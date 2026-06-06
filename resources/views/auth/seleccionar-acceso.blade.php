<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Selecciona tu modo de acceso</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="{{ asset('build/images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<style>
:root { --naranja:#fc7b04; --naranja-dark:#b85500; }
* { box-sizing: border-box; }
html, body { margin: 0; padding: 0; height: 100%; font-family: 'Inter', sans-serif; }
body {
    min-height: 100vh;
    background: linear-gradient(135deg, #7a3000 0%, #b85500 48%, #fc7b04 100%);
    display: flex; align-items: center; justify-content: center;
    padding: 24px; position: relative; overflow-x: hidden;
}
body::before, body::after {
    content: ''; position: absolute; border-radius: 50%;
    background: rgba(255,255,255,.06); pointer-events: none;
}
body::before { width: 480px; height: 480px; top: -180px; right: -120px; }
body::after  { width: 360px; height: 360px; bottom: -140px; left: -100px; }

.shell {
    width: 100%; max-width: 880px;
    background: rgba(255,255,255,.97);
    border-radius: 24px;
    box-shadow: 0 30px 90px rgba(0,0,0,.30);
    padding: 40px 44px 36px;
    position: relative; z-index: 2;
    backdrop-filter: blur(8px);
}
.brand { display: flex; align-items: center; gap: 12px; justify-content: center; margin-bottom: 18px; }
.brand img { height: 44px; }
.brand .title { font-weight: 800; font-size: 1.05rem; color: var(--naranja-dark); letter-spacing: .02em; }

.greeting {
    text-align: center; margin-bottom: 8px;
}
.greeting h1 {
    margin: 0; font-weight: 800; font-size: 1.65rem; color: #1f2937;
    letter-spacing: -.01em;
}
.greeting h1 span { color: var(--naranja); }
.greeting p { margin: 6px 0 0; color: #6b7280; font-size: .95rem; }

.cards {
    margin-top: 28px;
    display: grid; gap: 18px;
    grid-template-columns: repeat(2, 1fr);
}
@media (max-width: 720px) { .cards { grid-template-columns: 1fr; } }

.card-acceso {
    position: relative;
    background: #fff;
    border: 2px solid #f1f3f5;
    border-radius: 18px;
    padding: 28px 24px 24px;
    text-decoration: none; color: inherit;
    transition: all .22s ease;
    cursor: pointer; display: flex; flex-direction: column;
    overflow: hidden;
}
.card-acceso::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(252,123,4,0) 0%, rgba(252,123,4,.06) 100%);
    opacity: 0; transition: opacity .22s;
}
.card-acceso:hover {
    border-color: var(--naranja); transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(252,123,4,.18);
}
.card-acceso:hover::before { opacity: 1; }

.card-icon {
    width: 64px; height: 64px; border-radius: 18px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.9rem; color: #fff;
    background: linear-gradient(135deg, var(--naranja), var(--naranja-dark));
    box-shadow: 0 8px 20px rgba(252,123,4,.30);
    margin-bottom: 16px; position: relative; z-index: 1;
}
.card-virtual .card-icon { background: linear-gradient(135deg, #2c5fb7, #1e3a8a); box-shadow: 0 8px 20px rgba(44,95,183,.30); }

.card-title { font-weight: 800; font-size: 1.15rem; color: #1f2937; margin: 0; position: relative; z-index: 1; }
.card-desc  { color: #6b7280; font-size: .85rem; margin: 6px 0 18px; line-height: 1.45; position: relative; z-index: 1; }

.card-features { list-style: none; padding: 0; margin: 0 0 18px; position: relative; z-index: 1; }
.card-features li {
    font-size: .8rem; color: #4b5563;
    padding: 4px 0; display: flex; align-items: center; gap: 8px;
}
.card-features li i { color: var(--naranja); font-size: .9rem; }
.card-virtual .card-features li i { color: #2c5fb7; }

.card-cta {
    margin-top: auto;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 16px; border-radius: 12px;
    background: var(--naranja); color: #fff;
    font-weight: 700; font-size: .88rem;
    border: none; cursor: pointer; transition: all .15s;
    justify-content: center; width: 100%;
    text-decoration: none;
    position: relative; z-index: 1;
}
.card-cta:hover { background: var(--naranja-dark); }
.card-virtual .card-cta { background: #2c5fb7; }
.card-virtual .card-cta:hover { background: #1e3a8a; }

.footer-row {
    margin-top: 28px;
    display: flex; justify-content: space-between; align-items: center;
    color: #6b7280; font-size: .8rem;
}
.btn-logout {
    background: transparent; border: 1.5px solid #e9ecef;
    color: #6b7280; padding: 7px 14px; border-radius: 10px;
    font-size: .82rem; font-weight: 600; text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: all .15s;
}
.btn-logout:hover { background: #fff4e6; border-color: var(--naranja); color: var(--naranja-dark); }

.alerta {
    margin-bottom: 16px; padding: 10px 14px; border-radius: 10px;
    background: #fee2e2; color: #991b1b; font-size: .85rem;
    display: flex; align-items: center; gap: 8px;
}
</style>
</head>
<body>
    <div class="shell">
        <div class="brand">
            <span class="title">INNOVA · Ciencia Virtual</span>
        </div>

        @if (session('error'))
            <div class="alerta"><i class="ri-error-warning-line"></i> {{ session('error') }}</div>
        @endif

        <div class="greeting">
            <h1>Hola, <span>{{ $user->name }}</span></h1>
            <p>Tu cuenta tiene <strong>dos modos de acceso</strong>. ¿Cómo deseas continuar?</p>
        </div>

        <div class="cards">
            <a href="{{ route('acceso.entrar', 'admin') }}" class="card-acceso card-admin">
                <div class="card-icon"><i class="ri-shield-user-line"></i></div>
                <h3 class="card-title">Panel Administrativo</h3>
                <p class="card-desc">Gestione el sistema, usuarios, ofertas, pagos y reportes institucionales.</p>
                <ul class="card-features">
                    <li><i class="ri-check-line"></i> Administración general</li>
                    <li><i class="ri-check-line"></i> Gestión financiera</li>
                    <li><i class="ri-check-line"></i> Reportes y estadísticas</li>
                </ul>
                <button type="button" class="card-cta">
                    Entrar como Administrador <i class="ri-arrow-right-line"></i>
                </button>
            </a>

            <a href="{{ route('acceso.entrar', 'virtual') }}" class="card-acceso card-virtual">
                <div class="card-icon"><i class="ri-presentation-line"></i></div>
                <h3 class="card-title">Portal Virtual</h3>
                <p class="card-desc">Accede a tus cursos, clases y actividades como docente o estudiante.</p>
                <ul class="card-features">
                    <li><i class="ri-check-line"></i> Cursos y módulos</li>
                    <li><i class="ri-check-line"></i> Actividades y entregas</li>
                    <li><i class="ri-check-line"></i> Calificaciones</li>
                </ul>
                <button type="button" class="card-cta">
                    Entrar al Portal Virtual <i class="ri-arrow-right-line"></i>
                </button>
            </a>
        </div>

        <div class="footer-row">
            <span><i class="ri-information-line"></i> Podrás cambiar de modo en cualquier momento.</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="ri-logout-box-r-line"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
