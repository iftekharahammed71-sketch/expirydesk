<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Livewire\Dashboard;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyLogoController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocumentDownloadController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhiteLabelSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['auth', 'active'])->group(function (): void {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::livewire('/', Dashboard::class)->name('dashboard');
    Route::livewire('dashboard', Dashboard::class)->name('dashboard.index');

    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::get('companies/{company}/branding/logo', CompanyLogoController::class)->name('companies.logo');
    Route::get('companies/{company}/settings/reminders', [ReminderSettingsController::class, 'edit'])->name('settings.reminders.edit');
    Route::put('companies/{company}/settings/reminders', [ReminderSettingsController::class, 'update'])->name('settings.reminders.update');

    Route::get('branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('branches', [BranchController::class, 'store'])->name('branches.store');
    Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');

    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('document-types', [DocumentTypeController::class, 'index'])->name('document-types.index');
    Route::get('document-types/create', [DocumentTypeController::class, 'create'])->name('document-types.create');
    Route::post('document-types', [DocumentTypeController::class, 'store'])->name('document-types.store');
    Route::get('document-types/{document_type}/edit', [DocumentTypeController::class, 'edit'])->name('document-types.edit');
    Route::put('document-types/{document_type}', [DocumentTypeController::class, 'update'])->name('document-types.update');
    Route::delete('document-types/{document_type}', [DocumentTypeController::class, 'destroy'])->name('document-types.destroy');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::post('employees/{employee}/documents', [EmployeeDocumentController::class, 'store'])->name('employee-documents.store');
    Route::put('employee-documents/{document}', [EmployeeDocumentController::class, 'update'])->name('employee-documents.update');
    Route::delete('employee-documents/{document}', [EmployeeDocumentController::class, 'destroy'])->name('employee-documents.destroy');
    Route::get('employee-documents/{document}/download', DocumentDownloadController::class)->name('employee-documents.download');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('can:update,company')->group(function (): void {
        Route::get('companies/{company}/settings/white-label', [WhiteLabelSettingsController::class, 'edit'])->name('settings.white-label.edit');
        Route::put('companies/{company}/settings/white-label', [WhiteLabelSettingsController::class, 'update'])->name('settings.white-label.update');
    });
});
