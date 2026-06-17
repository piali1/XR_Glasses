const cameraView = document.getElementById("cameraView");

const processTitle = document.getElementById("processTitle");
const stepCounter = document.getElementById("stepCounter");
const stepStatus = document.getElementById("stepStatus");
const stepTitle = document.getElementById("stepTitle");
const stepDescription = document.getElementById("stepDescription");
const warningBox = document.getElementById("warningBox");
const timerDisplay = document.getElementById("timerDisplay");

let currentStep = 0;
let timerInterval = null;
let remainingSeconds = 0;

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
      description: "Make sure all required materials, containers and ingredients are available before starting.",
      warning: "Verify the prescription and clean the workspace before continuing.",
      timer: 0
    },
    {
      title: "Weigh ingredients",
      description: "Weigh the required ingredients carefully and compare the values with the process instruction.",
      warning: "Double-check all quantities before moving to the next step.",
      timer: 0
    },
    {
      title: "Mix base substance",
      description: "Mix the base substance until it reaches an even consistency.",
      warning: "Do not continue if the base is not prepared correctly.",
      timer: 30
    },
    {
      title: "Add active ingredient",
      description: "Add the active ingredient slowly and continue mixing.",
      warning: "Ensure the active ingredient is evenly distributed.",
      timer: 45
    },
    {
      title: "Fill and label",
      description: "Fill the preparation into the correct container and apply the required label.",
      warning: "Check the label before finishing the process.",
      timer: 0
    }
  ],

  capsules: [
    {
      title: "Check prescription",
      description: "Review the capsule preparation instructions and required dosage.",
      warning: "Do not continue before the dosage has been verified.",
      timer: 0
    },
    {
      title: "Prepare capsule shells",
      description: "Place the capsule shells into the preparation device.",
      warning: "Check that the capsule size is correct.",
      timer: 0
    },
    {
      title: "Fill capsules",
      description: "Fill the capsules carefully and evenly.",
      warning: "Avoid overfilling or uneven distribution.",
      timer: 60
    },
    {
      title: "Close capsules",
      description: "Close all capsules securely and check for damaged capsules.",
      warning: "Remove damaged capsules before final control.",
      timer: 0
    },
    {
      title: "Final control",
      description: "Perform final quality control and prepare the capsules for dispensing.",
      warning: "Confirm all checklist points before completing the process.",
      timer: 0
    }
  ],

  solution: [
    {
      title: "Check materials",
      description: "Prepare all required ingredients, tools and containers for the solution.",
      warning: "Confirm that the correct solvent is available.",
      timer: 0
    },
    {
      title: "Measure liquid",
      description: "Measure the required amount of liquid accurately.",
      warning: "Check the measuring unit carefully.",
      timer: 0
    },
    {
      title: "Dissolve ingredient",
      description: "Add the ingredient and stir until it is fully dissolved.",
      warning: "Do not continue if visible particles remain.",
      timer: 60
    },
    {
      title: "Waiting time",
      description: "Allow the solution to rest for the defined waiting time.",
      warning: "Do not skip the waiting step.",
      timer: 30
    },
    {
      title: "Fill and label",
      description: "Fill the solution into the correct container and apply the label.",
      warning: "Check storage instructions before finishing.",
      timer: 0
    }
  ]
};

const steps = processSteps[selectedProcess] || processSteps.ointment;

async function startCamera() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true,
      audio: false
    });

    cameraView.srcObject = stream;
  } catch (error) {
    alert("Camera access could not be started. Please allow camera permission in your browser.");
    console.error(error);
  }
}

function updateStep() {
  const step = steps[currentStep];

  processTitle.textContent = processNames[selectedProcess] || "Pharmacy Process";
  stepCounter.textContent = `Step ${currentStep + 1} of ${steps.length}`;
  stepStatus.textContent = currentStep === steps.length - 1 ? "Final step" : "In progress";
  stepTitle.textContent = step.title;
  stepDescription.textContent = step.description;
  warningBox.textContent = "Warning: " + step.warning;

  resetTimer();

  if (step.timer > 0) {
    remainingSeconds = step.timer;
    updateTimerDisplay();
  } else {
    remainingSeconds = 0;
    timerDisplay.textContent = "00:00";
  }
}

function nextStep() {
  if (currentStep < steps.length - 1) {
    currentStep++;
    updateStep();
  } else {
    alert("Process completed successfully.");
  }
}

function previousStep() {
  if (currentStep > 0) {
    currentStep--;
    updateStep();
  }
}

function startTimer() {
  const step = steps[currentStep];

  if (step.timer <= 0) {
    alert("No timer is required for this step.");
    return;
  }

  resetTimer();
  remainingSeconds = step.timer;
  updateTimerDisplay();

  timerInterval = setInterval(() => {
    remainingSeconds--;
    updateTimerDisplay();

    if (remainingSeconds <= 0) {
      resetTimer();
      alert("Timer finished. You can continue with the next step.");
    }
  }, 1000);
}

function resetTimer() {
  if (timerInterval) {
    clearInterval(timerInterval);
    timerInterval = null;
  }
}

function updateTimerDisplay() {
  const minutes = Math.floor(remainingSeconds / 60);
  const seconds = remainingSeconds % 60;

  timerDisplay.textContent =
    String(minutes).padStart(2, "0") +
    ":" +
    String(seconds).padStart(2, "0");
}

startCamera();
updateStep();