<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderSettingsRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReminderSettingsController extends Controller
{
    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('settings.reminders', [
            'company' => $company,
            'leadTimes' => $company->reminderSettings()->where('is_enabled', true)->orderByDesc('days_before')->pluck('days_before')->all(),
        ]);
    }

    public function update(ReminderSettingsRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);
        $company->reminderSettings()->delete();

        foreach ($request->validated('lead_times', []) as $days) {
            $company->reminderSettings()->create([
                'days_before' => $days,
                'is_enabled' => true,
            ]);
        }

        return back()->with('success', __('settings.reminders_updated'));
    }
}
