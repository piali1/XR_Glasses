console.log("workflow.js loaded");

const cameraView = document.getElementById("cameraView");
const cameraFallback = document.getElementById("cameraFallback");
const fallbackMessage = document.getElementById("fallbackMessage");

const processTitle = document.getElementById("processTitle");
const stepCounter = document.getElementById("stepCounter");
const stepStatus = document.getElementById("stepStatus");
const stepTitle = document.getElementById("stepTitle");
const stepDescription = document.getElementById("stepDescription");
const warningBox = document.getElementById("warningBox");
const timerDisplay = document.getElementById("timerDisplay");
const timerRequirement = document.getElementById("timerRequirement");
const timerButton = document.getElementById("timerButton");
const nextButton = document.getElementById("nextButton");
const checklistItems = document.getElementById("checklistItems");
const checklistCount = document.getElementById("checklistCount");
const progressFill = document.getElementById("progressFill");
const progressPercent = document.getElementById("progressPercent");
const riskBadge = document.getElementById("riskBadge");
const arHint = document.getElementById("arHint");
const arStatus = document.getElementById("arStatus");
const issueAlert = document.getElementById("issueAlert");
const issueText = document.getElementById("issueText");
const requirementNote = document.getElementById("requirementNote");
const processLogList = document.getElementById("processLog");
const logCount = document.getElementById("logCount");
const materialList = document.getElementById("materialList");
const materialStatus = document.getElementById("materialStatus");
const materialResult = document.getElementById("materialResult");

let currentStep = 0;
let timerInterval = null;
let remainingSeconds = 0;
let timerCompleted = true;
let materialVerified = false;
let activeIssue = null;

const completedSteps = new Set();
const processLog = [];
const reportedIssues = [];
const timerUsedSteps = new Set();
const materialVerifiedSteps = new Set();

const urlParams = new URLSearchParams(window.location.search);
const selectedProcess = urlParams.get("process") || "ointment";

const processNames = {
  ointment: "Ointment Preparation",
  capsules: "Capsule Preparation",
  solution: "Solution Preparation"
};

