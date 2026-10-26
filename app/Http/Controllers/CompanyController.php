<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        return view('companies.index', [
            'companies' => Company::query()->withCount(['branches', 'employees'])->orderBy('name')->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('companies.create');
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        $company = Company::create($request->validated());
        $company->whiteLabelSettings()->create([
            'company_display_name' => $company->name,
            'brand_color' => '#2563eb',
            'mail_from_name' => $company->name,
            'mail_from_address' => (string) config('mail.from.address'),
        ]);

        foreach ([90, 60, 30, 7] as $days) {
            $company->reminderSettings()->create(['days_before' => $days, 'is_enabled' => true]);
        }

        return redirect()->route('companies.index')->with('success', __('companies.messages.created'));
    }

    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);
        $company->update($request->validated());

        return redirect()->route('companies.index')->with('success', __('companies.messages.updated'));
    }
}
