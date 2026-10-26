<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Branch::class);
        $user = $request->user();

        return view('branches.index', [
            'branches' => Branch::query()->with('company')
                ->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))
                ->when($user->isManager(), fn (Builder $query) => $query->whereKey($user->branch_id))
                ->orderBy('name')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Branch::class);

        return view('branches.create', [
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $request->user()->isAdmin() ? (int) $data['company_id'] : $request->user()->company_id;
        Branch::create($data);

        return redirect()->route('branches.index')->with('success', __('branches.messages.created'));
    }

    public function edit(Request $request, Branch $branch): View
    {
        $this->authorize('update', $branch);

        return view('branches.edit', [
            'branch' => $branch,
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ]);
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $this->authorize('update', $branch);
        $data = $request->validated();
        $data['company_id'] = $branch->company_id;
        $branch->update($data);

        return redirect()->route('branches.index')->with('success', __('branches.messages.updated'));
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->authorize('delete', $branch);
        abort_if($branch->employees()->exists(), 422, __('branches.messages.in_use'));
        $branch->delete();

        return redirect()->route('branches.index')->with('success', __('branches.messages.deleted'));
    }
}
