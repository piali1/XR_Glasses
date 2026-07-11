<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Content Management</title>
  <link rel="stylesheet" href="{{ asset('css/admin-content.css') }}" />
</head>
<body>

<div class="admin-page">

  <header class="admin-header">
    <p class="eyebrow">Admin module</p>
    <h1>Content Management</h1>
    <p>
      Manage context-sensitive content items for the XR Pharmacy Hub.
      This demonstrates how SOPs, checklists, training content, QM evidence,
      compliance notes and role concepts can be maintained with metadata.
    </p>

    <div class="admin-actions">
      <a href="/hub">XR Hub</a>
      <a href="/">Process selection</a>
      <a href="/history">Process history</a>
      <a href="/audit/latest">Latest audit timeline</a>
    </div>
  </header>

  @if(session('status'))
    <div class="status-message">{{ session('status') }}</div>
  @endif

  <section class="admin-card">
    <h2>Create content item</h2>

    <form method="POST" action="/admin/content" class="content-form">
      @csrf

      <label>
        Type
        <select name="type" required>
          <option value="sop">SOP</option>
          <option value="checklist">Checklist</option>
          <option value="training">Training</option>
          <option value="workflow_template">Workflow template</option>
          <option value="qm">QM evidence</option>
          <option value="compliance">Compliance note</option>
          <option value="role_permission">Role / permission concept</option>
        </select>
      </label>

      <label>
        Title
        <input name="title" required placeholder="e.g. Cleaning SOP" />
      </label>

      <label>
        Version
        <input name="version" value="v1.0" />
      </label>

      <label>
        Valid from
        <input name="valid_from" type="date" value="2026-01-01" />
      </label>

      <label>
        Valid until
        <input name="valid_until" type="date" value="2026-12-31" />
      </label>

      <label>
        Area
        <input name="area" placeholder="e.g. Compounding / Rezeptur" />
      </label>

      <label>
        Responsible role
        <input name="responsible_role" placeholder="e.g. Pharmacist" />
      </label>

      <label>
        Approval status
        <select name="approval_status">
          <option value="draft">Draft</option>
          <option value="approved training template">Approved training template</option>
          <option value="concept only">Concept only</option>
          <option value="archived">Archived</option>
        </select>
      </label>

      <label>
        Process
        <select name="process">
          <option value="">Global</option>
          <option value="ointment">Ointment</option>
          <option value="capsules">Capsules</option>
          <option value="solution">Solution</option>
        </select>
      </label>

      <label>
        Step number
        <input name="step_number" type="number" min="1" placeholder="optional" />
      </label>

      <label>
        Display context
        <select name="display_context">
          <option value="workflow">Workflow</option>
          <option value="hub">Hub</option>
          <option value="both">Both</option>
        </select>
      </label>

      <label class="wide">
        Content
        <textarea name="content" required placeholder="Describe the SOP, training note, checklist or QM evidence."></textarea>
      </label>

      <button type="submit">Create content item</button>
    </form>
  </section>

  <section class="admin-card">
    <h2>Content library</h2>

    <div class="content-table">
      @foreach($contentItems as $item)
        <article class="content-row">
          <div>
            <span class="type-pill">{{ strtoupper(str_replace('_', ' ', $item->type)) }}</span>
            <h3>{{ $item->title }}</h3>
            <p>{{ $item->content }}</p>

            <div class="meta-line">
              <span>Version: {{ $item->version }}</span>
              <span>Area: {{ $item->area ?? 'General' }}</span>
              <span>Valid until: {{ optional($item->valid_until)->format('Y-m-d') ?? 'not specified' }}</span>
              <span>Responsible: {{ $item->responsible_role ?? 'not specified' }}</span>
              <span>Context: {{ $item->process ?? 'global' }}{{ $item->step_number ? ' · step ' . $item->step_number : '' }}</span>
            </div>
          </div>

          <form method="POST" action="/admin/content/{{ $item->id }}/status" class="status-form">
            @csrf
            <label>
              Status
              <select name="approval_status">
                <option value="draft" @selected($item->approval_status === 'draft')>Draft</option>
                <option value="approved training template" @selected($item->approval_status === 'approved training template')>Approved</option>
                <option value="concept only" @selected($item->approval_status === 'concept only')>Concept only</option>
                <option value="archived" @selected($item->approval_status === 'archived')>Archived</option>
              </select>
            </label>
            <button type="submit">Update status</button>
          </form>
        </article>
      @endforeach
    </div>
  </section>

</div>

</body>
</html>
