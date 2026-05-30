<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conectando a Moodle…</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #391b04, #743c04);
            color: #fff;
        }
        .wrap { text-align: center; }
        .spinner {
            width: 48px; height: 48px;
            border: 4px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin: 0 auto 1.25rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        p { font-size: .95rem; opacity: .85; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="spinner"></div>
        <p>Iniciando sesión en Moodle…</p>
    </div>

    <form id="mf" action="{{ $action }}" method="POST" style="display:none;">
        <input type="hidden" name="username"   value="{{ $username }}">
        <input type="hidden" name="password"   value="{{ $password }}">
        <input type="hidden" name="logintoken" value="{{ $logintoken }}">
        <input type="hidden" name="wantsurl"   value="{{ $wantsurl }}">
        <input type="hidden" name="anchor"     value="">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('mf').submit();
        });
    </script>
</body>
</html>