const processSteps = {
  ointment: [
    {
      title: "Check materials",
      description: "Prepare all containers, tools and ingredients before starting the ointment preparation.",
      warning: "Verify prescription, ingredient identity and clean workspace before continuing.",
      arHint: "Highlight the material tray and check whether all required items are present.",
      risk: "medium",
      timer: 0,
      materials: ["Prescription document", "Clean workspace", "Preparation tray"],
      checklist: ["Prescription checked", "Workspace cleaned", "All materials available"]
    },
    {
      title: "Weigh ingredients",
      description: "Weigh each ingredient carefully and compare the values with the process instruction.",
      warning: "Wrong quantities can change the concentration of the final preparation.",
      arHint: "Focus on the scale and confirm the displayed weight before continuing.",
      risk: "high",
      timer: 0,
      materials: ["Digital scale", "Active ingredient", "Weighing paper"],
      checklist: ["Scale calibrated", "Correct ingredient selected", "Weight value documented"]
    },
    {
      title: "Mix base substance",
      description: "Mix the base substance until it reaches an even and stable consistency.",
      warning: "Do not continue if the base is not homogeneous.",
      arHint: "The overlay would guide the stirring area and show the required mixing direction.",
      risk: "medium",
      timer: 30,
      materials: ["Ointment base", "Mixing bowl", "Spatula"],
      checklist: ["Base substance prepared", "Mixing tool selected", "Consistency visually checked"]
    },
    {
      title: "Add active ingredient",
      description: "Add the active ingredient slowly and continue mixing until evenly distributed.",
      warning: "Uneven distribution can affect dosage accuracy.",
      arHint: "The assistant would highlight the active ingredient and the mixing area.",
      risk: "high",
      timer: 45,
      materials: ["Verified active ingredient", "Prepared base", "Mixing tool"],
      checklist: ["Active ingredient verified", "Ingredient added gradually", "Distribution checked"]
    },
    {
      title: "Fill and label",
      description: "Fill the preparation into the correct container and apply the required label.",
      warning: "Final label and storage instructions must be checked before dispensing.",
      arHint: "The overlay would highlight the final container and label position.",
      risk: "medium",
      timer: 0,
      materials: ["Final container", "Printed label", "Storage instruction"],
      checklist: ["Correct container selected", "Label applied", "Expiry date and storage checked"]
    }
  ],

  capsules: [
    {
      title: "Check prescription",
      description: "Review the capsule preparation instruction and verify the required dosage.",
      warning: "Do not continue before the dosage has been verified.",
      arHint: "The prescription area would be highlighted for visual verification.",
      risk: "high",
      timer: 0,
      materials: ["Prescription document", "Dosage instruction", "Capsule record"],
      checklist: ["Dosage checked", "Capsule count confirmed", "Patient instruction reviewed"]
    },
    {
      title: "Prepare capsule shells",
      description: "Place the capsule shells into the preparation device.",
      warning: "Wrong capsule size can cause filling errors.",
      arHint: "The assistant would mark the capsule plate positions.",
      risk: "medium",
      timer: 0,
      materials: ["Capsule shells", "Capsule plate", "Filling tool"],
      checklist: ["Correct capsule size selected", "Capsule plate prepared", "Empty shells inspected"]
    },
    {
      title: "Fill capsules",
      description: "Fill the capsules carefully and distribute the powder evenly.",
      warning: "Avoid overfilling or uneven distribution.",
      arHint: "The overlay would show the filling direction and target area.",
      risk: "high",
      timer: 60,
      materials: ["Powder mixture", "Filling card", "Capsule plate"],
      checklist: ["Powder prepared", "Filling surface even", "No visible material loss"]
    },
    {
      title: "Close capsules",
      description: "Close all capsules securely and check for damaged capsules.",
      warning: "Damaged capsules must be removed before final control.",
      arHint: "The assistant would highlight capsules that need visual inspection.",
      risk: "medium",
      timer: 0,
      materials: ["Filled capsules", "Capsule top shells", "Inspection tray"],
      checklist: ["Capsules closed", "Damaged capsules removed", "Count verified"]
    },
    {
      title: "Final control",
      description: "Perform final quality control and prepare the capsules for dispensing.",
      warning: "Confirm all quality checks before finishing the process.",
      arHint: "The final inspection area would be highlighted.",
      risk: "medium",
      timer: 0,
      materials: ["Finished capsules", "Final container", "Quality checklist"],
      checklist: ["Final count checked", "Container labelled", "Quality control completed"]
    }
  ],

  solution: [
    {
      title: "Check materials",
      description: "Prepare all ingredients, tools and containers required for the solution.",
      warning: "Confirm that the correct solvent is available.",
      arHint: "The assistant would highlight solvent and container selection.",
      risk: "medium",
      timer: 0,
      materials: ["Solvent checked", "Container prepared", "Required tools available"],
      checklist: ["Solvent checked", "Container prepared", "Required tools available"]
    },
    {
      title: "Measure liquid",
      description: "Measure the required amount of liquid accurately.",
      warning: "Check the measuring unit carefully.",
      arHint: "The overlay would focus on the measuring cylinder scale.",
      risk: "high",
      timer: 0,
      materials: ["Measuring cylinder", "Solvent", "Solution container"],
      checklist: ["Correct unit checked", "Liquid level confirmed", "Measurement documented"]
    },
    {
      title: "Dissolve ingredient",
      description: "Add the ingredient and stir until it is fully dissolved.",
      warning: "Do not continue if visible particles remain.",
      arHint: "The assistant would highlight the stirring area and remaining particles.",
      risk: "high",
      timer: 60,
      materials: ["Measured solvent", "Ingredient powder", "Stirring rod"],
      checklist: ["Ingredient added", "Solution stirred", "No particles visible"]
    },
    {
      title: "Waiting time",
      description: "Allow the solution to rest for the defined waiting time.",
      warning: "Do not skip the waiting step.",
      arHint: "The countdown would stay visible in the AR overlay.",
      risk: "medium",
      timer: 30,
      materials: ["Closed container", "Timer", "Resting area"],
      checklist: ["Container closed", "Waiting time started", "Solution rested"]
    },
    {
      title: "Fill and label",
      description: "Fill the solution into the correct container and apply the label.",
      warning: "Check storage instructions before finishing.",
      arHint: "The final bottle and label area would be highlighted.",
      risk: "medium",
      timer: 0,
      materials: ["Correct bottle selected", "Label applied", "Storage instructions"],
      checklist: ["Correct bottle selected", "Label applied", "Storage instructions checked"]
    }
  ]
};

const steps = processSteps[selectedProcess] || processSteps.ointment;

async function startCamera() {
  if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    activateCameraFallback("Your browser does not provide camera access. Simulation mode is active.");
    return;
  }

  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true,
      audio: false
    });

    cameraView.srcObject = stream;
    arStatus.textContent = "AR camera active";
  } catch (error) {
    activateCameraFallback("Camera access was blocked or unavailable. Simulation mode is active.");
    console.error(error);
  }
}

