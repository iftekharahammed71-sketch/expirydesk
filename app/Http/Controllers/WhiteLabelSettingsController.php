<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhiteLabelUpdateRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WhiteLabelSettingsController extends Controller
{
    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        $settings = $company->whiteLabelSettings()->firstOrCreate([
            'company_id' => $company->id,
        ], [
            'company_display_name' => $company->name,
            'brand_color' => '#2563eb',
            'mail_from_name' => $company->name,
                'mail_from_address' => (string) config('mail.from.address'),
        ]);

        return view('settings.white-label', compact('company', 'settings'));
    }

    public function update(WhiteLabelUpdateRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);
        $settings = $company->whiteLabelSettings()->firstOrCreate(['company_id' => $company->id]);
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('local')->delete($settings->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('branding/'.$company->id, 'local');
        }

        $settings->update($data);

        return back()->with('success', __('settings.messages.updated'));
    }
}
