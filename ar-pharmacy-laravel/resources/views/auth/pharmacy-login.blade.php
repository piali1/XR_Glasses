<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pharmacy Staff Login</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body class="login-body">

<main class="login-shell">

  <section class="login-info">
    <p class="login-eyebrow">XR Pharmacy Hub</p>
    <h1>Pharmacy Staff Login</h1>
    <p>
      Sign in as pharmacy staff to access role-specific workflow, review,
      audit and content management functions.
    </p>

    <div class="login-system-card">
      <h2>Role-based access concept</h2>
      <div class="role-grid">
        <div>
          <strong>PTA</strong>
          <span>Workflow execution, QR scanning, checklists</span>
        </div>
        <div>
          <strong>Pharmacist</strong>
          <span>Quality review, audit evidence, release preparation</span>
        </div>
        <div>
          <strong>Supervisor</strong>
          <span>Supervisor review, approval, batch history</span>
        </div>
        <div>
          <strong>Admin</strong>
          <span>SOPs, checklists, training and QM content management</span>
        </div>
      </div>
    </div>

    <p class="login-note">
      Demo password for all users: <strong>pharmacy-demo</strong>
    </p>
  </section>

  <section class="login-panel">
    <div class="login-card">

      @if(session('status'))
        <div class="auth-message success">{{ session('status') }}</div>
      @endif

      @if(session('error'))
        <div class="auth-message error">{{ session('error') }}</div>
      @endif

      @if($errors->any())
        <div class="auth-message error">{{ $errors->first() }}</div>
      @endif

      @if(session('staff'))
        <div class="signed-in-box">
          <strong>Currently signed in</strong>
          <span>{{ session('staff.name') }} · {{ session('staff.role_label') }}</span>
          <form method="POST" action="/logout">
            @csrf
            <button type="submit">Sign out</button>
          </form>
        </div>
      @endif

      <h2>Sign in</h2>

      <form method="POST" action="/login" class="login-form">
        @csrf

        <label>
          Username
          <input id="username" name="username" value="{{ old('username') }}" required autocomplete="username" />
        </label>

        <label>
          Password
          <input id="password" name="password" type="password" required autocomplete="current-password" />
        </label>

        <button type="submit">Sign in to XR Hub</button>
      </form>

      <div class="demo-account-list">
        <h3>Demo accounts</h3>

        @foreach($accounts as $username => $account)
          <button
            type="button"
            class="demo-account"
            data-username="{{ $username }}"
            data-password="{{ $account['password'] }}"
          >
            <strong>{{ $account['role_label'] }}</strong>
            <span>{{ $username }}</span>
            <small>{{ $account['department'] }}</small>
          </button>
        @endforeach
      </div>

      <div class="login-links">
        <a href="/">Process selection</a>
        <a href="/hub">XR Hub</a>
      </div>
    </div>
  </section>

</main>

<script>
document.querySelectorAll(".demo-account").forEach(button => {
  button.addEventListener("click", () => {
    document.getElementById("username").value = button.dataset.username;
    document.getElementById("password").value = button.dataset.password;
  });
});
</script>

</body>
</html>
