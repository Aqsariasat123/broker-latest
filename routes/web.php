<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LookupCategoryController;
use App\Http\Controllers\LookupValueController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LifeProposalController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\StatementController;




Route::get('/', function () {
    return redirect('/login');
});

// Login routes
Route::get('/login', function () {
    // If user is logged in, redirect to dashboard
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return app(AuthController::class)->showLoginForm();
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// All protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::get('/{task}/get', [TaskController::class, 'getTask'])->name('tasks.get');
        Route::post('/columns/settings', [TaskController::class, 'saveColumnSettings'])->name('tasks.save-column-settings');
        Route::get('/export', [TaskController::class, 'export'])->name('tasks.export');
    });

    Route::prefix('lookups')->group(function () {
        Route::get('/', [LookupCategoryController::class, 'index'])->name('lookups.index');
        Route::get('/categories/create', [LookupCategoryController::class, 'create'])->name('lookup-categories.create');
        Route::post('/categories', [LookupCategoryController::class, 'store'])->name('lookup-categories.store');
        Route::get('/categories/{lookupCategory}/edit', [LookupCategoryController::class, 'edit'])->name('lookup-categories.edit');
        Route::put('/categories/{lookupCategory}', [LookupCategoryController::class, 'update'])->name('lookup-categories.update');
        Route::delete('/categories/{lookupCategory}', [LookupCategoryController::class, 'destroy'])->name('lookup-categories.destroy');
        Route::get('/categories/{lookupCategory}/values/create', [LookupValueController::class, 'create'])->name('lookup-values.create');
        Route::post('/categories/{lookupCategory}/values', [LookupValueController::class, 'store'])->name('lookup-values.store');
        Route::get('/values/{lookupValue}/edit', [LookupValueController::class, 'edit'])->name('lookup-values.edit');
        Route::put('/values/{lookupValue}', [LookupValueController::class, 'update'])->name('lookup-values.update');
        Route::delete('/values/{lookupValue}', [LookupValueController::class, 'destroy'])->name('lookup-values.destroy');
    });

    // Policies Routes
    Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');
    Route::get('/policies/create', [PolicyController::class, 'create'])->name('policies.create');
    Route::post('/policies', [PolicyController::class, 'store'])->name('policies.store');
    Route::get('/policies/{policy}', [PolicyController::class, 'show'])->name('policies.show');
    Route::get('/policies/{policy}/edit', [PolicyController::class, 'edit'])->name('policies.edit');
    Route::put('/policies/{policy}', [PolicyController::class, 'update'])->name('policies.update');
    Route::delete('/policies/{policy}', [PolicyController::class, 'destroy'])->name('policies.destroy');
    Route::get('/policies/export', [PolicyController::class, 'export'])->name('policies.export');
    Route::post('/policies/save-column-settings', [PolicyController::class, 'saveColumnSettings'])->name('policies.save-column-settings');

    // Clients Routes
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::resource('clients', ClientController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::post('/clients/save-column-settings', [ClientController::class, 'saveColumnSettings'])->name('clients.save-column-settings');

    // Contacts Routes
    Route::get('/contacts/export', [ContactController::class, 'export'])->name('contacts.export');
    Route::resource('contacts', ContactController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('/contacts/save-column-settings', [ContactController::class, 'saveColumnSettings'])->name('contacts.save-column-settings');

    // Life Proposals Routes
    Route::resource('life-proposals', LifeProposalController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
    Route::get('/life-proposals/{lifeProposal}/edit', [LifeProposalController::class, 'edit'])->name('life-proposals.edit');
    Route::get('/life-proposals/export', [LifeProposalController::class, 'export'])->name('life-proposals.export');
    Route::post('/life-proposals/save-column-settings', [LifeProposalController::class, 'saveColumnSettings'])->name('life-proposals.save-column-settings');

    // Expenses Routes
    Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::post('/expenses/save-column-settings', [ExpenseController::class, 'saveColumnSettings'])->name('expenses.save-column-settings');
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'update', 'destroy', 'show', 'edit']);

    // Documents Routes
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/save-column-settings', [DocumentController::class, 'saveColumnSettings'])->name('documents.save-column-settings');
    Route::get('/documents/export', [DocumentController::class, 'export'])->name('documents.export');

    // Vehicles Routes
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/export', [VehicleController::class, 'export'])->name('vehicles.export');
    Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    Route::post('/vehicles/save-column-settings', [VehicleController::class, 'saveColumnSettings'])->name('vehicles.save-column-settings');

    // Claims Routes
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}/edit', [ClaimController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ClaimController::class, 'update'])->name('claims.update');
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
    Route::get('/claims/export', [ClaimController::class, 'export'])->name('claims.export');
    Route::post('/claims/save-column-settings', [ClaimController::class, 'saveColumnSettings'])->name('claims.save-column-settings');

    // Income Routes
    Route::get('/incomes/export', [IncomeController::class, 'export'])->name('incomes.export');
    Route::post('/incomes/save-column-settings', [IncomeController::class, 'saveColumnSettings'])->name('incomes.save-column-settings');
    Route::get('/incomes', [IncomeController::class, 'index'])->name('incomes.index');
    Route::post('/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::get('/incomes/{income}/edit', [IncomeController::class, 'edit'])->name('incomes.edit');
    Route::put('/incomes/{income}', [IncomeController::class, 'update'])->name('incomes.update');
    Route::delete('/incomes/{income}', [IncomeController::class, 'destroy'])->name('incomes.destroy');

    // Commissions Routes
    Route::get('/commissions/export', [CommissionController::class, 'export'])->name('commissions.export');
    Route::post('/commissions/save-column-settings', [CommissionController::class, 'saveColumnSettings'])->name('commissions.save-column-settings');
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
    Route::get('/commissions/{commission}/edit', [CommissionController::class, 'edit'])->name('commissions.edit');
    Route::put('/commissions/{commission}', [CommissionController::class, 'update'])->name('commissions.update');
    Route::delete('/commissions/{commission}', [CommissionController::class, 'destroy'])->name('commissions.destroy');

    // Statements Routes
    Route::get('/statements/export', [StatementController::class, 'export'])->name('statements.export');
    Route::post('/statements/save-column-settings', [StatementController::class, 'saveColumnSettings'])->name('statements.save-column-settings');
    Route::get('/statements', [StatementController::class, 'index'])->name('statements.index');
    Route::post('/statements', [StatementController::class, 'store'])->name('statements.store');
    Route::get('/statements/{statement}/edit', [StatementController::class, 'edit'])->name('statements.edit');
    Route::put('/statements/{statement}', [StatementController::class, 'update'])->name('statements.update');
    Route::delete('/statements/{statement}', [StatementController::class, 'destroy'])->name('statements.destroy');

    Route::view('/calendar', 'calender.index')->name('calendar');
});

