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

  window.location.href = "/workflow?process=" + selectedProcess;


}