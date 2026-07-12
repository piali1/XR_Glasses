<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Set New Staff Password</title>
  <link rel="stylesheet" href="{{ asset('css/staff-auth.css') }}" />
</head>
<body class="login-body">

<main class="login-shell">
  <section class="login-info">
    <p class="login-eyebrow">XR Pharmacy Hub</p>
    <h1>Set New Password</h1>
    <p>
      This form simulates a production-style password reset flow for pharmacy staff.
    </p>
  </section>

  <section class="login-panel">
    <div class="login-card">

      @if($errors->any())
        <div class="auth-message error">{{ $errors->first() }}</div>
      @endif

      <h2>Update password</h2>

      <form method="POST" action="/password/reset" class="login-form">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}" />

        <label>
          Staff email
          <input name="email" type="email" value="{{ old('email', $email) }}" required />
        </label>

        <label>
          New password
          <input name="password" type="password" required />
        </label>

        <label>
          Confirm new password
          <input name="password_confirmation" type="password" required />
        </label>

        <button type="submit">Update password</button>
      </form>

      <div class="login-links">
        <a href="/login">Back to login</a>
      </div>
    </div>
  </section>
</main>

</body>
</html>
