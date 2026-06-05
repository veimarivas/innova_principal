<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada — InnovaCiencia Virtual</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<style>
:root{--or1:#fc7b04;--or2:#e86e00;--gold:#c8902a;--gold-lt:#e8b84a;--shadow-or:rgba(252,123,4,.28);--ease:.3s cubic-bezier(.4,0,.2,1);}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;background:#0e0600;color:#fff;overflow-x:hidden;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem}
.card{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:18px;padding:3rem 2.5rem;max-width:520px;width:100%;text-align:center}
.icon-wrap{width:72px;height:72px;border-radius:50%;background:rgba(16,185,129,.15);border:2px solid rgba(16,185,129,.4);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;font-size:1.8rem;color:#34d399}
h1{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;margin-bottom:.7rem}
p{font-size:.88rem;line-height:1.7;color:rgba(255,255,255,.65);margin-bottom:1.5rem}
.program-name{font-size:.82rem;font-weight:600;color:var(--or1);background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.2);border-radius:8px;padding:.6rem 1rem;margin-bottom:1.5rem}
.asesor-note{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:1rem;display:flex;align-items:center;gap:.8rem;text-align:left;margin-bottom:1.8rem}
.asesor-note i{font-size:1.2rem;color:var(--or1);flex-shrink:0}
.asesor-note strong{display:block;font-size:.8rem;color:#fff;margin-bottom:.15rem}
.asesor-note span{font-size:.75rem;color:rgba(255,255,255,.55)}
.btn-home{display:inline-flex;align-items:center;gap:.5rem;padding:.72rem 1.8rem;border-radius:8px;background:var(--or1);color:#fff;font-weight:600;font-size:.88rem;border:none;cursor:pointer;box-shadow:0 4px 20px var(--shadow-or);text-decoration:none;transition:background var(--ease),transform var(--ease)}
.btn-home:hover{background:var(--or2);transform:translateY(-2px)}
footer{margin-top:2rem;font-size:.72rem;color:rgba(255,255,255,.3)}
footer span{color:var(--or1)}
</style>
</head>
<body>

@php
    $oferta       = $enlace->ofertaAcademica;
    $asesor       = $enlace->trabajadoresCargo;
    $personaAsesor= optional(optional($asesor)->trabajador)->persona;
    $nombreAsesor = $personaAsesor
        ? trim(($personaAsesor->nombres ?? '') . ' ' . ($personaAsesor->apellido_paterno ?? ''))
        : null;
@endphp

<div class="card" id="card">
    <div class="icon-wrap">
        <i class="fa-solid fa-circle-check"></i>
    </div>

    <h1>¡Solicitud enviada!</h1>
    <p>Tu solicitud de pre-inscripción fue recibida correctamente. Pronto nos comunicaremos contigo.</p>

    <div class="program-name">
        <i class="fa-solid fa-graduation-cap" style="margin-right:.4rem;"></i>
        {{ optional($oferta->programa)->nombre ?? 'Programa de Posgrado' }}
    </div>

    @if($nombreAsesor)
    <div class="asesor-note">
        <i class="fa-solid fa-user-tie"></i>
        <div>
            <strong>{{ $nombreAsesor }}</strong>
            <span>Tu asesor se pondrá en contacto contigo a la brevedad.</span>
        </div>
    </div>
    @endif

    <a href="{{ route('login') }}" class="btn-home">
        <i class="fa-solid fa-house"></i>
        Volver al inicio
    </a>
</div>

<footer>© {{ date('Y') }} <span>InnovaCiencia Virtual</span></footer>

<script>
    gsap.from('#card', { opacity:0, y:40, duration:.8, ease:'power3.out' });
</script>
</body>
</html>
