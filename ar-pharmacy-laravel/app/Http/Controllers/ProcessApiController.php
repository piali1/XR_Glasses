<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Material;
use App\Models\MaterialScan;
use App\Models\ProcessIssue;
use App\Models\ProcessLog;
use App\Models\RecipeTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessApiController extends Controller
{
    public function recipeTemplates(Request $request): JsonResponse
    {
        $query = RecipeTemplate::query()
            ->with(['steps']);

        if ($request->filled('process')) {
            $query->where('process', $request->string('process'));
        }

        $templates = $query->orderBy('id')->get();

        return response()->json($templates);
    }

    public function recipeTemplate(RecipeTemplate $template): JsonResponse
    {
        $template->load([
            'steps',
            'materials' => fn ($query) => $query->orderBy('step_number')->orderBy('id'),
        ]);

        $materialsByStep = $template->materials->groupBy('step_number');

        $steps = $template->steps->map(function ($step) use ($materialsByStep) {
            return [
                'step_number' => $step->step_number,
                'title' => $step->title,
                'description' => $step->description,
                'warning' => $step->warning,
                'ar_hint' => $step->ar_hint,
                'risk' => $step->risk,
                'timer_seconds' => $step->timer_seconds,
                'checklist_items' => $step->checklist_items ?? [],
                'materials' => ($materialsByStep[$step->step_number] ?? collect())->values()->map(fn ($material) => [
                    'name' => $material->name,
                    'code' => $material->code,
                ]),
            ];
        });

        return response()->json([
            'id' => $template->id,
            'process' => $template->process,
            'title' => $template->title,
            'reference_source' => $template->reference_source,
            'reference_code' => $template->reference_code,
            'dosage_form' => $template->dosage_form,
            'source_note' => $template->source_note,
            'is_demo' => $template->is_demo,
            'steps' => $steps,
        ]);
    }

    public function createBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['required', 'string', 'max:255'],
            'recipe_template_id' => ['nullable', 'integer', 'exists:recipe_templates,id'],
            'process' => ['required', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'workstation' => ['nullable', 'string', 'max:255'],
        ]);

        $batch = Batch::updateOrCreate(
            ['batch_id' => $validated['batch_id']],
            [
                'recipe_template_id' => $validated['recipe_template_id'] ?? null,
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
            'recipe_template_id' => $batch->recipe_template_id,
            'status' => $batch->status,
        ]);
    }

    public function validateMaterial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'recipe_template_id' => ['nullable', 'integer', 'exists:recipe_templates,id'],
            'process' => ['required', 'string', 'max:255'],
            'step_number' => ['required', 'integer', 'min:1'],
            'code' => ['required', 'string', 'max:255'],
        ]);

        $query = Material::query()
            ->where('step_number', $validated['step_number'])
            ->where('code', $validated['code']);

        if (!empty($validated['recipe_template_id'])) {
            $query->where('recipe_template_id', $validated['recipe_template_id']);
        } else {
            $query->where('process', $validated['process']);
        }

        $material = $query->first();

        MaterialScan::create([
            'batch_id' => $validated['batch_id'] ?? null,
            'process' => $validated['process'],
            'step_number' => $validated['step_number'],
            'material_code' => $validated['code'],
            'material_name' => $material?->name,
            'is_valid' => (bool) $material,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'valid' => (bool) $material,
            'material' => $material,
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
        $batches = Batch::with(['recipeTemplate'])
            ->withCount(['logs', 'issues', 'scans'])
            ->latest()
            ->take(20)
            ->get();

        return response()->json($batches);
    }
}