function activateCameraFallback(message) {
  cameraFallback.classList.remove("hidden");
  fallbackMessage.textContent = message;
  arStatus.textContent = "Simulation mode active";
  cameraView.style.display = "none";
}

function updateStep() {
  const step = steps[currentStep];

  materialVerified = false;
  activeIssue = null;
  timerCompleted = step.timer === 0;

  processTitle.textContent = processNames[selectedProcess] || "Pharmacy Process";
  stepCounter.textContent = `Step ${currentStep + 1} of ${steps.length}`;
  stepStatus.textContent = currentStep === steps.length - 1 ? "Final step" : "In progress";
  stepTitle.textContent = step.title;
  stepDescription.textContent = step.description;
  warningBox.textContent = "Warning: " + step.warning;
  arHint.textContent = step.arHint;
  riskBadge.textContent = "Risk: " + step.risk;

  const progress = Math.round(((currentStep + 1) / steps.length) * 100);
  progressFill.style.width = progress + "%";
  progressPercent.textContent = progress + "%";

  issueAlert.classList.add("hidden");

  renderMaterials(step);
  renderChecklist(step);
  resetTimerForStep(step);
  renderProcessLog();
  updateNextButtonState();
}

function renderMaterials(step) {
  materialList.innerHTML = "";

  step.materials.forEach(material => {
    const item = document.createElement("li");
    item.textContent = material;
    materialList.appendChild(item);
  });

  materialStatus.textContent = "Not verified";
  materialResult.textContent = "Material scan required before continuing.";
  materialResult.className = "material-result";
}

function scanMaterial() {
  const step = steps[currentStep];

  materialVerified = true;
  activeIssue = null;
  issueAlert.classList.add("hidden");
  materialVerifiedSteps.add(currentStep);

  materialStatus.textContent = "Verified";
  materialResult.textContent =
    "Correct material detected for step " + (currentStep + 1) + ": " + step.title + ".";
  materialResult.className = "material-result success";

  updateNextButtonState();
}

function simulateWrongMaterial() {
  materialVerified = false;
  activeIssue = "Wrong material detected";

  reportedIssues.push({
    stepNumber: currentStep + 1,
    issue: "Wrong material detected",
    time: new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
  });

  issueText.textContent =
    "Wrong material detected in step " + (currentStep + 1) + ". Resolve the issue and scan the correct material before continuing.";
  issueAlert.classList.remove("hidden");

  materialStatus.textContent = "Failed";
  materialResult.textContent = "Wrong material detected. Process blocked.";
  materialResult.className = "material-result error";

  updateNextButtonState();
}

function renderChecklist(step) {
  checklistItems.innerHTML = "";

  step.checklist.forEach((item, index) => {
    const label = document.createElement("label");
    label.innerHTML = `
      <input type="checkbox" class="check-item" data-index="${index}" />
      ${item}
    `;
    checklistItems.appendChild(label);
  });

  document.querySelectorAll(".check-item").forEach(item => {
    item.addEventListener("change", updateNextButtonState);
  });

  updateChecklistCount();
}

function updateChecklistCount() {
  const items = Array.from(document.querySelectorAll(".check-item"));
  const checked = items.filter(item => item.checked).length;
  checklistCount.textContent = `${checked}/${items.length} confirmed`;
}

function resetTimerForStep(step) {
  resetTimer();

  remainingSeconds = step.timer;

  if (step.timer > 0) {
    timerRequirement.textContent = "Timer required before continuing";
    timerButton.disabled = false;
    timerButton.textContent = "Start Timer";
    timerDisplay.textContent = formatTime(step.timer);
  } else {
    timerRequirement.textContent = "No timer required";
    timerButton.disabled = true;
    timerButton.textContent = "No Timer";
    timerDisplay.textContent = "00:00";
  }
}

function startTimer() {
  const step = steps[currentStep];

  if (step.timer <= 0) {
    alert("No timer is required for this step.");
    return;
  }

  if (timerInterval) {
    return;
  }

  timerButton.disabled = true;
  timerButton.textContent = "Timer running";
  timerUsedSteps.add(currentStep);

  timerInterval = setInterval(() => {
    remainingSeconds--;
    timerDisplay.textContent = formatTime(remainingSeconds);

    if (remainingSeconds <= 0) {
      resetTimer();
      timerCompleted = true;
      timerButton.textContent = "Timer completed";
      updateNextButtonState();
      alert("Timer finished. You can continue after confirming the checklist.");
    }
  }, 1000);
}

