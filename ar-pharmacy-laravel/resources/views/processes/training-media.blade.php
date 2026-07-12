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
        <div class="training-simulation-player" id="trainingPlayer">
          <div class="simulation-screen">
            <div class="simulation-topline">
              <span id="chapterBadge">Chapter 1 / 5</span>
              <span id="playerTime">00:00 / 00:20</span>
            </div>

            <div class="simulation-stage">
              <div class="xr-frame">
                <span class="xr-label">XR overlay</span>
                <h2 id="sceneTitle">Prescription and setup check</h2>
                <p id="sceneText">
                  Verify the prescription document and confirm that the preparation process matches the selected workflow.
                </p>

                <div class="scene-checks" id="sceneChecks">
                  <span>Prescription document visible</span>
                  <span>Workspace cleaned</span>
                  <span>Preparation tray ready</span>
                </div>
              </div>
            </div>

            <div class="simulation-caption" id="sceneCaption">
              Training starts with the prescription document and workspace setup before material verification begins.
            </div>

            <div class="simulation-controls">
              <button type="button" id="playTrainingButton">Play training</button>

              <div class="progress-track">
                <div id="trainingProgress" class="progress-fill"></div>
              </div>

              <button type="button" id="restartTrainingButton">Restart</button>
            </div>
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
          <strong>Interactive training player active</strong>
          <p>
            This demo uses an interactive training simulation. A real MP4 can still be connected by adding:
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

<script>
const chapters = [
  {
    title: "Prescription and setup check",
    text: "Verify the prescription document and confirm that the preparation process matches the selected workflow.",
    caption: "Training starts with the prescription document and workspace setup before material verification begins.",
    checks: ["Prescription document visible", "Workflow selected", "Batch data prepared"]
  },
  {
    title: "Workspace hygiene",
    text: "Clean the preparation area and remove unrelated materials before starting compounding.",
    caption: "A clean workspace reduces contamination risk and supports reproducible preparation quality.",
    checks: ["Surface cleaned", "Only required tools present", "Waste removed"]
  },
  {
    title: "Preparation tray check",
    text: "Prepare all containers, tools and ingredients before scanning QR-coded materials.",
    caption: "The XR overlay guides the user to verify that all required materials are ready.",
    checks: ["Container ready", "Spatula ready", "Material tray ready"]
  },
  {
    title: "QR material verification",
    text: "Scan each QR code and continue only when the backend confirms that the material belongs to this workflow step.",
    caption: "Wrong materials are blocked and documented as process issues.",
    checks: ["QR scan active", "Backend validation", "Traceability stored"]
  },
  {
    title: "Ready for workflow execution",
    text: "After setup and material checks are complete, the PTA can continue with the guided preparation workflow.",
    caption: "The pharmacist can later review the documented evidence before release.",
    checks: ["Checklist complete", "Materials verified", "Ready for next step"]
  }
];

let isPlaying = false;
let currentSecond = 0;
const duration = 20;
let timer = null;

const playButton = document.getElementById("playTrainingButton");
const restartButton = document.getElementById("restartTrainingButton");
const progress = document.getElementById("trainingProgress");
const time = document.getElementById("playerTime");
const title = document.getElementById("sceneTitle");
const text = document.getElementById("sceneText");
const caption = document.getElementById("sceneCaption");
const checks = document.getElementById("sceneChecks");
const badge = document.getElementById("chapterBadge");

function formatTime(seconds) {
  return "00:" + String(seconds).padStart(2, "0");
}

function renderScene() {
  const chapterIndex = Math.min(Math.floor(currentSecond / 4), chapters.length - 1);
  const chapter = chapters[chapterIndex];

  title.textContent = chapter.title;
  text.textContent = chapter.text;
  caption.textContent = chapter.caption;
  badge.textContent = `Chapter ${chapterIndex + 1} / ${chapters.length}`;
  time.textContent = `${formatTime(currentSecond)} / ${formatTime(duration)}`;
  progress.style.width = `${(currentSecond / duration) * 100}%`;

  checks.innerHTML = chapter.checks
    .map(item => `<span>${item}</span>`)
    .join("");
}

function playTraining() {
  if (isPlaying) {
    isPlaying = false;
    playButton.textContent = "Play training";
    clearInterval(timer);
    return;
  }

  isPlaying = true;
  playButton.textContent = "Pause";

  timer = setInterval(() => {
    currentSecond += 1;

    if (currentSecond > duration) {
      currentSecond = duration;
      clearInterval(timer);
      isPlaying = false;
      playButton.textContent = "Replay training";
    }

    renderScene();
  }, 1000);
}

function restartTraining() {
  clearInterval(timer);
  isPlaying = false;
  currentSecond = 0;
  playButton.textContent = "Play training";
  renderScene();
}

if (playButton && restartButton) {
  playButton.addEventListener("click", playTraining);
  restartButton.addEventListener("click", restartTraining);
  renderScene();
}
</script>

</body>
</html>
