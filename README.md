# AR Pharmacy Process Assistant

A full-stack prototype for an AR-like pharmacy workflow assistant. The application demonstrates how XR glasses could support pharmacy staff during manual preparation processes by showing step-by-step guidance, warnings, checklists, timers and QR-based material validation directly in the user's field of view.

## Use Case

The project focuses on pharmacy preparation and compounding workflows. Instead of only displaying medication information, the prototype supports the actual manual workflow.

It helps with:

- selecting a preparation process
- verifying required materials
- following guided process steps
- completing checklist items
- respecting required process times
- documenting issues
- generating a digital process report
- reviewing completed batches afterwards

## XR Value

The XR value is that workflow guidance can be shown while the user keeps their hands free.

In a real XR glasses setup, pharmacy staff could see process instructions, warnings, QR validation results and timers directly in the field of view while continuing the manual preparation task.

This prototype simulates that XR experience through a browser-based AR-like camera view.

## Main Features

- AR-like workflow page with camera overlay
- Process selection
- Batch ID, operator and workstation input
- NRF-style recipe template structure
- Step-by-step workflow guidance
- Dynamic checklist validation
- Required process time / timer validation
- Real QR-code scanning with the PC camera
- QR image and QR sheet upload
- Multi-material QR verification per workflow step
- Wrong material detection and process blocking
- Issue reporting
- Live process log
- Digital completion summary
- Downloadable process report
- Process history page
- Supervisor quality review
- Laravel backend with SQLite database

## Technology Stack

- Laravel
- Blade templates
- JavaScript
- CSS
- SQLite
- Browser camera API
- BarcodeDetector API for QR-code scanning

## Backend and Database

The prototype uses a Laravel backend and SQLite database. It stores structured process and validation data instead of only keeping everything in the frontend.

Main database concepts:

- recipe_templates: stores NRF-style preparation templates
- recipe_steps: stores workflow steps, warnings, timers and checklist items
- materials: stores expected materials and QR material codes
- batches: stores started and completed process runs
- material_scans: stores scanned QR codes and validation results
- process_logs: stores completed workflow steps
- process_issues: stores reported issues
- supervisor_reviews: stores approval or rejection of completed batches

## QR-Code Validation

Each required material has a QR material code, for example:

- MAT-PRESCRIPTION-DOCUMENT
- MAT-CLEAN-WORKSPACE
- MAT-PREPARATION-TRAY

The user can scan QR codes in two ways:

1. Live QR-code scan through the PC camera
2. QR image or QR sheet upload from the computer

When a QR code is detected, the application sends the code to the Laravel backend. The backend validates the code against the material database and checks whether the material belongs to the current workflow step.

If the material is correct, it is marked as scanned. If the material is wrong, the process is blocked and an issue is documented.

## NRF-style Template Structure

The prototype does not copy protected NRF content.

Instead, it demonstrates how NRF-based or training-based preparation instructions could be represented as structured backend templates. The current data is demo data and is used only to show the technical and process-oriented concept.

Example:

- Reference source: NRF-compatible preparation template structure
- Reference code: NRF-DEMO-OINTMENT-001
- Dosage form: Semisolid preparation / ointment

## How to Run the Project

Go to the Laravel project folder:

    cd ar-pharmacy-laravel

Install dependencies if needed:

    composer install

Prepare the environment if .env does not exist yet:

    cp .env.example .env
    php artisan key:generate

Prepare the SQLite database:

    touch database/database.sqlite
    php artisan config:clear
    php artisan migrate --seed

Start the Laravel development server:

    php artisan serve

Open the application in the browser:

    http://127.0.0.1:8000

## Demo Flow

Recommended demo flow:

1. Explain the problem: media breaks in manual pharmacy preparation workflows.
2. Open the process selection page.
3. Enter batch information.
4. Select the ointment preparation workflow.
5. Start the AR-like workflow.
6. Scan or upload QR codes for required materials.
7. Show that wrong QR codes block the process.
8. Complete checklist items.
9. Start the required process time for time-critical steps.
10. Complete all workflow steps.
11. Show the digital completion summary.
12. Download the process report.
13. Submit a supervisor quality review.
14. Open the process history page.
15. Show that batches, scans, logs, issues and reviews are stored in the backend database.

## Limitations and Future Work

This is a proof-of-concept prototype, not a production-ready medical or pharmacy system.

Current limitations:

- No real XR glasses deployment
- No real licensed NRF content included
- No user login
- No role-based access control
- No admin interface for editing recipe templates
- No production-grade audit trail
- No automated test suite
- No deployment configuration
- Browser support for QR scanning depends on the BarcodeDetector API

Possible future work:

- Integration with real XR hardware
- Licensed NRF or training-data integration
- User roles such as PTA, pharmacist, supervisor and admin
- Admin interface for recipe templates and QR material codes
- Stronger audit logging
- Automated tests
- Production deployment
- Improved security and validation
