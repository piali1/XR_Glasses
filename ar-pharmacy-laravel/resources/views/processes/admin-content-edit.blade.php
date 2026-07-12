<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Content Item</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/admin-content.css') }}" />
</head>
<body>
@include('partials.staff-bar')

<div class="admin-page">

  <header class="admin-header">
    <p class="eyebrow">Admin module</p>
    <h1>Edit Content Item</h1>
    <p>
      Edit metadata and content for a context-sensitive XR Pharmacy Hub item.
      This simulates controlled SOP, checklist, training and QM content maintenance.
    </p>

    <div class="admin-actions">
      <a href="/admin/content">Back to content management</a>
      <a href="/hub">XR Hub</a>
    </div>
  </header>

  @if($errors->any())
    <div class="status-message error-message">{{ $errors->first() }}</div>
  @endif

  <section class="admin-card">
    <h2>{{ $contentItem->title }}</h2>

    <form method="POST" action="/admin/content/{{ $contentItem->id }}/update" class="content-form">
      @csrf

      <label>
        Type
        <select name="type" required>
          <option value="sop" @selected($contentItem->type === 'sop')>SOP</option>
          <option value="checklist" @selected($contentItem->type === 'checklist')>Checklist</option>
          <option value="training" @selected($contentItem->type === 'training')>Training</option>
          <option value="workflow_template" @selected($contentItem->type === 'workflow_template')>Workflow template</option>
          <option value="qm" @selected($contentItem->type === 'qm')>QM evidence</option>
          <option value="compliance" @selected($contentItem->type === 'compliance')>Compliance note</option>
          <option value="role_permission" @selected($contentItem->type === 'role_permission')>Role / permission concept</option>
        </select>
      </label>

      <label>
        Title
        <input name="title" required value="{{ old('title', $contentItem->title) }}" />
      </label>

      <label>
        Version
        <input name="version" value="{{ old('version', $contentItem->version) }}" />
      </label>

      <label>
        Valid from
        <input name="valid_from" type="date" value="{{ old('valid_from', optional($contentItem->valid_from)->format('Y-m-d')) }}" />
      </label>

      <label>
        Valid until
        <input name="valid_until" type="date" value="{{ old('valid_until', optional($contentItem->valid_until)->format('Y-m-d')) }}" />
      </label>

      <label>
        Area
        <input name="area" value="{{ old('area', $contentItem->area) }}" />
      </label>

      <label>
        Responsible role
        <input name="responsible_role" value="{{ old('responsible_role', $contentItem->responsible_role) }}" />
      </label>

      <label>
        Approval status
        <select name="approval_status">
          <option value="draft" @selected($contentItem->approval_status === 'draft')>Draft</option>
          <option value="approved training template" @selected($contentItem->approval_status === 'approved training template')>Approved training template</option>
          <option value="concept only" @selected($contentItem->approval_status === 'concept only')>Concept only</option>
          <option value="archived" @selected($contentItem->approval_status === 'archived')>Archived</option>
        </select>
      </label>

      <label>
        Process
        <select name="process">
          <option value="" @selected($contentItem->process === null)>Global</option>
          <option value="ointment" @selected($contentItem->process === 'ointment')>Ointment</option>
          <option value="capsules" @selected($contentItem->process === 'capsules')>Capsules</option>
          <option value="solution" @selected($contentItem->process === 'solution')>Solution</option>
        </select>
      </label>

      <label>
        Step number
        <input name="step_number" type="number" min="1" value="{{ old('step_number', $contentItem->step_number) }}" />
      </label>

      <label>
        Display context
        <select name="display_context">
          <option value="workflow" @selected($contentItem->display_context === 'workflow')>Workflow</option>
          <option value="hub" @selected($contentItem->display_context === 'hub')>Hub</option>
          <option value="both" @selected($contentItem->display_context === 'both')>Both</option>
        </select>
      </label>

      <label class="wide">
        Content
        <textarea name="content" required>{{ old('content', $contentItem->content) }}</textarea>
      </label>

      <button type="submit">Save changes</button>
    </form>

    <div class="edit-danger-zone">
      <form method="POST" action="/admin/content/{{ $contentItem->id }}/duplicate">
        @csrf
        <button type="submit" class="duplicate-button">Duplicate as new draft version</button>
      </form>

      <form method="POST" action="/admin/content/{{ $contentItem->id }}/archive">
        @csrf
        <button type="submit" class="archive-button">Archive content item</button>
      </form>
    </div>
  </section>

</div>

</body>
</html>
