<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Bienvenido a PROCAFES</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* estilos inline-friendly para correos */
    body{margin:0;background:#f4f6f8;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial,sans-serif}
    .wrap{max-width:640px;margin:0 auto;padding:24px}
    .card{background:#ffffff;border-radius:10px;margin-top:24px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,.05)}
    .brand{display:block;text-align:center;margin:24px auto 0}
    .h1{font-size:20px;line-height:1.3;margin:0 0 12px;color:#111827;font-weight:700;text-align:center}
    .muted{color:#6b7280;font-size:14px;line-height:1.6;text-align:center;margin:0 0 20px}
    .cta{display:inline-block;background:#3E350E;color:#fff;text-decoration:none;padding:12px 18px;border-radius:8px;font-weight:600}
    .cta-wrap{text-align:center;margin:16px 0 6px}
    .small{font-size:12px;color:#9CA3AF;margin-top:24px;text-align:center}
    .btn-alt{display:block;word-break:break-all;font-size:12px;color:#6b7280;text-decoration:none;margin-top:14px;text-align:center}
    .divider{height:1px;background:#e5e7eb;margin:20px 0}
    .footer{font-size:12px;color:#9CA3AF;text-align:center;margin-top:18px}
  </style>
</head>
<body>
  <div class="wrap">
    <img class="brand" src="{{ asset('images/logo.png') }}" alt="PROCAFES" height="42" onerror="this.style.display='none'">

    <div class="card">
      <h1 class="h1">¡Bienvenido a PROCAFES, {{ $user->name }}! 🎉</h1>
      <p class="muted">
        Gracias por registrarte en nuestra tienda. Para activar tu cuenta y mantenerla segura,
        por favor confirma tu correo electrónico.
      </p>

      <div class="cta-wrap">
        <a class="cta" href="{{ $url }}" target="_blank" rel="noopener">
          Confirmar cuenta
        </a>
      </div>

      <p class="muted" style="margin-top:16px">
        Si el botón no funciona, copia y pega este enlace en tu navegador:
      </p>
      <a class="btn-alt" href="{{ $url }}" target="_blank" rel="noopener">{{ $url }}</a>

      <div class="divider"></div>

      <p class="muted" style="margin:0">
        Si no creaste esta cuenta, puedes ignorar este mensaje.
      </p>
    </div>

    <p class="footer">© {{ date('Y') }} PROCAFES. Todos los derechos reservados.</p>
  </div>
</body>
</html>
