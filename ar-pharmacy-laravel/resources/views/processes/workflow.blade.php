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

    <video id="cameraView" autoplay playsinline muted></video>

    <div id="cameraFallback" class="camera-fallback hidden">
      <div class="fallback-content">
        <p class="eyebrow">Simulation Mode</p>
        <h2>Camera preview unavailable</h2>
        <p id="fallbackMessage">
          The workflow continues in overlay simulation mode.
        </p>
      </div>
    </div>

    <div class="scan-layer"></div>

    <div class="workflow-overlay">

      <header class="workflow-header">
        <div>
          <p class="eyebrow">AR-like Workflow</p>
          <h1 id="processTitle">Pharmacy Process</h1>

          <div class="status-row">
            <span id="arStatus" class="status-pill">AR camera starting</span>
            <span class="status-pill">Traceability active</span>
            <span class="status-pill">Validation enabled</span>
          </div>
        </div>

        <a href="/" class="exit-button">Exit</a>
      </header>

      <div class="progress-area">
        <div class="progress-text">
          <span id="stepCounter">Step 1 of 5</span>
          <span id="progressPercent">0%</span>
        </div>
        <div class="progress-bar">
          <div id="progressFill"></div>
        </div>
      </div>

      <main class="workflow-layout">

        <section class="step-card">


          <div class="batch-meta-box">
            <p><strong>Batch:</strong> <span id="workflowBatchId">DEMO-BATCH</span></p>
            <p><strong>Operator:</strong> <span id="workflowOperator">Demo Operator</span></p>
            <p><strong>Workstation:</strong> <span id="workflowWorkstation">Demo Workstation</span></p>
          </div>

          <div class="step-meta">
            <span id="stepStatus">In progress</span>
            <span id="riskBadge" class="risk-badge">Risk: normal</span>
          </div>

          <h2 id="stepTitle">Loading step...</h2>

          <p id="stepDescription">
            The selected process step will be displayed here.
          </p>

          <div class="ar-hint-box">
            <strong>AR Hint</strong>
            <p id="arHint">The next visual instruction will appear here.</p>
          </div>

          <div class="warning-box" id="warningBox">
            Warning information will be displayed here.
          </div>


          <div class="material-box">
            <div class="section-title-row">
              <h3>Material verification</h3>
              <span id="materialStatus">Not verified</span>
            </div>

            <p class="material-intro">
              Required materials for this step:
            </p>

            <ul id="materialList"></ul>

            <div class="material-actions">
              <button onclick="scanMaterial()">Scan QR code</button>
              <button onclick="simulateWrongMaterial()">Simulate wrong material</button>
            </div>

            <div id="qrScannerBox" class="qr-scanner-box hidden">
              <p id="qrScannerStatus">Point the QR code into the camera view.</p>
              <button onclick="stopQrScanner()">Stop QR scan</button>
            </div>

            <div id="materialResult" class="material-result">
              Material scan required before continuing.
            </div>
          </div>

          <div class="checklist-box">
            <div class="section-title-row">
              <h3>Smart checklist</h3>
              <span id="checklistCount">0/0 confirmed</span>
            </div>

            <div id="checklistItems"></div>
          </div>

          <div class="timer-box" id="timerBox">
            <div>
              <span id="timerRequirement">Timer</span>
              <strong id="timerDisplay">00:00</strong>
            </div>

            <button id="timerButton" onclick="startTimer()">Start Timer</button>
          </div>

          <div id="issueAlert" class="issue-alert hidden">
            <strong>Process paused</strong>
            <p id="issueText">An issue has been reported.</p>
            <button onclick="resolveIssue()">Resolve issue</button>
          </div>

          <div class="issue-actions">
            <p>Report issue</p>
            <div>
              <button onclick="reportIssue('Material missing')">Material missing</button>
              <button onclick="reportIssue('Wrong quantity')">Wrong quantity</button>
              <button onclick="reportIssue('Contamination risk')">Contamination risk</button>
            </div>
          </div>

          <p id="requirementNote" class="requirement-note">
            Confirm all requirements to continue.
          </p>

          <div class="navigation-buttons">
            <button onclick="previousStep()">Back</button>
            <button id="nextButton" onclick="nextStep()" disabled>Next Step</button>
          </div>

        </section>

        <aside class="log-card">
          <div class="section-title-row">
            <h3>Live process log</h3>
            <span id="logCount">0 entries</span>
          </div>

          <ul id="processLog">
            <li>No steps completed yet.</li>
          </ul>
        </aside>

      </main>

      <div id="completionOverlay" class="completion-overlay hidden">
        <section class="completion-card">
          <p class="eyebrow">Workflow completed</p>
          <h2>Process completed successfully</h2>

          <p>
            The selected pharmacy process has been completed.
            All workflow steps were confirmed and documented.
          </p>

          <div class="completion-summary">
            <p><strong>Process:</strong> <span id="completedProcessName">Pharmacy Process</span></p>
            <p><strong>Batch ID:</strong> <span id="completedBatchId">DEMO-BATCH</span></p>
            <p><strong>Operator:</strong> <span id="completedOperator">Demo Operator</span></p>
            <p><strong>Workstation:</strong> <span id="completedWorkstation">Demo Workstation</span></p>
            <p><strong>Completed steps:</strong> <span id="completedStepCount">0</span></p>
            <p><strong>Reported issues:</strong> <span id="completedIssueCount">0</span></p>
            <p><strong>Timers used:</strong> <span id="completedTimerCount">0</span></p>
            <p><strong>Material checks:</strong> <span id="completedMaterialCount">0</span></p>
            <p><strong>Status:</strong> Validated and finished</p>
          </div>

          <div class="completion-log">
            <h3>Digital process documentation</h3>
            <ul id="completionLogList"></ul>
          </div>

          <button onclick="downloadProcessReport()">Download process report</button>
          <button onclick="restartProcess()">Return to process selection</button>
        </section>
      </div>

    </div>

  </div>

  <script src="{{ asset('js/workflow.js') }}"></script>
</body>
</html>
