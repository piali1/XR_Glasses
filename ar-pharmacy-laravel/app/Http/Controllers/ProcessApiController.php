<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Material;
use App\Models\MaterialScan;
use App\Models\ProcessIssue;
use App\Models\ProcessLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessApiController extends Controller
{
    public function createBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['required', 'string', 'max:255'],
            'process' => ['required', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'workstation' => ['nullable', 'string', 'max:255'],
        ]);

        $batch = Batch::updateOrCreate(
            ['batch_id' => $validated['batch_id']],
            [
                'process' => $validated['process'],
                'operator_name' => $validated['operator_name'] ?? null,
                'workstation' => $validated['workstation'] ?? null,
                'started_at' => now(),
                'status' => 'in_progress',
            ]
        );

        return response()->json([
            'id' => $batch->id,
            'batch_id' => $batch->batch_id,
            'status' => $batch->status,
        ]);
    }

    public function validateMaterial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'process' => ['required', 'string', 'max:255'],
            'step_number' => ['required', 'integer', 'min:1'],
            'code' => ['required', 'string', 'max:255'],
        ]);

        $material = Material::where('process', $validated['process'])
            ->where('step_number', $validated['step_number'])
            ->where('code', $validated['code'])
            ->first();

        MaterialScan::create([
            'batch_id' => $validated['batch_id'] ?? null,
            'process' => $validated['process'],
            'step_number' => $validated['step_number'],
            'material_code' => $validated['code'],
            'material_name' => $material?->name,
            'is_valid' => (bool) $material,
            'scanned_at' => now(),
        ]);

        $expected = Material::where('process', $validated['process'])
            ->where('step_number', $validated['step_number'])
            ->orderBy('id')
            ->get(['name', 'code']);

        return response()->json([
            'valid' => (bool) $material,
            'material' => $material,
            'expected' => $expected,
        ]);
    }

    public function storeProcessLog(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'step_number' => ['required', 'integer', 'min:1'],
            'step_title' => ['required', 'string', 'max:255'],
            'timer_used' => ['required', 'boolean'],
            'materials_verified' => ['required', 'boolean'],
        ]);

        $log = ProcessLog::create([
            ...$validated,
            'logged_at' => now(),
        ]);

        return response()->json($log);
    }

    public function storeIssue(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'step_number' => ['required', 'integer', 'min:1'],
            'issue' => ['required', 'string', 'max:255'],
        ]);

        $issue = ProcessIssue::create([
            ...$validated,
            'reported_at' => now(),
        ]);

        return response()->json($issue);
    }

    public function completeBatch(Request $request, Batch $batch): JsonResponse
    {
        $batch->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'id' => $batch->id,
            'batch_id' => $batch->batch_id,
            'status' => $batch->status,
            'completed_at' => $batch->completed_at,
        ]);
    }

    public function history(): JsonResponse
    {
        $batches = Batch::withCount(['logs', 'issues', 'scans'])
            ->latest()
            ->take(20)
            ->get();

        return response()->json($batches);
    }
}
