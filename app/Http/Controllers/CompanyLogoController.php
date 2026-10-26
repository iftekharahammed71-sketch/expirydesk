<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CompanyLogoController extends Controller
{
    public function __invoke(Company $company): Response
    {
        $this->authorize('view', $company);
        $settings = $company->whiteLabelSettings;

        abort_unless($settings?->logo_path && Storage::disk('local')->exists($settings->logo_path), 404);

        return response(Storage::disk('local')->get($settings->logo_path), 200, [
            'Content-Type' => Storage::disk('local')->mimeType($settings->logo_path) ?: 'image/png',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
