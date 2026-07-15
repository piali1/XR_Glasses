<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pharmacist Release</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/pharmacist-release.css') }}" />
</head>
<body>
@include('partials.staff-bar')

@php
  $validScans = $batch->scans->where('is_valid', true)->count();
  $invalidScans = $batch->scans->where('is_valid', false)->count();
  $issueCount = $batch->issues->count();
  $documentedSteps = $batch->logs->count();
  $timerConfirmations = $batch->logs->where('timer_used', true)->count();
  $materialVerifiedSteps = $batch->logs->where('materials_verified', true)->count();

  $riskLevel = 'low';
  if ($invalidScans > 0 || $issueCount > 0) {
    $riskLevel = 'high';
  } elseif ($documentedSteps < 3 || $validScans < 3) {
    $riskLevel = 'medium';
  }

  $decisionLabel = [
    'released' => 'Released',
    'rejected' => 'Rejected',
    'correction_requested' => 'Correction requested',
  ][$release->decision ?? ''] ?? 'No final decision yet';
@endphp

<main class="release-page">

  <header class="release-hero">
    <p class="release-eyebrow">Pharmacist review</p>
    <h1>Pharmacist Release Decision</h1>
    <p>
      This page simulates the professional release step after the guided pharmacy workflow.
      A pharmacist reviews material validation, checklist evidence, reported issues,
      audit data, content versions and risk level before making a final decision.
    </p>

    <div class="release-actions">
      <a href="/audit/{{ $batch->id }}">Open audit timeline</a>
      <a href="/history">Process history</a>
      <a href="/hub">XR Hub</a>
    </div>
  </header>

  @if(session('status'))
    <div class="status-message">{{ session('status') }}</div>
  @endif

  <section class="batch-overview">
    <div>
      <strong>Batch</strong>
      <span>{{ $batch->batch_id }}</span>
    </div>
    <div>
      <strong>Process</strong>
      <span>{{ $batch->process }}</span>
    </div>
    <div>
      <strong>Status</strong>
      <span>{{ $batch->status }}</span>
    </div>
    <div>
      <strong>Template</strong>
      <span>{{ $batch->recipeTemplate->reference_code ?? 'not assigned' }}</span>
    </div>
    <div class="risk-card {{ $riskLevel }}">
      <strong>Risk level</strong>
      <span>{{ strtoupper($riskLevel) }}</span>
    </div>
  </section>

  <section class="release-grid">

    <article class="release-card">
      <h2>Material scans</h2>
      <div class="metric-grid">
        <div><strong>{{ $validScans }}</strong><span>valid scans</span></div>
        <div><strong>{{ $invalidScans }}</strong><span>invalid scans</span></div>
        <div><strong>{{ $batch->scans->count() }}</strong><span>total scans</span></div>
      </div>

      <div class="detail-list">
        @forelse($batch->scans as $scan)
          <div class="{{ $scan->is_valid ? 'ok' : 'danger' }}">
            <strong>Step {{ $scan->step_number }}</strong>
            <span>{{ $scan->material_name ?? $scan->material_code }} · {{ $scan->is_valid ? 'valid' : 'invalid' }}</span>
          </div>
        @empty
          <p>No material scans documented yet.</p>
        @endforelse
      </div>
    </article>

    <article class="release-card">
      <h2>Checklist and process evidence</h2>
      <div class="metric-grid">
        <div><strong>{{ $documentedSteps }}</strong><span>documented steps</span></div>
        <div><strong>{{ $materialVerifiedSteps }}</strong><span>material checks</span></div>
        <div><strong>{{ $timerConfirmations }}</strong><span>time confirmations</span></div>
      </div>

      <div class="detail-list">
        @forelse($batch->logs as $log)
          <div class="ok">
            <strong>Step {{ $log->step_number }}</strong>
            <span>{{ $log->step_title }} · {{ $log->materials_verified ? 'materials verified' : 'materials open' }} · {{ $log->timer_used ? 'time confirmed' : 'time open' }}</span>
          </div>
        @empty
          <p>No process logs documented yet.</p>
        @endforelse
      </div>
    </article>

    <article class="release-card">
      <h2>Reported issues</h2>
      <div class="metric-grid">
        <div><strong>{{ $issueCount }}</strong><span>reported issues</span></div>
      </div>

      <div class="detail-list">
        @forelse($batch->issues as $issue)
          <div class="danger">
            <strong>Step {{ $issue->step_number }}</strong>
            <span>{{ $issue->issue }}</span>
          </div>
        @empty
          <p>No issues reported for this batch.</p>
        @endforelse
      </div>
    </article>

    <article class="release-card">
      <h2>Content versions used</h2>
      <div class="detail-list content-version-list">
        @forelse($contentItems as $item)
          <div class="{{ $item->approval_status === 'archived' ? 'danger' : 'ok' }}">
            <strong>{{ strtoupper(str_replace('_', ' ', $item->type)) }} · {{ $item->version }}</strong>
            <span>
              {{ $item->title }}
              · valid until {{ optional($item->valid_until)->format('Y-m-d') ?? 'not specified' }}
              · {{ $item->approval_status }}
            </span>
          </div>
        @empty
          <p>No related content versions found.</p>
        @endforelse
      </div>
    </article>

  </section>

  <section class="decision-panel">
    <div>
      <p class="release-eyebrow">Final professional decision</p>
      <h2>{{ $decisionLabel }}</h2>

      @if($release)
        <p>
          Reviewed by {{ $release->reviewer_name }} as {{ $release->reviewer_role }}.
          Risk level at decision time: {{ strtoupper($release->risk_level) }}.
        </p>

        @if($release->comment)
          <blockquote>{{ $release->comment }}</blockquote>
        @endif
      @else
        <p>No pharmacist release decision has been saved for this batch yet.</p>
      @endif
    </div>

    <form method="POST" action="/pharmacist/release/{{ $batch->id }}" class="decision-form">
      @csrf

      <label>
        Pharmacist comment
        <textarea name="comment" placeholder="Document final review notes, correction request or release reason.">{{ old('comment', $release->comment ?? '') }}</textarea>
      </label>

      <div class="decision-buttons">
        <button type="submit" name="decision" value="released" class="release-button">Release batch</button>
        <button type="submit" name="decision" value="correction_requested" class="correction-button">Request correction</button>
        <button type="submit" name="decision" value="rejected" class="reject-button">Reject batch</button>
      </div>
    </form>
  </section>

</main>

</body>
</html>
