<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>AR Pharmacy Process Assistant</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body>
@include('partials.staff-bar')
<div class="page">

    <header class="header">
      <p class="eyebrow">Prototype</p>
      <h1>AR Pharmacy Process Assistant</h1>
      <p class="subtitle">
        Select a sample pharmacy preparation process to start the AR-like workflow guidance.
      </p>
    </header>

<section class="process-role-access-panel">
  @php
    $staff = session('staff');
    $role = $staff['role'] ?? 'guest';

    $access = [
      'workflow' => in_array($role, ['pta', 'pharmacist', 'supervisor', 'admin'], true),
      'audit' => in_array($role, ['pharmacist', 'supervisor', 'admin'], true),
      'admin' => $role === 'admin',
    ];
  @endphp

  @if($staff)
    <div class="process-role-main">
      <p class="process-role-eyebrow">Authenticated pharmacy staff session</p>
      <h2>Signed in as {{ $staff['role_label'] }}</h2>
      <p>
        {{ $staff['name'] }} · {{ $staff['department'] }} · Session-based role access active
      </p>
    </div>

    <div class="process-permission-list">
      @foreach($staff['permissions'] ?? [] as $permission)
        <span>{{ $permission }}</span>
      @endforeach
    </div>

    <div class="process-access-grid">
      <div class="{{ $access['workflow'] ? 'allowed' : 'locked' }}">
        <strong>Workflow execution</strong>
        <span>{{ $access['workflow'] ? 'Allowed' : 'Locked' }}</span>
      </div>

      <div class="{{ $access['audit'] ? 'allowed' : 'locked' }}">
        <strong>Audit evidence</strong>
        <span>{{ $access['audit'] ? 'Allowed' : 'Pharmacist / Supervisor only' }}</span>
      </div>

      <div class="{{ $access['admin'] ? 'allowed' : 'locked' }}">
        <strong>Content administration</strong>
        <span>{{ $access['admin'] ? 'Allowed' : 'Admin only' }}</span>
      </div>
    </div>
  @else
    <div class="process-role-main">
      <p class="process-role-eyebrow">Guest mode</p>
      <h2>No pharmacy staff session active</h2>
      <p>Please sign in to activate role-based access for PTA, Pharmacist, Supervisor or Admin.</p>
      <a href="/login" class="process-login-button">Open pharmacy staff login</a>
    </div>
  @endif
</section>


    
    <section class="demo-feature-strip">
      <span>Full-stack prototype</span>
      <span>Real QR validation</span>
      <span>QR sheet upload</span>
      <span>NRF-style templates</span>
      <span>Backend documentation</span>
      <span>Supervisor review</span>
    </section>

<main class="process-grid">

      <section class="process-card" onclick="selectProcess('ointment')">
        <div class="icon">01</div>
        <h2>Ointment Preparation</h2>
        <p>
          Step-by-step guidance for preparing a simple ointment, including material check,
          weighing, mixing and final labelling.
        </p>
        <button>Select Process</button>
      </section>

      <section class="process-card" onclick="selectProcess('capsules')">
        <div class="icon">02</div>
        <h2>Capsule Preparation</h2>
        <p>
          Guidance for a capsule preparation workflow, including dosage check,
          filling steps and final quality control.
        </p>
        <button>Select Process</button>
      </section>

      <section class="process-card" onclick="selectProcess('solution')">
        <div class="icon">03</div>
        <h2>Solution Preparation</h2>
        <p>
          Workflow support for preparing a liquid solution with checklist items,
          warnings and timer-based waiting steps.
        </p>
        <button>Select Process</button>
      </section>

    </main>


    <section class="batch-box">
      <h3>Batch information</h3>
      <p>
        Add optional batch data for the digital process report.
      </p>

      <div class="batch-grid">
        <label>
          Batch ID
          <input id="batchId" type="text" placeholder="e.g. OIN-2026-001" />
        </label>

        <label>
          Operator
          <input id="operatorName" type="text" placeholder="e.g. Pia" />
        </label>

        <label>
          Workstation
          <input id="workstation" type="text" placeholder="e.g. Pharmacy Lab 1" />
        </label>
      </div>
    </section>

    <div class="selected-box" id="selectedBox">
      <h3>Selected Process</h3>
      <p id="selectedText">No process selected yet.</p>
      <button id="startButton" disabled onclick="startProcess()">Start AR Workflow</button>
    </div>

  </div>

  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>