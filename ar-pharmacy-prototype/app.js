let selectedProcess = null;

const processNames = {
  ointment: "Ointment Preparation",
  capsules: "Capsule Preparation",
  solution: "Solution Preparation"
};

function selectProcess(processKey) {
  selectedProcess = processKey;

  const selectedText = document.getElementById("selectedText");
  const startButton = document.getElementById("startButton");

  selectedText.textContent = processNames[processKey];
  startButton.disabled = false;

  const cards = document.querySelectorAll(".process-card");
  cards.forEach(card => card.classList.remove("active"));

  event.currentTarget.classList.add("active");
}

function startProcess() {
  if (!selectedProcess) {
    alert("Please select a process first.");
    return;
  }

  alert("Starting AR workflow for: " + processNames[selectedProcess]);

  // Later we will connect this to the AR workflow page.
  // Example:
  // window.location.href = "workflow.html?process=" + selectedProcess;
}