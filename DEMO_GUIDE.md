# Demo Guide

This document describes the recommended demo flow for the AR Pharmacy Process Assistant prototype.

## Demo Goal

The goal of the demo is to show how an AR-like workflow assistant can support pharmacy preparation processes by combining:

- step-by-step process guidance
- QR-based material validation
- checklist validation
- required process time validation
- backend documentation
- digital process history
- supervisor quality review

## Recommended Demo Setup

Use the following example values on the start page:

- Batch ID: OIN-DEMO-001
- Operator: Demo Operator
- Workstation: Lab Workstation 1
- Process: Ointment Preparation

## Demo Flow

### 1. Start Page

Open the application and show the process selection page.

Explain:

The user starts by selecting a pharmacy preparation workflow and entering batch information. This connects the manual preparation process with digital backend documentation.

### 2. Select Ointment Preparation

Choose the ointment preparation process.

Explain:

The ointment workflow is based on a structured NRF-style demo template. The prototype does not use protected NRF content, but shows how preparation instructions can be represented as structured backend data.

### 3. AR-like Workflow View

Show the camera-based workflow page.

Explain:

The screen simulates an XR glasses view. In a real XR setup, the user would see instructions, warnings, timers and validation results directly in the field of view while keeping both hands free.

### 4. Material Verification

Open the material verification area.

Show either:

- live QR scanning with the camera
- QR image upload
- QR sheet upload

Explain:

The backend checks whether the scanned QR code belongs to the material required for the current process step.

### 5. Correct QR Code

Scan or upload a correct QR code.

Expected result:

- the material is marked as verified
- the step can continue once all required materials are verified

Explain:

This prevents the user from continuing with missing or unverified materials.

### 6. Wrong QR Code

Use the wrong material simulation or scan a wrong QR code.

Expected result:

- the process is blocked
- an issue is documented
- the user cannot continue as if everything was correct

Explain:

This is the key safety feature. Wrong materials are not only shown in the UI, but also documented in the backend.

### 7. Checklist Validation

Complete the checklist items.

Explain:

The user must confirm required preparation actions before continuing. This supports process compliance and reduces forgotten steps.

### 8. Required Process Time

Start and complete the required process time where shown.

Explain:

Some steps require a defined process time. The prototype blocks progress until the required time has been completed.

### 9. Complete Workflow

Finish all workflow steps.

Expected result:

- digital completion summary appears
- completed steps are shown
- material checks are shown
- issues are shown
- process documentation is available

### 10. Download Process Report

Download the digital process report.

Explain:

The report summarizes the batch, process steps, material checks, issues and completion information.

### 11. Supervisor Quality Review

Submit a supervisor quality review.

Explain:

A supervisor can approve or reject the documented preparation. The review is stored in the backend database.

### 12. Process History

Open the process history page.

Explain:

The backend stores batches, material scans, process logs, issues and supervisor reviews. This shows that the prototype is not only a frontend mockup, but a full-stack process documentation system.

## Key Talking Points

- The prototype supports the actual preparation workflow, not only information lookup.
- QR validation helps prevent wrong materials from being used.
- Checklist and process time validation block incomplete workflow steps.
- The Laravel backend stores process-relevant data.
- The XR value is hands-free guidance during manual work.
- The current implementation is a proof of concept and not a production medical system.

## Limitations to Mention

- No real XR glasses deployment yet
- No real licensed NRF content included
- Demo data only
- No login or role-based access control yet
- No production-grade audit trail yet
- No automated test suite yet
- Browser QR support depends on camera and BarcodeDetector support

## Fallback During Demo

If live camera scanning does not work during the demo, use QR image upload or QR sheet upload.

This still demonstrates the same backend validation logic.
