<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Training Media - Preparation Setup</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/training-media.css') }}" />
</head>
<body>
@include('partials.staff-bar')

@php
  $localVideoPath = 'media/training/preparation-setup-demo.mp4';
  $hasLocalVideo = file_exists(public_path($localVideoPath));
@endphp

<main class="training-page">

  <header class="training-hero">
    <p class="training-eyebrow">XR Pharmacy Training Media</p>
    <h1>Preparation Setup Training</h1>
    <p>
      This training module demonstrates how video-based learning content can be linked
      directly to a workflow step in the XR Pharmacy Hub.
    </p>

    <div class="training-actions">
      <a href="/workflow?process=ointment&batchId=DEMO-BATCH&operator=Demo%20Operator&workstation=Demo%20Workstation">Back to workflow</a>
      <a href="/hub">XR Hub</a>
      <a href="/admin/content">Content management</a>
    </div>
  </header>

  <section class="video-layout">

    <article class="video-card">
      @if($hasLocalVideo)
        <video class="training-video-player" controls preload="metadata">
          <source src="{{ asset($localVideoPath) }}" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      @else
        <div class="video-placeholder">
          <div class="play-button">▶</div>
          <div>
            <strong>Training video placeholder</strong>
            <span>No local MP4 file connected yet</span>
          </div>
        </div>
      @endif

      <div class="video-meta">
        <span>Duration: 02:30 min</span>
        <span>Role: PTA / Pharmacist</span>
        <span>Status: Approved training template</span>
        <span>Version: v1.0</span>
      </div>

      @unless($hasLocalVideo)
        <div class="video-upload-note">
          <strong>How to connect a real video</strong>
          <p>
            Add an MP4 file named <code>preparation-setup-demo.mp4</code> to:
          </p>
          <pre>public/media/training/preparation-setup-demo.mp4</pre>
        </div>
      @endunless
    </article>

    <aside class="training-side-card">
      <h2>Why this appears here</h2>
      <p>
        This media item is connected to process <strong>ointment</strong> and
        workflow step <strong>1</strong>. The workflow loads it context-sensitively
        from the backend content model.
      </p>
    </aside>

  </section>

  <section class="training-grid">

    <article>
      <h2>Learning objectives</h2>
      <ul>
        <li>Verify prescription document before starting preparation.</li>
        <li>Clean and prepare the workspace.</li>
        <li>Prepare all required containers, tools and materials.</li>
        <li>Understand why material verification is required before continuing.</li>
      </ul>
    </article>

    <article>
      <h2>Training chapters</h2>
      <ol>
        <li>Prescription and documentation check</li>
        <li>Workspace hygiene and setup</li>
        <li>Preparation tray check</li>
        <li>QR-based material verification</li>
        <li>Common setup mistakes</li>
      </ol>
    </article>

    <article>
      <h2>QM relevance</h2>
      <p>
        The training content supports standardized preparation, reduces process
        variation and documents which version of training material is connected
        to the workflow step.
      </p>
    </article>

    <article>
      <h2>Content metadata</h2>
      <div class="metadata-list">
        <span>Type: Training</span>
        <span>Area: Compounding / Rezeptur</span>
        <span>Responsible role: PTA / Pharmacist</span>
        <span>Valid until: 2026-12-31</span>
      </div>
    </article>

  </section>

</main>

</body>
</html>
