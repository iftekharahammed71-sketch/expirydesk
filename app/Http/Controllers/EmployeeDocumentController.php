<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDocumentStoreRequest;
use App\Http\Requests\EmployeeDocumentUpdateRequest;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeDocumentController extends Controller
{
    public function store(EmployeeDocumentStoreRequest $request, Employee $employee): RedirectResponse
    {
        $this->authorize('create', EmployeeDocument::class);
        $this->authorize('view', $employee);

        $file = $request->file('file');
        $path = $file->store('documents/'.$employee->id, 'local');

        $employee->documents()->create([
            ...$request->safe()->except('file'),
            'file_path' => $path,
            'original_filename' => Str::limit(basename($file->getClientOriginalName()), 255, ''),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('success', __('documents.messages.created'));
    }

    public function update(EmployeeDocumentUpdateRequest $request, EmployeeDocument $document): RedirectResponse
    {
        $data = $request->safe()->except('file');
        $file = $request->file('file');

        if ($file) {
            Storage::disk('local')->delete($document->file_path);
            $path = $file->store('documents/'.$document->employee_id, 'local');
            $data = [
                ...$data,
                'file_path' => $path,
                'original_filename' => Str::limit(basename($file->getClientOriginalName()), 255, ''),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ];
        }

        $document->update($data);

        return back()->with('success', __('documents.messages.updated'));
    }

    public function destroy(EmployeeDocument $document): RedirectResponse
    {
        $this->authorize('delete', $document);
        Storage::disk('local')->delete($document->file_path);
        $employee = $document->employee;
        $document->delete();

        return redirect()->route('employees.show', $employee)->with('success', __('documents.messages.deleted'));
    }
}
