<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessApiController;
use App\Http\Controllers\DemoAuthController;


if (! function_exists('require_demo_staff')) {
    function require_demo_staff(array $roles = [])
    {
        $staff = session('staff');

        if (! $staff) {
            return redirect()->guest('/login')->with('error', 'Please sign in as pharmacy staff first.');
        }

        if ($roles && ! in_array($staff['role'], $roles, true)) {
            return redirect('/login')->with('error', 'This area requires one of these roles: ' . implode(', ', $roles) . '.');
        }

        return null;
    }
}


Route::get('/login', [DemoAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [DemoAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [DemoAuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('processes.index');
});

Route::get('/workflow', function () {
    if ($guard = require_demo_staff(['pta', 'pharmacist', 'supervisor', 'admin'])) { return $guard; }
    return view('processes.workflow');
});


Route::post('/api/batches', [ProcessApiController::class, 'createBatch']);
Route::post('/api/materials/validate', [ProcessApiController::class, 'validateMaterial']);
Route::post('/api/process-logs', [ProcessApiController::class, 'storeProcessLog']);
Route::post('/api/issues', [ProcessApiController::class, 'storeIssue']);
Route::post('/api/batches/{batch}/complete', [ProcessApiController::class, 'completeBatch']);
Route::get('/api/history', [ProcessApiController::class, 'history']);


Route::get('/api/content-items', [ProcessApiController::class, 'contentItems']);
Route::get('/api/recipe-templates', [ProcessApiController::class, 'recipeTemplates']);
Route::get('/api/recipe-templates/{template}', [ProcessApiController::class, 'recipeTemplate']);

Route::get('/hub', function () {
    return view('processes.hub');
});


Route::get('/admin/content', function () {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    $contentItems = \App\Models\ContentItem::query()
        ->orderBy('type')
        ->orderBy('process')
        ->orderBy('step_number')
        ->orderBy('title')
        ->get();

    return view('processes.admin-content', [
        'contentItems' => $contentItems,
    ]);
});

Route::post('/admin/content', function (\Illuminate\Http\Request $request) {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    $validated = $request->validate([
        'type' => ['required', 'string', 'max:255'],
        'title' => ['required', 'string', 'max:255'],
        'version' => ['nullable', 'string', 'max:255'],
        'valid_from' => ['nullable', 'date'],
        'valid_until' => ['nullable', 'date'],
        'area' => ['nullable', 'string', 'max:255'],
        'responsible_role' => ['nullable', 'string', 'max:255'],
        'approval_status' => ['nullable', 'string', 'max:255'],
        'process' => ['nullable', 'string', 'max:255'],
        'step_number' => ['nullable', 'integer', 'min:1'],
        'display_context' => ['nullable', 'string', 'max:255'],
        'content' => ['required', 'string'],
    ]);

    $validated['version'] = $validated['version'] ?? 'v1.0';
    $validated['approval_status'] = $validated['approval_status'] ?? 'draft';
    $validated['display_context'] = $validated['display_context'] ?? 'workflow';

    \App\Models\ContentItem::create($validated);

    return redirect('/admin/content')->with('status', 'Content item created.');
});

Route::post('/admin/content/{contentItem}/status', function (\Illuminate\Http\Request $request, \App\Models\ContentItem $contentItem) {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    $validated = $request->validate([
        'approval_status' => ['required', 'string', 'max:255'],
    ]);

    $contentItem->update([
        'approval_status' => $validated['approval_status'],
    ]);

    return redirect('/admin/content')->with('status', 'Content item status updated.');
});

Route::get('/audit/latest', function () {
    if ($guard = require_demo_staff(['pharmacist', 'supervisor', 'admin'])) { return $guard; }

    $batch = \App\Models\Batch::latest()->first();

    if (!$batch) {
        return redirect('/history');
    }

    return redirect('/audit/' . $batch->id);
});

Route::get('/audit/{batch}', function (\App\Models\Batch $batch) {
    if ($guard = require_demo_staff(['pharmacist', 'supervisor', 'admin'])) { return $guard; }

    $batch->load(['recipeTemplate', 'scans', 'logs', 'issues', 'supervisorReview']);

    return view('processes.audit', [
        'batch' => $batch,
    ]);
});

Route::get('/history', function () {
    $batches = \App\Models\Batch::with(['recipeTemplate', 'supervisorReview'])
        ->withCount(['logs', 'issues', 'scans'])
        ->latest()
        ->get();

    return view('processes.history', [
        'batches' => $batches,
    ]);
});
Route::post('/api/batches/{batch}/review', [ProcessApiController::class, 'storeSupervisorReview']);
