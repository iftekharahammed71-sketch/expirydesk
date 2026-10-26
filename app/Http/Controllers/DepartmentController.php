<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Department::class);
        $user = $request->user();

        return view('departments.index', [
            'departments' => Department::query()->with('company')
                ->when(! $user->isAdmin(), fn (Builder $query) => $query->where('company_id', $user->company_id))
                ->orderBy('name')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Department::class);

        return view('departments.create', [
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $request->user()->isAdmin() ? (int) $data['company_id'] : $request->user()->company_id;
        Department::create($data);

        return redirect()->route('departments.index')->with('success', __('departments.messages.created'));
    }

    public function edit(Request $request, Department $department): View
    {
        $this->authorize('update', $department);

        return view('departments.edit', [
            'department' => $department,
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ]);
    }

    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->authorize('update', $department);
        $data = $request->validated();
        $data['company_id'] = $department->company_id;
        $department->update($data);

        return redirect()->route('departments.index')->with('success', __('departments.messages.updated'));
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('delete', $department);
        abort_if($department->employees()->exists(), 422, __('departments.messages.in_use'));
        $department->delete();

        return redirect()->route('departments.index')->with('success', __('departments.messages.deleted'));
    }
}
