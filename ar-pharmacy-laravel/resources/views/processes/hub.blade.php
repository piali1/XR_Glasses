<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>XR Pharmacy Hub</title>

  <link rel="stylesheet" href="{{ asset('css/hub.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body>
@include('partials.staff-bar')

  <div class="hub-page">

    <header class="hub-header">
      <p class="eyebrow">Concept module</p>
      <h1>XR Pharmacy Hub</h1>
      <p>
        A context-sensitive content and process hub for pharmacy workflows.
        This overview shows how SOPs, checklists, training content, workflow templates,
        quality documentation and role-based access can be structured for an XR-supported pharmacy environment.
      </p>

      <div class="hub-actions">
        <a href="/">Back to process selection</a>
        <a href="/workflow?process=ointment&batchId=OIN-DEMO-001&operator=Demo%20Operator&workstation=Lab%20Workstation%201">Open AR workflow</a>
        <a href="/history">Open process history</a>
      @if(session('staff.role') === 'admin')
      <a href="/admin/content">Admin content management</a>
    @else
      <span class="locked-action">Admin content locked · Admin only</span>
    @endif
      <a href="/audit/latest">Latest audit timeline</a>
      </div>
    </header>

  <section class="staff-role-access-panel">
    @php
      $staff = session('staff');
      $role = $staff['role'] ?? 'guest';

      $access = [
        'workflow' => in_array($role, ['pta', 'pharmacist', 'supervisor', 'admin'], true),
        'audit' => in_array($role, ['pharmacist', 'supervisor', 'admin'], true),
        'history' => in_array($role, ['supervisor', 'admin'], true),
        'admin' => $role === 'admin',
      ];
    @endphp

    @if($staff)
      <div class="role-access-main">
        <p class="role-eyebrow">Authenticated pharmacy staff</p>
        <h2>{{ $staff['name'] }}</h2>
        <div class="role-meta">
          <span class="role-badge {{ $role }}">{{ $staff['role_label'] }}</span>
          <span>{{ $staff['department'] }}</span>
          <span>Signed in at {{ $staff['signed_in_at'] ?? 'current session' }}</span>
        </div>
      </div>

      <div class="permission-box">
        <h3>Current permissions</h3>
        <ul>
          @foreach($staff['permissions'] ?? [] as $permission)
            <li>{{ $permission }}</li>
          @endforeach
        </ul>
      </div>

      <div class="access-matrix">
        <div class="{{ $access['workflow'] ? 'allowed' : 'locked' }}">
          <strong>Workflow</strong>
          <span>{{ $access['workflow'] ? 'Allowed' : 'Locked' }}</span>
        </div>

        <div class="{{ $access['audit'] ? 'allowed' : 'locked' }}">
          <strong>Audit timeline</strong>
          <span>{{ $access['audit'] ? 'Allowed' : 'Locked' }}</span>
        </div>

        <div class="{{ $access['history'] ? 'allowed' : 'locked' }}">
          <strong>Batch history</strong>
          <span>{{ $access['history'] ? 'Allowed' : 'Limited' }}</span>
        </div>

        <div class="{{ $access['admin'] ? 'allowed' : 'locked' }}">
          <strong>Content admin</strong>
          <span>{{ $access['admin'] ? 'Allowed' : 'Admin only' }}</span>
        </div>
      </div>
    @else
      <div class="role-access-main">
        <p class="role-eyebrow">Not signed in</p>
        <h2>Guest access</h2>
        <p>Please sign in as pharmacy staff to access role-specific functions.</p>
        <a href="/login" class="login-cta">Open staff login</a>
      </div>
    @endif
  </section>


    <main class="hub-grid">

      <section class="hub-card highlight">
        <span class="card-label">Workflow template</span>
        <h2>NRF-style Ointment Preparation</h2>
        <p>
          Structured process guidance for a pharmacy preparation workflow with steps,
          warnings, material validation, checklists and required process times.
        </p>

        <div class="metadata-grid">
          <div><strong>Reference code</strong><span>NRF-DEMO-OINTMENT-001</span></div>
          <div><strong>Version</strong><span>v1.0</span></div>
          <div><strong>Area</strong><span>Compounding / Rezeptur</span></div>
          <div><strong>Valid until</strong><span>2026-12-31</span></div>
          <div><strong>Responsible role</strong><span>Pharmacist / Supervisor</span></div>
          <div><strong>Status</strong><span>Approved training template</span></div>
        </div>
      </section>

      <section class="hub-card">
        <span class="card-label">SOP</span>
        <h2>SOP Library</h2>
        <p>
          Standard operating procedures can be connected to workflows and displayed
          at the exact point where they are needed.
        </p>

        <ul>
          <li>SOP-OINTMENT-001: Preparation workspace setup</li>
          <li>SOP-HYGIENE-001: Cleaning and contamination prevention</li>
          <li>SOP-LABEL-001: Final labelling and storage instruction</li>
        </ul>
      </section>

      <section class="hub-card">
        <span class="card-label">Checklist</span>
        <h2>Smart Checklists</h2>
        <p>
          Checklist items are attached to workflow steps and block progress until
          required confirmations are completed.
        </p>

        <ul>
          <li>Prescription checked</li>
          <li>Workspace cleaned</li>
          <li>Required materials verified</li>
          <li>Final label checked</li>
        </ul>
      </section>

      <section class="hub-card">
        <span class="card-label">Training</span>
        <h2>Training Content</h2>
        <p>
          Training content and short instructional videos can be linked to specific
          process steps for onboarding or refresher training.
        </p>

        <div class="placeholder-box">
          Video placeholder: Mixing technique for semisolid preparation
        </div>
      </section>

      <section class="hub-card">
        <span class="card-label">QM</span>
        <h2>Quality Management Evidence</h2>
        <p>
          The prototype stores process-relevant events to support traceability and
          quality documentation.
        </p>

        <ul>
          <li>Batch created</li>
          <li>Material scans stored</li>
          <li>Process steps logged</li>
          <li>Issues documented</li>
          <li>Supervisor review stored</li>
          <li>Digital process report available</li>
        </ul>
      </section>

      <section class="hub-card">
        <span class="card-label">Roles</span>
        <h2>Roles and Permissions Concept</h2>
        <p>
          The current prototype does not include login yet, but the hub concept
          defines the intended role structure.
        </p>

        <table>
          <thead>
            <tr>
              <th>Role</th>
              <th>Permissions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>PTA</td>
              <td>Run workflows, scan materials, report issues</td>
            </tr>
            <tr>
              <td>Pharmacist</td>
              <td>Review process data, approve preparation</td>
            </tr>
            <tr>
              <td>Supervisor</td>
              <td>Approve or reject documented batches</td>
            </tr>
            <tr>
              <td>Admin</td>
              <td>Maintain templates, SOPs and metadata</td>
            </tr>
          </tbody>
        </table>
      </section>

      <section class="hub-card wide">
        <span class="card-label">System architecture</span>
        <h2>Context-sensitive Information Flow</h2>
        <div class="architecture-flow">
          <div>XR glasses / browser UI</div>
          <span>→</span>
          <div>Workflow and content model</div>
          <span>→</span>
          <div>Laravel backend</div>
          <span>→</span>
          <div>SQLite process database</div>
          <span>→</span>
          <div>QM history and supervisor review</div>
        </div>
        <p>
          The workflow page is one executable module of the hub. The hub overview
          shows how additional pharmacy content types could be connected to the same
          context-sensitive architecture.
        </p>
      </section>

    </main>

  </div>

</body>
</html>
