<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Batch Audit Timeline</title>
  <link rel="stylesheet" href="{{ asset('css/audit.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body>
@include('partials.staff-bar')

@php
  $events = collect();

  $events->push([
    'time' => $batch->started_at,
    'type' => 'Batch',
    'title' => 'Batch created',
    'description' => 'Batch ' . $batch->batch_id . ' started for process ' . $batch->process . '.',
    'status' => 'started',
  ]);

  foreach ($batch->scans as $scan) {
    $events->push([
      'time' => $scan->scanned_at,
      'type' => 'Material scan',
      'title' => $scan->is_valid ? 'Valid material scanned' : 'Invalid material scanned',
      'description' => 'Step ' . $scan->step_number . ': ' . ($scan->material_name ?? $scan->material_code),
      'status' => $scan->is_valid ? 'valid' : 'blocked',
    ]);
  }

  foreach ($batch->issues as $issue) {
    $events->push([
      'time' => $issue->reported_at,
      'type' => 'Issue',
      'title' => 'Issue reported',
      'description' => 'Step ' . $issue->step_number . ': ' . $issue->issue,
      'status' => 'issue',
    ]);
  }

  foreach ($batch->logs as $log) {
    $details = [];
    if ($log->materials_verified) {
      $details[] = 'materials verified';
    }
    if ($log->timer_used) {
      $details[] = 'required process time completed';
    }

    $events->push([
      'time' => $log->logged_at,
      'type' => 'Process log',
      'title' => 'Step documented',
      'description' => 'Step ' . $log->step_number . ': ' . $log->step_title . (count($details) ? ' (' . implode(', ', $details) . ')' : ''),
      'status' => 'documented',
    ]);
  }

  if ($batch->completed_at) {
    $events->push([
      'time' => $batch->completed_at,
      'type' => 'Completion',
      'title' => 'Batch completed',
      'description' => 'Workflow completed and saved to backend.',
      'status' => 'completed',
    ]);
  }

  if ($batch->supervisorReview) {
    $events->push([
      'time' => $batch->supervisorReview->reviewed_at,
      'type' => 'Supervisor review',
      'title' => ucfirst($batch->supervisorReview->status),
      'description' => 'Reviewed by ' . ($batch->supervisorReview->reviewer_name ?? 'Supervisor') . '. ' . ($batch->supervisorReview->comment ?? ''),
      'status' => $batch->supervisorReview->status,
    ]);
  }

  $events = $events->sortBy(function ($event) {
    return $event['time'] ? \Illuminate\Support\Carbon::parse($event['time'])->timestamp : 0;
  })->values();
@endphp

<div class="audit-page">

  <header class="audit-header">
    <p class="eyebrow">QM evidence</p>
    <h1>Batch Audit Timeline</h1>
    <p>
      This timeline shows how the prototype documents process events for traceability,
      quality management and supervisor review.
    </p>

    <div class="audit-actions">
      <a href="/history">Back to history</a>
      <a href="/hub">XR Hub</a>
      <a href="/">Process selection</a>
    </div>
  </header>

  <section class="batch-summary">
    <div><strong>Batch ID</strong><span>{{ $batch->batch_id }}</span></div>
    <div><strong>Process</strong><span>{{ $batch->process }}</span></div>
    <div><strong>Status</strong><span>{{ $batch->status }}</span></div>
    <div><strong>Template</strong><span>{{ $batch->recipeTemplate->reference_code ?? 'not assigned' }}</span></div>
  </section>

  <main class="timeline">
    @foreach($events as $event)
      @php
        $displayTime = $event['time']
          ? \Illuminate\Support\Carbon::parse($event['time'])->format('Y-m-d H:i:s')
          : 'No timestamp';
      @endphp

      <article class="timeline-item {{ $event['status'] }}">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <span>{{ $event['type'] }} · {{ $displayTime }}</span>
          <h2>{{ $event['title'] }}</h2>
          <p>{{ $event['description'] }}</p>
        </div>
      </article>
    @endforeach
  </main>

</div>

</body>
</html>
