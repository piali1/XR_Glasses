# Test Cases

This document lists the main functional test cases for the AR Pharmacy Process Assistant prototype.

## Test Case 1: Open Start Page

Goal:
Verify that the application opens correctly.

Steps:
1. Start the Laravel server.
2. Open the application in the browser.
3. Check that the process selection page is visible.

Expected Result:
The start page is displayed with process cards, batch input fields and the process history button.

## Test Case 2: Start Ointment Workflow

Goal:
Verify that a workflow can be started with batch metadata.

Steps:
1. Enter a batch ID.
2. Enter an operator name.
3. Enter a workstation.
4. Select Ointment Preparation.
5. Start the workflow.

Expected Result:
The AR-like workflow page opens and shows the first process step.

## Test Case 3: Correct QR Code Validation

Goal:
Verify that a required material can be validated successfully.

Steps:
1. Open the material verification area.
2. Scan or upload a QR code required for the current step.
3. Check the material status.

Expected Result:
The material is marked as verified and stored as a valid material scan.

## Test Case 4: Multi-Material Verification

Goal:
Verify that all materials required for a step must be scanned.

Steps:
1. Open a step with multiple required materials.
2. Scan only one required material.
3. Try to continue.
4. Scan the remaining required materials.

Expected Result:
The workflow only allows progress after all required materials have been verified.

## Test Case 5: Wrong QR Code Detection

Goal:
Verify that wrong materials are blocked.

Steps:
1. Scan or simulate a QR code that does not belong to the current step.
2. Check the warning message.
3. Try to continue.

Expected Result:
The workflow blocks progress and documents the issue.

## Test Case 6: QR Sheet Upload

Goal:
Verify that a QR sheet image can be uploaded.

Steps:
1. Upload an image containing multiple QR codes.
2. Check whether the system detects relevant QR codes for the current step.

Expected Result:
The system validates only the materials required for the current step and ignores irrelevant QR codes from other steps.

## Test Case 7: Checklist Validation

Goal:
Verify that checklist items are required before continuing.

Steps:
1. Leave checklist items unchecked.
2. Try to continue to the next step.
3. Check all required checklist items.
4. Continue again.

Expected Result:
The workflow blocks progress until the checklist is completed.

## Test Case 8: Required Process Time

Goal:
Verify that required process time blocks progress.

Steps:
1. Open a step with required process time.
2. Try to continue without completing the time.
3. Start and complete the required process time.
4. Continue to the next step.

Expected Result:
The workflow blocks progress until the required process time is completed.

## Test Case 9: Completion Summary

Goal:
Verify that the workflow creates a digital completion summary.

Steps:
1. Complete all workflow steps.
2. Check the completion page.

Expected Result:
The completion summary shows completed steps, material checks, issues and process documentation.

## Test Case 10: Digital Process Report

Goal:
Verify that a process report can be downloaded.

Steps:
1. Complete the workflow.
2. Click the report download button.
3. Open the downloaded report file.

Expected Result:
The report contains batch information, completed steps, material checks and documented issues.

## Test Case 11: Supervisor Quality Review

Goal:
Verify that a supervisor review can be submitted.

Steps:
1. Complete a workflow.
2. Enter reviewer name and comment.
3. Approve or reject the preparation.

Expected Result:
The supervisor review is stored and shown in the completion summary and process history.

## Test Case 12: Process History

Goal:
Verify that completed process data is stored in the backend.

Steps:
1. Complete a workflow.
2. Open the process history page.
3. Check the batch table.

Expected Result:
The history page shows batches, material scans, process logs, issues and supervisor reviews.

## Test Case 13: Backend Persistence

Goal:
Verify that the prototype is not only a frontend mockup.

Steps:
1. Complete a workflow.
2. Open the process history page.
3. Restart the browser page.

Expected Result:
Stored batch and process data remain available because they are stored in the Laravel backend database.

## Test Case 14: Fallback if Camera Does Not Work

Goal:
Verify that the workflow can still be demonstrated without live camera scanning.

Steps:
1. Do not use live camera scanning.
2. Upload a QR image or QR sheet instead.

Expected Result:
The QR validation still works through image upload.
