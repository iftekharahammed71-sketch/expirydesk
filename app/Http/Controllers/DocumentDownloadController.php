<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentDownloadController extends Controller
{
    public function __invoke(EmployeeDocument $document): BinaryFileResponse
    {
        $this->authorize('download', $document);

        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return response()->download(
            Storage::disk('local')->path($document->file_path),
            $document->original_filename,
            ['Content-Type' => $document->mime_type]
        );
    }
}
