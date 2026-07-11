@php
  $staff = session('staff');
  $role = $staff['role'] ?? 'guest';
  $canAudit = in_array($role, ['pharmacist', 'supervisor', 'admin'], true);
@endphp

<div class="staff-bar">
  <div class="staff-brand">
    <span class="staff-logo">XR</span>
    <div>
      <strong>Pharmacy Hub</strong>
      <small>Role-based demo access</small>
    </div>
  </div>

  @if($staff)
    <div class="staff-current">
      <span class="staff-role {{ $role }}">{{ $staff['role_label'] }}</span>
      <div>
        <strong>{{ $staff['name'] }}</strong>
        <small>{{ $staff['department'] }}</small>
      </div>
    </div>

    <nav class="staff-nav">
      <a href="/">Processes</a>
      <a href="/hub">Hub</a>
      <a href="/history">History</a>

      @if($canAudit)
        <a href="/audit/latest">Audit</a>
      @endif

      @if($role === 'admin')
        <a href="/admin/content">Admin</a>
      @endif
    </nav>

    <form method="POST" action="/logout" class="staff-logout">
      @csrf
      <button type="submit">Sign out</button>
    </form>
  @else
    <div class="staff-guest">
      <span>Not signed in</span>
      <a href="/login">Staff login</a>
    </div>
  @endif
</div>
