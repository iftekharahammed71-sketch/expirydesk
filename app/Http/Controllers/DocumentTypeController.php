<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTypeRequest;
use App\Models\Company;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentTypeController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', DocumentType::class);
        $user = $request->user();

        return view('document-types.index', [
            'documentTypes' => DocumentType::query()->with('company')
                ->where(function (Builder $query) use ($user): void {
                    $query->whereNull('company_id');
                    if (! $user->isAdmin()) {
                        $query->orWhere('company_id', $user->company_id);
                    } else {
                        $query->orWhereNotNull('company_id');
                    }
                })
                ->orderBy('name')->paginate(20),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', DocumentType::class);

        return view('document-types.create', [
            'companies' => $request->user()->isAdmin() ? Company::query()->where('status', true)->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(DocumentTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['company_id'] = $request->user()->isAdmin() ? ($data['company_id'] ?? null) : $request->user()->company_id;
        $data['is_system'] = false;
        DocumentType::create($data);

        return redirect()->route('document-types.index')->with('success', __('document_types.messages.created'));
    }

    public function edit(DocumentType $document_type): View
    {
        $this->authorize('update', $document_type);

        return view('document-types.edit', ['documentType' => $document_type, 'companies' => collect()]);
    }

    public function update(DocumentTypeRequest $request, DocumentType $document_type): RedirectResponse
    {
        $this->authorize('update', $document_type);
        $data = $request->validated();
        unset($data['company_id']);
        $document_type->update($data);

        return redirect()->route('document-types.index')->with('success', __('document_types.messages.updated'));
    }

    public function destroy(DocumentType $document_type): RedirectResponse
    {
        $this->authorize('delete', $document_type);
        abort_if($document_type->employeeDocuments()->exists(), 422, __('document_types.messages.in_use'));
        $document_type->delete();

        return redirect()->route('document-types.index')->with('success', __('document_types.messages.deleted'));
    }
}
