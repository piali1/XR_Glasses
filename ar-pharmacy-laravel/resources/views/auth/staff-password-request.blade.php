<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Staff Password</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body class="login-body">

<main class="login-shell">
  <section class="login-info">
    <p class="login-eyebrow">XR Pharmacy Hub</p>
    <h1>Password Reset</h1>
    <p>
      Request a password reset for a pharmacy staff account.
      In this prototype, the reset link is displayed directly instead of being sent by email.
    </p>

    <div class="login-system-card">
      <h2>Production-style recovery flow</h2>
      <div class="role-grid">
        <div>
          <strong>Reset token</strong>
          <span>A temporary token is generated and stored hashed.</span>
        </div>
        <div>
          <strong>New password</strong>
          <span>The updated password is saved with Laravel Hash.</span>
        </div>
      </div>
    </div>
  </section>

  <section class="login-panel">
    <div class="login-card">

      @if(session('status'))
        <div class="auth-message success">{{ session('status') }}</div>
      @endif

      @if($errors->any())
        <div class="auth-message error">{{ $errors->first() }}</div>
      @endif

      @if(session('reset_link'))
        <div class="signed-in-box">
          <strong>Demo reset link</strong>
          <a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a>
        </div>
      @endif

      <h2>Request reset link</h2>

      <form method="POST" action="/password/email" class="login-form">
        @csrf

        <label>
          Staff email
          <input name="email" type="email" value="{{ old('email', 'pharmacist.demo@example.test') }}" required />
        </label>

        <button type="submit">Generate reset link</button>
      </form>

      <div class="login-links">
        <a href="/login">Back to login</a>
      </div>
    </div>
  </section>
</main>

</body>
</html>
