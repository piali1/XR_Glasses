<?php

use App\Models\PharmacistRelease;

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
        'media_type' => ['nullable', 'string', 'max:255'],
        'media_title' => ['nullable', 'string', 'max:255'],
        'media_url' => ['nullable', 'string', 'max:2048'],
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



Route::get('/training-media/preparation-setup', function () {
    if ($guard = require_demo_staff(['pta', 'pharmacist', 'supervisor', 'admin'])) { return $guard; }

    return view('processes.training-media');
});

Route::get('/pharmacist/release/latest', function () {
    if ($guard = require_demo_staff(['pharmacist', 'supervisor', 'admin'])) { return $guard; }

    $batch = \App\Models\Batch::latest()->first();

    if (! $batch) {
        return redirect('/history')->with('error', 'No batch available for pharmacist release.');
    }

    return redirect('/pharmacist/release/' . $batch->id);
});

Route::get('/pharmacist/release/{batch}', function (\App\Models\Batch $batch) {
    if ($guard = require_demo_staff(['pharmacist', 'supervisor', 'admin'])) { return $guard; }

    $batch->load(['recipeTemplate', 'scans', 'logs', 'issues', 'supervisorReview']);

    $contentItems = \App\Models\ContentItem::query()
        ->where(function ($query) use ($batch) {
            $query->whereNull('process')
                ->orWhere('process', $batch->process);
        })
        ->orderBy('step_number')
        ->orderBy('type')
        ->orderBy('title')
        ->get();

    $release = PharmacistRelease::query()
        ->where('batch_id', $batch->id)
        ->latest()
        ->first();

    return view('processes.pharmacist-release', [
        'batch' => $batch,
        'contentItems' => $contentItems,
        'release' => $release,
    ]);
});

Route::post('/pharmacist/release/{batch}', function (\Illuminate\Http\Request $request, \App\Models\Batch $batch) {
    if ($guard = require_demo_staff(['pharmacist', 'supervisor', 'admin'])) { return $guard; }

    $validated = $request->validate([
        'decision' => ['required', 'string', 'in:released,rejected,correction_requested'],
        'comment' => ['nullable', 'string', 'max:3000'],
    ]);

    $batch->load(['scans', 'logs', 'issues']);

    $invalidScans = $batch->scans->where('is_valid', false)->count();
    $validScans = $batch->scans->where('is_valid', true)->count();
    $issueCount = $batch->issues->count();
    $documentedSteps = $batch->logs->count();

    $riskLevel = 'low';

    if ($invalidScans > 0 || $issueCount > 0) {
        $riskLevel = 'high';
    } elseif ($documentedSteps < 3 || $validScans < 3) {
        $riskLevel = 'medium';
    }

    $contentItems = \App\Models\ContentItem::query()
        ->where(function ($query) use ($batch) {
            $query->whereNull('process')
                ->orWhere('process', $batch->process);
        })
        ->get();

    $staff = session('staff');

    PharmacistRelease::updateOrCreate(
        ['batch_id' => $batch->id],
        [
            'reviewer_name' => $staff['name'] ?? 'Pharmacist',
            'reviewer_role' => $staff['role'] ?? 'pharmacist',
            'decision' => $validated['decision'],
            'risk_level' => $riskLevel,
            'material_summary' => [
                'valid_scans' => $validScans,
                'invalid_scans' => $invalidScans,
                'total_scans' => $batch->scans->count(),
            ],
            'checklist_summary' => [
                'documented_steps' => $documentedSteps,
                'timer_confirmations' => $batch->logs->where('timer_used', true)->count(),
                'materials_verified_steps' => $batch->logs->where('materials_verified', true)->count(),
            ],
            'issue_summary' => [
                'reported_issues' => $issueCount,
            ],
            'content_version_summary' => $contentItems
                ->map(fn ($item) => [
                    'type' => $item->type,
                    'title' => $item->title,
                    'version' => $item->version,
                    'valid_until' => optional($item->valid_until)->format('Y-m-d'),
                    'approval_status' => $item->approval_status,
                ])
                ->values()
                ->all(),
            'comment' => $validated['comment'] ?? null,
            'released_at' => now(),
        ]
    );

    if ($validated['decision'] === 'released') {
        $batch->status = 'pharmacist_released';
    } elseif ($validated['decision'] === 'rejected') {
        $batch->status = 'pharmacist_rejected';
    } else {
        $batch->status = 'correction_requested';
    }

    $batch->save();

    return redirect('/pharmacist/release/' . $batch->id)
        ->with('status', 'Pharmacist release decision saved.');
});


Route::get('/admin/content/{contentItem}/edit', function (\App\Models\ContentItem $contentItem) {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    return view('processes.admin-content-edit', [
        'contentItem' => $contentItem,
    ]);
});

Route::post('/admin/content/{contentItem}/update', function (\Illuminate\Http\Request $request, \App\Models\ContentItem $contentItem) {
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
        'media_type' => ['nullable', 'string', 'max:255'],
        'media_title' => ['nullable', 'string', 'max:255'],
        'media_url' => ['nullable', 'string', 'max:2048'],
        'content' => ['required', 'string'],
    ]);

    $validated['version'] = $validated['version'] ?? 'v1.0';
    $validated['approval_status'] = $validated['approval_status'] ?? 'draft';
    $validated['display_context'] = $validated['display_context'] ?? 'workflow';

    $contentItem->update($validated);

    return redirect('/admin/content')->with('status', 'Content item updated.');
});

Route::post('/admin/content/{contentItem}/archive', function (\App\Models\ContentItem $contentItem) {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    $contentItem->update([
        'approval_status' => 'archived',
    ]);

    return redirect('/admin/content')->with('status', 'Content item archived.');
});

Route::post('/admin/content/{contentItem}/duplicate', function (\App\Models\ContentItem $contentItem) {
    if ($guard = require_demo_staff(['admin'])) { return $guard; }

    $currentVersion = $contentItem->version ?: 'v1.0';
    $newVersion = $currentVersion . ' copy';

    if (preg_match('/^v?(\d+)\.(\d+)$/i', $currentVersion, $matches)) {
        $major = (int) $matches[1];
        $minor = (int) $matches[2] + 1;
        $newVersion = 'v' . $major . '.' . $minor;
    }

    $copy = $contentItem->replicate();
    $copy->title = $contentItem->title . ' - new version';
    $copy->version = $newVersion;
    $copy->approval_status = 'draft';
    $copy->save();

    return redirect('/admin/content')->with('status', 'New draft version created.');
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