function resetTimer() {
  if (timerInterval) {
    clearInterval(timerInterval);
    timerInterval = null;
  }
}

function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const rest = seconds % 60;

  return String(minutes).padStart(2, "0") + ":" + String(rest).padStart(2, "0");
}

function isChecklistComplete() {
  const items = Array.from(document.querySelectorAll(".check-item"));
  return items.length > 0 && items.every(item => item.checked);
}

function isStepReady() {
  return isChecklistComplete() && timerCompleted && materialVerified && !activeIssue;
}

function updateNextButtonState() {
  updateChecklistCount();

  const ready = isStepReady();

  nextButton.disabled = !ready;
  nextButton.textContent = currentStep === steps.length - 1 ? "Finish Workflow" : "Next Step";

  if (activeIssue) {
    requirementNote.textContent = "Resolve the reported issue before continuing.";
  } else if (!materialVerified) {
    requirementNote.textContent = "Scan and verify the required material before continuing.";
  } else if (!timerCompleted) {
    requirementNote.textContent = "Complete the required timer before continuing.";
  } else if (!isChecklistComplete()) {
    requirementNote.textContent = "Confirm all checklist items to continue.";
  } else {
    requirementNote.textContent = "All requirements completed. You can continue.";
  }
}

function reportIssue(issueType) {
  activeIssue = issueType;

  reportedIssues.push({
    stepNumber: currentStep + 1,
    issue: issueType,
    time: new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
  });

  issueText.textContent = `${issueType} reported. Resolve the issue before continuing.`;
  issueAlert.classList.remove("hidden");

  updateNextButtonState();
}

function resolveIssue() {
  activeIssue = null;
  issueAlert.classList.add("hidden");
  updateNextButtonState();
}

function nextStep() {
  if (!isStepReady()) {
    alert("Please complete all requirements before continuing.");
    return;
  }

  completeCurrentStep();

  if (currentStep < steps.length - 1) {
    currentStep++;
    updateStep();
  } else {
    showCompletionSummary();
  }
}

function previousStep() {
  if (currentStep > 0) {
    currentStep--;
    updateStep();
  }
}

function completeCurrentStep() {
  if (completedSteps.has(currentStep)) {
    return;
  }

  const step = steps[currentStep];

  completedSteps.add(currentStep);

  processLog.push({
    stepNumber: currentStep + 1,
    title: step.title,
    time: new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" }),
    timerUsed: timerUsedSteps.has(currentStep),
    materialVerified: materialVerifiedSteps.has(currentStep)
  });

  renderProcessLog();
}

function renderProcessLog() {
  if (processLog.length === 0) {
    processLogList.innerHTML = "<li>No steps completed yet.</li>";
  } else {
    processLogList.innerHTML = processLog.map(entry => `
      <li>
        <strong>Step ${entry.stepNumber}: ${entry.title}</strong>
        <span>${entry.time}${entry.timerUsed ? " · timer used" : ""}${entry.materialVerified ? " · material verified" : ""}</span>
      </li>
    `).join("");
  }

  logCount.textContent = `${processLog.length} entries`;
}

function showCompletionSummary() {
  document.getElementById("completedProcessName").textContent =
    processNames[selectedProcess] || "Pharmacy Process";

  document.getElementById("completedStepCount").textContent =
    `${completedSteps.size} of ${steps.length}`;

  document.getElementById("completedIssueCount").textContent =
    reportedIssues.length;

  document.getElementById("completedTimerCount").textContent =
    timerUsedSteps.size;

  const materialCount = document.getElementById("completedMaterialCount");
  if (materialCount) {
    materialCount.textContent = materialVerifiedSteps.size;
  }

  const completionLogList = document.getElementById("completionLogList");

  completionLogList.innerHTML = processLog.map(entry => `
    <li>
      Step ${entry.stepNumber}: ${entry.title}
      <span>${entry.time}${entry.timerUsed ? " · timer used" : ""}${entry.materialVerified ? " · material verified" : ""}</span>
    </li>
  `).join("");

  document.getElementById("completionOverlay").classList.remove("hidden");
}

function restartProcess() {
  window.location.href = "/";
}

window.scanMaterial = scanMaterial;
window.simulateWrongMaterial = simulateWrongMaterial;
window.reportIssue = reportIssue;
window.resolveIssue = resolveIssue;
window.startTimer = startTimer;
window.nextStep = nextStep;
window.previousStep = previousStep;
window.restartProcess = restartProcess;

startCamera();
updateStep();
