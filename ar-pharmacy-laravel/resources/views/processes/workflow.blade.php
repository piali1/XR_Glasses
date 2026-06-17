<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>AR Workflow Assistant</title>

  <link rel="stylesheet" href="{{ asset('css/workflow.css') }}" />
</head>
<body>

  <div class="workflow-app">

    <video id="cameraView" autoplay playsinline></video>

    <div class="workflow-overlay">

      <header class="workflow-header">
        <div>
          <p class="eyebrow">AR-like Workflow</p>
          <h1 id="processTitle">Pharmacy Process</h1>
        </div>

        <a href="/" class="exit-button">Exit</a>
      </header>

      <main class="step-card">

        <div class="step-meta">
          <span id="stepCounter">Step 1 of 5</span>
          <span id="stepStatus">In progress</span>
        </div>

        <h2 id="stepTitle">Loading step...</h2>

        <p id="stepDescription">
          The selected process step will be displayed here.
        </p>

        <div class="warning-box" id="warningBox">
          Warning information will be displayed here.
        </div>

        <div class="checklist-box">
          <h3>Checklist</h3>

          <label>
            <input type="checkbox" class="check-item" />
            Step understood
          </label>

          <label>
            <input type="checkbox" class="check-item" />
            Materials checked
          </label>

          <label>
            <input type="checkbox" class="check-item" />
            Safety confirmed
          </label>
        </div>

        <div class="timer-box">
          <div>
            <span>Timer</span>
            <strong id="timerDisplay">00:00</strong>
          </div>

          <button onclick="startTimer()">Start Timer</button>
        </div>

        <div class="navigation-buttons">
          <button onclick="previousStep()">Back</button>
          <button id="nextButton" onclick="nextStep()" disabled>Next Step</button>
        </div>

      </main>

      <div id="completionOverlay" class="completion-overlay hidden">
        <section class="completion-card">
          <p class="eyebrow">Workflow completed</p>
          <h2>Process completed successfully</h2>

          <p>
            The selected pharmacy process has been completed.
            All workflow steps were confirmed successfully.
          </p>

          <div class="completion-summary">
            <p><strong>Process:</strong> <span id="completedProcessName">Pharmacy Process</span></p>
            <p><strong>Status:</strong> All steps confirmed</p>
            <p><strong>Result:</strong> Preparation finished</p>
          </div>

          <button onclick="restartProcess()">Return to process selection</button>
        </section>
      </div>


    </div>

  </div>

  <script src="{{ asset('js/workflow.js') }}"></script>
</body>
</html>
