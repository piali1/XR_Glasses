<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Process History</title>
  <link rel="stylesheet" href="{{ asset('css/history.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body>
@include('partials.staff-bar')
  <main class="history-page">
    <div class="history-header">
      <div>
        <p class="eyebrow">Backend database</p>
        <h1>Process History</h1>
        <p class="subtitle">
          Stored batches, material scans, process logs and reported issues from the Laravel backend.
        </p>
      </div>

      <a href="/" class="back-link">Back to process selection</a>
    </div>

    <section class="summary-grid">
      <article>
        <span>{{ $batches->count() }}</span>
        <p>Batches</p>
      </article>

      <article>
        <span>{{ $batches->sum('scans_count') }}</span>
        <p>Material scans</p>
      </article>

      <article>
        <span>{{ $batches->sum('logs_count') }}</span>
        <p>Process logs</p>
      </article>

      <article>
        <span>{{ $batches->sum('issues_count') }}</span>
        <p>Issues</p>
      </article>
    </section>

    <section class="history-card">
      <div class="card-header">
        <h2>Stored process runs</h2>
        <p>{{ $batches->count() }} database record(s)</p>
      </div>

      @if ($batches->isEmpty())
        <div class="empty-state">
          <h3>No process runs stored yet.</h3>
          <p>Complete a workflow first. The batch will appear here afterwards.</p>
        </div>
      @else
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Batch ID</th>
                <th>Template</th>
                <th>Process</th>
                <th>Operator</th>
                <th>Status</th>
                <th>Review</th>
                <th>Scans</th>
                <th>Logs</th>
                <th>Issues</th>
                <th>Completed</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($batches as $batch)
                <tr>
                  <td>
                    <strong>{{ $batch->batch_id }}</strong>
                    <small>Started: {{ optional($batch->started_at)->format('d.m.Y H:i') ?? 'n/a' }}</small>
                  </td>
                  <td>
                    {{ $batch->recipeTemplate?->title ?? 'No template' }}
                    <small>{{ $batch->recipeTemplate?->reference_code ?? 'No reference code' }}</small>
                  </td>
                  <td>{{ ucfirst($batch->process) }}</td>
                  <td>{{ $batch->operator_name ?? 'n/a' }}</td>
                  <td>
                    <span class="status status-{{ $batch->status }}">
                      {{ ucfirst(str_replace('_', ' ', $batch->status)) }}
                    </span>
                  </td>
                  <td>
                    @if ($batch->supervisorReview)
                      <span class="review-badge review-{{ $batch->supervisorReview->status }}">
                        {{ ucfirst($batch->supervisorReview->status) }}
                      </span>
                      <small>{{ $batch->supervisorReview->reviewer_name ?? 'Supervisor' }}</small>
                    @else
                      <span class="review-badge review-pending">Pending</span>
                    @endif
                  </td>
                  <td>{{ $batch->scans_count }}</td>
                  <td>{{ $batch->logs_count }}</td>
                  <td>{{ $batch->issues_count }}</td>
                  <td>{{ optional($batch->completed_at)->format('d.m.Y H:i') ?? 'Not completed' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    <section class="explanation-card">
      <h2>Why this matters</h2>
      <p>
        This page shows that the prototype is connected to a real backend database.
        QR scans, process logs, issues and batch completion data are persisted and can be reviewed after the workflow.
      </p>
    </section>
  </main>
</body>
</html>
