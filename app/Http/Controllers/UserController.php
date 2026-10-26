<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);
        $user = $request->user();

        return view('users.index', [
            'users' => User::query()->with(['company', 'branch'])
                ->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))
                ->orderBy('name')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', User::class);

        return view('users.create', [
            'roles' => UserRole::cases(),
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
            'branches' => $request->user()->isAdmin() ? Branch::query()->where('is_active', true)->orderBy('name')->get() : Branch::query()->where('company_id', $request->user()->company_id)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        $data = $this->normalizeScope($data);
        User::create($data);

        return redirect()->route('users.index')->with('success', __('users.messages.created'));
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
            'branches' => $request->user()->isAdmin() ? Branch::query()->where('is_active', true)->orderBy('name')->get() : Branch::query()->where('company_id', $request->user()->company_id)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($this->normalizeScope($data));

        return redirect()->route('users.index')->with('success', __('users.messages.updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', __('users.messages.deleted'));
    }

    private function normalizeScope(array $data): array
    {
        unset($data['password_confirmation']);

        if (($data['role'] ?? null) === UserRole::ADMIN->value) {
            $data['company_id'] = null;
            $data['branch_id'] = null;
        }

        if (auth()->user()->isHr()) {
            $data['company_id'] = auth()->user()->company_id;
        }

        return $data;
    }
}
