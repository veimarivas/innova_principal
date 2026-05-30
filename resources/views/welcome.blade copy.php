<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innova Ciencia Virtual — Posgrados de Excelencia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
<style>
/* ─────────────────────────────────────────────
   TOKENS
───────────────────────────────────────────── */
:root{
    --or1:#fc7b04; --or2:#e86e00; --or3:#c06000;
    --or4:#8a4500; --or5:#5c2e00; --or6:#2e1600;
    --gold:#c8902a; --gold-lt:#e8b84a;
    --cream:#fdf8f2; --cream2:#f5ede0;
    --ink:#1c0d00; --ink2:#3a1e08;
    --white:#fff;
    --t-light:rgba(255,255,255,.88); --t-muted:rgba(255,255,255,.52);
    --shadow-or:rgba(252,123,4,.32);
    --ease:.3s cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;background:#0e0600;color:var(--white);overflow-x:hidden}
a{text-decoration:none;color:inherit}
ul{list-style:none}
img{display:block;max-width:100%}

/* ─────────────────────────────────────────────
   UTILITIES
───────────────────────────────────────────── */
.container{max-width:1180px;margin:0 auto;padding:0 1.5rem}
.section-pad{padding:5.5rem 0}

.eyebrow{
    display:inline-flex;align-items:center;gap:.45rem;
    font-size:.72rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;
    color:var(--or1);margin-bottom:1rem;
}
.eyebrow::before{content:'';width:28px;height:2px;background:var(--or1);border-radius:2px}

.title-serif{
    font-family:'Playfair Display',serif;
    font-size:clamp(2rem,3.5vw,2.8rem);
    font-weight:700;line-height:1.2;
    color:var(--white);margin-bottom:1rem;
}
.title-serif.dark{color:var(--ink)}
.title-serif span{color:var(--or1)}
.title-serif em{font-style:italic;color:var(--gold-lt)}

.subtitle{
    font-size:.98rem;line-height:1.75;color:var(--t-muted);max-width:560px;
}
.subtitle.dark{color:#7a4820}

.btn-primary{
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.72rem 1.7rem;border-radius:4px;
    background:var(--or1);color:var(--white);
    font-weight:600;font-size:.9rem;border:none;cursor:pointer;
    box-shadow:0 4px 20px var(--shadow-or);
    transition:background var(--ease),transform var(--ease),box-shadow var(--ease);
}
.btn-primary:hover{background:var(--or2);transform:translateY(-2px);box-shadow:0 8px 28px var(--shadow-or)}

.btn-outline{
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.7rem 1.7rem;border-radius:4px;
    border:1.5px solid rgba(252,123,4,.5);
    color:var(--or1);font-weight:600;font-size:.9rem;
    transition:all var(--ease);cursor:pointer;background:transparent;
}
.btn-outline:hover{background:rgba(252,123,4,.08);border-color:var(--or1)}

.btn-outline.dark{border-color:rgba(92,46,0,.4);color:var(--or3)}
.btn-outline.dark:hover{background:rgba(252,123,4,.06);border-color:var(--or3)}

/* ─────────────────────────────────────────────
   LOADING
───────────────────────────────────────────── */
#loading{
    position:fixed;inset:0;background:#0e0600;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    z-index:9999;
}
.ld-ring{
    width:52px;height:52px;border-radius:50%;
    border:3px solid rgba(252,123,4,.15);border-top-color:var(--or1);
    animation:spin .85s linear infinite;margin-bottom:1rem;
}
@keyframes spin{to{transform:rotate(360deg)}}
.ld-text{font-size:.68rem;font-weight:600;letter-spacing:.25em;color:var(--or1);opacity:0}

/* ─────────────────────────────────────────────
   SCROLL PROGRESS
───────────────────────────────────────────── */
.scroll-bar{
    position:fixed;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,var(--or1),var(--gold-lt));
    transform:scaleX(0);transform-origin:left;z-index:9997;
}

/* ─────────────────────────────────────────────
   MOBILE OVERLAY
───────────────────────────────────────────── */
.mob-overlay{
    position:fixed;inset:0;background:rgba(14,6,0,.78);backdrop-filter:blur(6px);
    z-index:998;opacity:0;pointer-events:none;transition:opacity .3s;
}
.mob-overlay.open{opacity:1;pointer-events:all}

/* ─────────────────────────────────────────────
   HEADER
───────────────────────────────────────────── */
#hdr{
    position:fixed;top:0;left:0;right:0;z-index:999;
    padding:.9rem 0;
    transition:background .4s,padding .4s,box-shadow .4s,border-color .4s;
    border-bottom:1px solid transparent;
}
#hdr.scrolled{
    background:rgba(14,6,0,.96);backdrop-filter:blur(16px);
    padding:.6rem 0;border-color:rgba(252,123,4,.12);
    box-shadow:0 1px 32px rgba(0,0,0,.5);
}
.nav{display:flex;align-items:center;justify-content:space-between;gap:1rem}

.brand{display:flex;align-items:center;gap:.7rem}
.brand-icon{
    width:44px;height:44px;border-radius:8px;
    background:linear-gradient(135deg,var(--or1),var(--or2));
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;box-shadow:0 4px 16px var(--shadow-or);flex-shrink:0;
}
.brand-name{
    font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;
    color:var(--white);line-height:1.1;
}
.brand-name small{display:block;font-family:'Inter',sans-serif;font-size:.62rem;font-weight:500;letter-spacing:.12em;color:var(--or1);text-transform:uppercase;margin-top:1px}

.nav-links{display:flex;gap:2rem;align-items:center}
.nav-links a{
    font-size:.85rem;font-weight:500;color:var(--t-light);
    position:relative;transition:color var(--ease);
}
.nav-links a::after{
    content:'';position:absolute;bottom:-3px;left:0;width:0;height:1.5px;
    background:var(--or1);transition:width var(--ease);
}
.nav-links a:hover{color:var(--or1)}
.nav-links a:hover::after{width:100%}

.hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;padding:3px}
.hamburger span{display:block;width:22px;height:1.5px;background:var(--white);border-radius:2px;transition:all .3s}
.hamburger.open span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.hamburger.open span:nth-child(2){opacity:0;transform:scaleX(0)}
.hamburger.open span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}

/* ─────────────────────────────────────────────
   HERO
───────────────────────────────────────────── */
.hero{
    min-height:100vh;position:relative;overflow:hidden;
    display:flex;align-items:center;padding:7rem 0 4rem;
    background:
        radial-gradient(ellipse 80% 60% at 70% 40%, rgba(252,123,4,.10) 0%, transparent 60%),
        radial-gradient(ellipse 50% 40% at 15% 80%, rgba(200,144,42,.06) 0%, transparent 50%),
        linear-gradient(170deg,#120700 0%,#0a0400 55%,#150800 100%);
}

/* Decorative large text */
.hero-bg-text{
    position:absolute;right:-2%;top:50%;transform:translateY(-50%);
    font-family:'Playfair Display',serif;font-size:clamp(140px,18vw,240px);
    font-weight:800;line-height:1;
    color:rgba(252,123,4,.04);pointer-events:none;user-select:none;white-space:nowrap;
}

/* Orbs */
.orb{position:absolute;border-radius:50%;pointer-events:none;will-change:transform}
.orb-1{
    width:500px;height:500px;
    background:radial-gradient(circle,rgba(252,123,4,.09) 0%,transparent 70%);
    top:-15%;right:5%;
}
.orb-2{
    width:320px;height:320px;
    background:radial-gradient(circle,rgba(200,144,42,.07) 0%,transparent 70%);
    bottom:5%;left:-5%;
}
/* Grid lines decoration */
.hero-grid{
    position:absolute;inset:0;
    background-image:linear-gradient(rgba(252,123,4,.04) 1px,transparent 1px),
                     linear-gradient(90deg,rgba(252,123,4,.04) 1px,transparent 1px);
    background-size:60px 60px;
    mask-image:radial-gradient(ellipse 70% 70% at 50% 50%, black 30%, transparent 100%);
    pointer-events:none;
}

.hero-inner{
    position:relative;z-index:2;
    display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;
}

.hero-tag{
    display:inline-flex;align-items:center;gap:.5rem;
    background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.25);
    border-radius:50px;padding:.35rem .9rem;
    font-size:.72rem;font-weight:600;color:var(--or1);
    letter-spacing:.08em;margin-bottom:1.4rem;
}
.hero-tag i{font-size:.65rem}

.hero-h1{
    font-family:'Playfair Display',serif;
    font-size:clamp(2.2rem,4.5vw,3.6rem);
    font-weight:800;line-height:1.13;
    color:var(--white);margin-bottom:1.4rem;
}
.hero-h1 em{font-style:italic;color:var(--gold-lt)}
.hero-h1 strong{color:var(--or1);font-style:normal}

.hero-desc{font-size:1rem;line-height:1.75;color:var(--t-light);margin-bottom:2.2rem;max-width:480px}

.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}

/* Right side */
.hero-right{display:flex;flex-direction:column;gap:1.2rem}

.hero-card{
    background:rgba(255,255,255,.04);
    border:1px solid rgba(252,123,4,.18);
    border-radius:14px;padding:1.3rem 1.5rem;
    backdrop-filter:blur(8px);
    display:flex;align-items:center;gap:1rem;
    transition:border-color .3s,background .3s,transform .3s;
}
.hero-card:hover{
    border-color:rgba(252,123,4,.38);
    background:rgba(252,123,4,.07);transform:translateX(4px);
}
.hero-card-icon{
    width:46px;height:46px;border-radius:10px;flex-shrink:0;
    background:linear-gradient(135deg,var(--or1),var(--or2));
    display:flex;align-items:center;justify-content:center;font-size:1.1rem;
    box-shadow:0 4px 12px var(--shadow-or);
}
.hero-card-body h4{font-size:.88rem;font-weight:600;color:var(--white);margin-bottom:.15rem}
.hero-card-body p{font-size:.78rem;color:var(--t-muted);line-height:1.4}

/* ─────────────────────────────────────────────
   STATS STRIP
───────────────────────────────────────────── */
.stats-strip{
    background:var(--or1);padding:2.4rem 0;
    position:relative;overflow:hidden;
}
.stats-strip::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(135deg,var(--or2) 0%,var(--or1) 50%,var(--or2) 100%);
}
.stats-row{
    position:relative;z-index:1;
    display:grid;grid-template-columns:repeat(4,1fr);gap:0;
}
.stat-item{
    text-align:center;padding:0 1rem;
    border-right:1px solid rgba(255,255,255,.2);
}
.stat-item:last-child{border-right:none}
.stat-num{
    font-family:'Playfair Display',serif;font-size:2.8rem;font-weight:800;
    color:var(--white);line-height:1;display:block;
}
.stat-label{font-size:.78rem;font-weight:600;color:rgba(255,255,255,.8);letter-spacing:.06em;text-transform:uppercase;margin-top:.3rem;display:block}

/* ─────────────────────────────────────────────
   ABOUT / INTRO
───────────────────────────────────────────── */
.about-section{
    background:var(--cream);padding:5.5rem 0;
    position:relative;overflow:hidden;
}
.about-section::before{
    content:'';position:absolute;top:0;right:0;width:45%;height:100%;
    background:linear-gradient(135deg,transparent 0%,rgba(252,123,4,.04) 100%);
    clip-path:polygon(15% 0,100% 0,100% 100%,0% 100%);
    pointer-events:none;
}
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.about-text{position:relative;z-index:1}
.about-text .title-serif.dark{font-size:clamp(1.7rem,3vw,2.4rem)}
.pillars{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:2rem}
.pillar{
    background:var(--white);border-radius:10px;padding:1.1rem;
    border:1px solid rgba(252,123,4,.1);
    transition:transform .25s,box-shadow .25s;
}
.pillar:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(252,123,4,.12)}
.pillar i{font-size:1.1rem;color:var(--or1);margin-bottom:.5rem;display:block}
.pillar h4{font-size:.82rem;font-weight:700;color:var(--ink);margin-bottom:.25rem}
.pillar p{font-size:.76rem;color:var(--or4);line-height:1.5}

.about-visual{
    position:relative;display:grid;grid-template-columns:1fr 1fr;gap:1rem;
}
.av-badge{
    background:var(--white);border-radius:14px;padding:1.6rem;text-align:center;
    border:1px solid rgba(252,123,4,.12);
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    transition:transform .25s,box-shadow .25s;
}
.av-badge:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(252,123,4,.14)}
.av-badge:nth-child(1){grid-column:span 2}
.av-badge .big-num{
    font-family:'Playfair Display',serif;font-size:3.2rem;font-weight:800;
    color:var(--or1);line-height:1;
}
.av-badge span{font-size:.78rem;font-weight:600;color:var(--or4);text-transform:uppercase;letter-spacing:.08em}
.av-badge p{font-size:.8rem;color:var(--or4);margin-top:.25rem}

/* ─────────────────────────────────────────────
   PROGRAM TYPES
───────────────────────────────────────────── */
.types-section{background:#0e0600;padding:5.5rem 0}
.types-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.2rem;margin-top:.5rem}
.type-card{
    position:relative;overflow:hidden;border-radius:14px;
    background:rgba(255,255,255,.035);
    border:1px solid rgba(252,123,4,.14);
    padding:2rem 1.6rem;
    transition:border-color .3s,background .3s,transform .3s;
    cursor:default;
}
.type-card::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(135deg,rgba(252,123,4,.06) 0%,transparent 60%);
    opacity:0;transition:opacity .3s;
}
.type-card:hover{transform:translateY(-5px);border-color:rgba(252,123,4,.35)}
.type-card:hover::before{opacity:1}
.type-icon{
    width:52px;height:52px;border-radius:12px;
    background:linear-gradient(135deg,var(--or1),var(--or2));
    display:flex;align-items:center;justify-content:center;
    font-size:1.2rem;color:var(--white);margin-bottom:1.2rem;
    box-shadow:0 6px 16px var(--shadow-or);position:relative;z-index:1;
}
.type-card h3{
    font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;
    color:var(--white);margin-bottom:.5rem;position:relative;z-index:1;
}
.type-card p{font-size:.82rem;color:var(--t-muted);line-height:1.6;position:relative;z-index:1;margin-bottom:1rem}
.type-link{
    font-size:.8rem;font-weight:600;color:var(--or1);
    display:inline-flex;align-items:center;gap:.3rem;
    position:relative;z-index:1;transition:gap .2s;
}
.type-link:hover{gap:.6rem}

/* ─────────────────────────────────────────────
   PROGRAMS CATALOG
───────────────────────────────────────────── */
.catalog-section{background:var(--cream);padding:5.5rem 0}
.filter-row{
    display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:2.5rem;
}
.filter-btn{
    padding:.4rem 1.1rem;border-radius:4px;
    border:1.5px solid rgba(92,46,0,.22);
    background:transparent;color:var(--or4);
    font-size:.82rem;font-weight:500;cursor:pointer;
    transition:all .2s;font-family:'Inter',sans-serif;
}
.filter-btn:hover,.filter-btn.active{
    background:var(--or1);border-color:var(--or1);color:var(--white);
}
.catalog-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:1.8rem}

.prog-card{
    background:var(--white);border-radius:14px;overflow:hidden;
    border:1px solid rgba(0,0,0,.07);
    transition:transform .3s,box-shadow .3s;
    display:flex;flex-direction:column;
}
.prog-card:hover{transform:translateY(-6px);box-shadow:0 20px 48px rgba(92,46,0,.14)}

.prog-img{position:relative;height:192px;overflow:hidden;background:#f0e8dc}
.prog-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.prog-card:hover .prog-img img{transform:scale(1.05)}
.prog-type-badge{
    position:absolute;top:.7rem;left:.7rem;
    background:var(--or1);color:var(--white);
    font-size:.62rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
    padding:.25rem .6rem;border-radius:3px;
}
.prog-fase-badge{
    position:absolute;top:.7rem;right:.7rem;
    background:var(--ink2);color:rgba(255,255,255,.9);
    font-size:.62rem;font-weight:600;
    padding:.25rem .6rem;border-radius:3px;
}
.prog-convenio{
    position:absolute;bottom:.6rem;right:.6rem;
    background:rgba(255,255,255,.92);border-radius:6px;
    padding:.2rem .5rem;height:32px;display:flex;align-items:center;
}
.prog-convenio img{height:22px;object-fit:contain}

.prog-body{padding:1.3rem;flex:1;display:flex;flex-direction:column}
.prog-sede{
    font-size:.7rem;font-weight:600;color:var(--or3);
    text-transform:uppercase;letter-spacing:.06em;margin-bottom:.4rem;
    display:flex;align-items:center;gap:.3rem;
}
.prog-sede i{font-size:.65rem}
.prog-title{
    font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;
    color:var(--ink);line-height:1.3;margin-bottom:.5rem;
}
.prog-desc{font-size:.8rem;color:#7a5030;line-height:1.55;margin-bottom:.9rem;flex:1}
.prog-meta{
    display:flex;flex-direction:column;gap:.3rem;
    padding:.9rem 0;border-top:1px solid rgba(0,0,0,.06);border-bottom:1px solid rgba(0,0,0,.06);
    margin-bottom:1rem;
}
.prog-meta-item{font-size:.76rem;color:#9a6040;display:flex;align-items:center;gap:.4rem}
.prog-meta-item i{color:var(--or1);width:12px;text-align:center}
.prog-footer{display:flex;align-items:center;justify-content:space-between}
.prog-price{
    font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--or1);
}
.prog-price small{font-family:'Inter',sans-serif;font-size:.65rem;color:#9a6040;display:block;font-weight:400}

.no-prog-msg{
    grid-column:1/-1;text-align:center;padding:3.5rem;
    background:var(--white);border-radius:14px;border:1px dashed rgba(252,123,4,.3);
}
.no-prog-msg i{font-size:2.5rem;color:var(--or1);margin-bottom:1rem;display:block}
.no-prog-msg h3{font-size:1.1rem;color:var(--ink);margin-bottom:.4rem}
.no-prog-msg p{font-size:.85rem;color:#7a5030}

/* ─────────────────────────────────────────────
   DIFFERENTIATORS
───────────────────────────────────────────── */
.why-section{background:#0e0600;padding:5.5rem 0}
.why-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:.5rem}
.why-card{
    border:1px solid rgba(252,123,4,.12);border-radius:14px;
    padding:2.2rem 1.8rem;
    background:rgba(255,255,255,.025);
    position:relative;overflow:hidden;
    transition:border-color .3s,transform .3s;
}
.why-card:hover{border-color:rgba(252,123,4,.35);transform:translateY(-4px)}
.why-num{
    font-family:'Playfair Display',serif;font-size:4rem;font-weight:800;
    color:rgba(252,123,4,.07);position:absolute;top:.5rem;right:1rem;
    line-height:1;pointer-events:none;user-select:none;
}
.why-icon{
    width:48px;height:48px;border-radius:10px;
    background:linear-gradient(135deg,var(--or1),var(--or2));
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;color:var(--white);margin-bottom:1.2rem;
    box-shadow:0 4px 14px var(--shadow-or);
}
.why-card h3{font-size:1rem;font-weight:700;color:var(--white);margin-bottom:.55rem}
.why-card p{font-size:.83rem;color:var(--t-muted);line-height:1.65}

/* ─────────────────────────────────────────────
   TEAM
───────────────────────────────────────────── */
.team-section{background:var(--cream2);padding:5.5rem 0}
.carousel-wrap{position:relative;display:flex;align-items:center;gap:.8rem}
.carousel-track-wrap{flex:1;overflow:hidden;cursor:grab}
.carousel-track-wrap:active{cursor:grabbing}
.carousel-track{display:flex;gap:1.3rem;will-change:transform}

.team-card{
    min-width:220px;max-width:220px;border-radius:14px;overflow:hidden;
    background:var(--white);border:1px solid rgba(0,0,0,.08);flex-shrink:0;
    transition:transform .3s,box-shadow .3s;
}
.team-card:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(92,46,0,.14)}
.team-img{width:100%;height:190px;overflow:hidden;background:#f0e8dc}
.team-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.team-card:hover .team-img img{transform:scale(1.05)}
.team-info{padding:1.1rem}
.team-info h4{
    font-family:'Playfair Display',serif;font-size:.92rem;font-weight:700;
    color:var(--ink);line-height:1.3;margin-bottom:.25rem;
}
.team-role{font-size:.73rem;font-weight:600;color:var(--or1);display:block;margin-bottom:.5rem}
.team-sede{font-size:.72rem;color:#8a5030;display:flex;align-items:center;gap:.3rem;margin-bottom:.7rem}
.team-sede i{color:var(--or3);font-size:.65rem}
.team-contacts{display:flex;gap:.4rem}
.tcb{
    width:30px;height:30px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.75rem;transition:transform .2s,background .2s;
    position:relative;
}
.tcb-email{background:rgba(252,123,4,.1);color:var(--or1)}
.tcb-email:hover{background:var(--or1);color:var(--white);transform:scale(1.12)}
.tcb-wa{background:rgba(37,211,102,.1);color:#25d366}
.tcb-wa:hover{background:#25d366;color:var(--white);transform:scale(1.12)}

.car-btn{
    width:40px;height:40px;border-radius:50%;border:1.5px solid rgba(92,46,0,.22);
    background:var(--white);color:var(--or3);cursor:pointer;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;font-size:.85rem;
    transition:background .2s,border-color .2s,transform .2s;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}
.car-btn:hover:not(:disabled){background:var(--or1);border-color:var(--or1);color:var(--white);transform:scale(1.08)}
.car-btn:disabled{opacity:.3;cursor:not-allowed}

/* ─────────────────────────────────────────────
   PARTNERS
───────────────────────────────────────────── */
.partners-section{background:#0e0600;padding:4.5rem 0 3.5rem;overflow:hidden}
.partners-ticker-wrap{
    overflow:hidden;margin-top:2rem;
    mask-image:linear-gradient(90deg,transparent,black 12%,black 88%,transparent);
}
.partners-ticker{display:flex;gap:1.5rem;animation:tickerScroll 28s linear infinite;width:max-content}
.partners-ticker:hover{animation-play-state:paused}
@keyframes tickerScroll{from{transform:translateX(0)}to{transform:translateX(-33.333%)}}
.partner-item{
    width:150px;height:80px;border-radius:10px;flex-shrink:0;
    background:rgba(255,255,255,.04);border:1px solid rgba(252,123,4,.12);
    display:flex;align-items:center;justify-content:center;padding:.8rem;
    transition:border-color .3s,background .3s;
}
.partner-item:hover{border-color:rgba(252,123,4,.35);background:rgba(252,123,4,.07)}
.partner-item img{max-height:50px;object-fit:contain;filter:brightness(0) invert(1);opacity:.6;transition:opacity .3s,filter .3s}
.partner-item:hover img{opacity:1;filter:none}

/* ─────────────────────────────────────────────
   SEDES
───────────────────────────────────────────── */
.sedes-section{background:var(--cream);padding:5.5rem 0}
.sede-track-wrap{flex:1;overflow:hidden;cursor:grab}
.sede-track-wrap:active{cursor:grabbing}
.sede-track{display:flex;gap:1.4rem;will-change:transform}
.sede-card{
    min-width:290px;max-width:290px;border-radius:14px;overflow:hidden;
    background:var(--white);border:1px solid rgba(0,0,0,.08);flex-shrink:0;
    transition:transform .3s,box-shadow .3s;
}
.sede-card:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(92,46,0,.13);cursor:pointer}
.sede-map{display:block;height:170px;overflow:hidden}
.sede-map iframe{width:100%;height:170px;border:0;display:block;pointer-events:none}
.sede-info{padding:1.2rem}
.sede-name{
    font-family:'Playfair Display',serif;font-size:.98rem;font-weight:700;
    color:var(--ink);margin-bottom:.15rem;
}
.sede-parent{font-size:.72rem;font-weight:600;color:var(--or3);margin-bottom:.3rem;text-transform:uppercase;letter-spacing:.05em}
.sede-dir{font-size:.78rem;color:#7a5030;display:flex;align-items:flex-start;gap:.3rem;margin-bottom:.9rem}
.sede-dir i{color:var(--or1);margin-top:2px;flex-shrink:0;font-size:.7rem}
.sede-stats{display:flex;gap:.6rem;border-top:1px solid rgba(0,0,0,.07);padding-top:.8rem}
.sede-stat{flex:1;text-align:center}
.sede-stat-num{
    display:block;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--or1);
}
.sede-stat-lbl{font-size:.64rem;color:#9a6040;text-transform:uppercase;letter-spacing:.05em}

/* ─────────────────────────────────────────────
   CTA
───────────────────────────────────────────── */
.cta-section{
    position:relative;overflow:hidden;padding:6rem 0;text-align:center;
    background:linear-gradient(150deg,#2e1600 0%,#4a2500 40%,#2e1600 100%);
}
.cta-section::before{
    content:'';position:absolute;inset:0;
    background:
        radial-gradient(ellipse 60% 80% at 80% 50%, rgba(252,123,4,.14) 0%, transparent 60%),
        radial-gradient(ellipse 40% 60% at 10% 60%, rgba(200,144,42,.10) 0%, transparent 55%);
    pointer-events:none;
}
.cta-section::after{
    content:'POSGRADO';
    position:absolute;bottom:-10%;left:50%;transform:translateX(-50%);
    font-family:'Playfair Display',serif;font-size:clamp(80px,12vw,160px);
    font-weight:800;color:rgba(255,255,255,.025);white-space:nowrap;
    pointer-events:none;user-select:none;
}
.cta-inner{position:relative;z-index:1}
.cta-inner h2{
    font-family:'Playfair Display',serif;
    font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:800;
    color:var(--white);margin-bottom:1rem;
}
.cta-inner h2 span{color:var(--gold-lt)}
.cta-inner p{font-size:.98rem;color:var(--t-light);line-height:1.75;max-width:580px;margin:0 auto 2.2rem}
.cta-actions{display:flex;justify-content:center;gap:1rem;flex-wrap:wrap}

/* ─────────────────────────────────────────────
   FOOTER
───────────────────────────────────────────── */
footer{
    background:#080400;border-top:1px solid rgba(252,123,4,.1);
    padding:4.5rem 0 2rem;
}
.footer-grid{
    display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:3rem;
    padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,.06);
    margin-bottom:2rem;
}
.footer-brand-name{
    font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;
    color:var(--white);margin-bottom:.8rem;
}
.footer-brand-name span{color:var(--or1)}
.footer-desc{font-size:.83rem;line-height:1.7;color:var(--t-muted);margin-bottom:1.4rem}
.socials{display:flex;gap:.6rem}
.social-btn{
    width:34px;height:34px;border-radius:50%;
    background:rgba(252,123,4,.1);border:1px solid rgba(252,123,4,.2);
    color:var(--or1);font-size:.8rem;
    display:flex;align-items:center;justify-content:center;
    transition:background .2s,transform .2s;
}
.social-btn:hover{background:var(--or1);color:var(--white);transform:translateY(-3px)}

.footer-col h5{
    font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
    color:var(--or1);margin-bottom:1.1rem;
}
.footer-links li{margin-bottom:.6rem}
.footer-links a{font-size:.82rem;color:var(--t-muted);transition:color .2s,padding-left .2s;display:inline-block}
.footer-links a:hover{color:var(--or1);padding-left:4px}
.footer-contact li{
    display:flex;align-items:flex-start;gap:.6rem;
    font-size:.8rem;color:var(--t-muted);margin-bottom:.7rem;
}
.footer-contact li i{color:var(--or1);margin-top:2px;flex-shrink:0}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem}
.footer-bottom p{font-size:.75rem;color:var(--t-muted)}
.footer-bottom a{color:var(--t-muted);transition:color .2s}
.footer-bottom a:hover{color:var(--or1)}

/* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */
@media(max-width:1024px){
    .footer-grid{grid-template-columns:1fr 1fr}
    .why-grid{grid-template-columns:1fr 1fr}
    .about-grid{grid-template-columns:1fr}
    .about-visual{display:none}
}
@media(max-width:768px){
    .nav-links{
        position:fixed;top:0;right:-100%;bottom:0;width:270px;
        background:rgba(8,4,0,.98);flex-direction:column;justify-content:center;
        gap:1.8rem;padding:2rem;z-index:999;
        transition:right .35s;border-left:1px solid rgba(252,123,4,.18);
    }
    .nav-links.open{right:0}
    .hamburger{display:flex}

    .hero-inner{grid-template-columns:1fr}
    .hero-right{display:none}

    .stats-row{grid-template-columns:1fr 1fr}
    .stat-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.15);padding:1rem}
    .stat-item:nth-child(2n){border-right:none}

    .why-grid{grid-template-columns:1fr}
    .footer-grid{grid-template-columns:1fr}
    .footer-bottom{flex-direction:column;text-align:center}
}
@media(max-width:480px){
    .catalog-grid{grid-template-columns:1fr}
    .stats-row{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<!-- Loading -->
<div id="loading">
    <div class="ld-ring"></div>
    <span class="ld-text">INNOVA CIENCIA VIRTUAL</span>
</div>

<!-- Scroll progress -->
<div class="scroll-bar" id="scrollBar"></div>

<!-- Mobile overlay -->
<div class="mob-overlay" id="mobOverlay"></div>

<!-- ═══════════════════════════════════
     HEADER
══════════════════════════════════════ -->
<header id="hdr">
    <div class="container">
        <nav class="nav">
            <a href="#inicio" class="brand">
                <img src="{{ asset('build/images/logo-sm.png') }}" alt="Innova Ciencia" style="width:44px;height:44px;border-radius:8px;object-fit:contain;">
                <div class="brand-name">
                    Innova Ciencia
                    <small>Virtual — Posgrados</small>
                </div>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#programas">Programas</a></li>
                <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
                <li><a href="#equipo">Equipo</a></li>
                <li><a href="#sedes">Sedes</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>

            @if(Route::has('login'))
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="btn-primary" style="font-size:.82rem;padding:.55rem 1.3rem">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                    <a href="http://moodle52.localhost/" target="_blank" class="btn-outline" style="font-size:.82rem;padding:.55rem 1.3rem">
                        <i class="fas fa-graduation-cap"></i> Moodle
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary" style="font-size:.82rem;padding:.55rem 1.3rem;margin-right:.15rem">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </a>
                    <a href="http://moodle52.localhost/" target="_blank" class="btn-outline" style="font-size:.82rem;padding:.55rem 1.3rem">
                        <i class="fas fa-graduation-cap"></i> Moodle
                    </a>
                @endauth
            @endif

            <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
        </nav>
    </div>
</header>

<!-- ═══════════════════════════════════
     HERO
══════════════════════════════════════ -->
<section class="hero" id="inicio">
    <div class="hero-bg-text">POSGRADO</div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="hero-grid"></div>

    <div class="container">
        <div class="hero-inner">
            <!-- Left -->
            <div class="hero-left">
                <div class="hero-tag">
                    <i class="fas fa-certificate"></i>
                    Excelencia Académica en Posgrados
                </div>
                <h1 class="hero-h1">
                    Potencia tu carrera con<br>
                    <em>programas de posgrado</em><br>
                    de <strong>alto nivel</strong>
                </h1>
                <p class="hero-desc">
                    Formación especializada con docentes de trayectoria, metodología innovadora
                    y respaldo de instituciones reconocidas a nivel nacional e internacional.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('catalogo') }}" class="btn-primary">
                        <i class="fas fa-book-open"></i> Explorar Programas
                    </a>
                    <a href="#contacto" class="btn-outline">
                        <i class="fas fa-envelope"></i> Contáctanos
                    </a>
                </div>
            </div>

            <!-- Right -->
            <div class="hero-right" id="heroRight">
                <div class="hero-card">
                    <div class="hero-card-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="hero-card-body">
                        <h4>Diplomados y Maestrías</h4>
                        <p>Programas con aval académico y titulación reconocida</p>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon"><i class="fas fa-laptop"></i></div>
                    <div class="hero-card-body">
                        <h4>Modalidad Virtual y Presencial</h4>
                        <p>Flexibilidad para adaptarse a tu ritmo de vida</p>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon"><i class="fas fa-handshake"></i></div>
                    <div class="hero-card-body">
                        <h4>Convenios Internacionales</h4>
                        <p>Respaldo de {{ \App\Models\Convenio::count() }} instituciones aliadas</p>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="hero-card-icon"><i class="fas fa-users"></i></div>
                    <div class="hero-card-body">
                        <h4>Comunidad Académica</h4>
                        <p>Más de {{ \App\Models\Estudiante::count() }} profesionales formados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     STATS STRIP
══════════════════════════════════════ -->
<div class="stats-strip">
    <div class="container">
        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-num" data-target="{{ \App\Models\Posgrado::count() }}">0</span>
                <span class="stat-label">Programas Académicos</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" data-target="{{ \App\Models\Estudiante::count() }}">0</span>
                <span class="stat-label">Estudiantes Formados</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" data-target="{{ \App\Models\Sucursale::count() }}">0</span>
                <span class="stat-label">Sedes Activas</span>
            </div>
            <div class="stat-item">
                <span class="stat-num" data-target="{{ \App\Models\Convenio::count() }}">0</span>
                <span class="stat-label">Convenios Institucionales</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════
     ABOUT
══════════════════════════════════════ -->
<section class="about-section" id="nosotros">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <span class="eyebrow">Quiénes somos</span>
                <h2 class="title-serif dark">
                    Formando líderes para los<br><span>desafíos del mundo</span> moderno
                </h2>
                <p class="subtitle dark" style="margin-bottom:1.8rem">
                    Somos una institución de posgrado comprometida con la calidad académica y el
                    desarrollo profesional de nuestros estudiantes. Ofrecemos programas diseñados
                    por expertos para responder a las exigencias actuales del mercado laboral.
                </p>
                <div class="pillars">
                    <div class="pillar">
                        <i class="fas fa-medal"></i>
                        <h4>Calidad certificada</h4>
                        <p>Programas auditados y avalados por instituciones aliadas</p>
                    </div>
                    <div class="pillar">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h4>Docentes expertos</h4>
                        <p>Profesionales con trayectoria académica y empresarial</p>
                    </div>
                    <div class="pillar">
                        <i class="fas fa-network-wired"></i>
                        <h4>Red de contactos</h4>
                        <p>Comunidad de profesionales de múltiples sectores</p>
                    </div>
                    <div class="pillar">
                        <i class="fas fa-clock"></i>
                        <h4>Horarios flexibles</h4>
                        <p>Clases diseñadas para profesionales en actividad</p>
                    </div>
                </div>
            </div>

            <div class="about-visual">
                <div class="av-badge">
                    <div class="big-num">{{ \App\Models\OfertasAcademica::count() }}+</div>
                    <span>Ofertas Académicas</span>
                    <p>programas abiertos a inscripciones</p>
                </div>
                <div class="av-badge">
                    <div class="big-num">{{ \App\Models\Tipo::count() }}</div>
                    <span>Modalidades</span>
                    <p>tipos de formación disponibles</p>
                </div>
                <div class="av-badge">
                    <div class="big-num">{{ \App\Models\Trabajadore::count() }}+</div>
                    <span>Especialistas</span>
                    <p>docentes y administrativos</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     PROGRAM TYPES
══════════════════════════════════════ -->
<section class="types-section" id="programas">
    <div class="container">
        <span class="eyebrow">Modalidades académicas</span>
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem">
            <h2 class="title-serif" style="margin:0">Tipos de <span>Formación</span></h2>
            <a href="{{ route('catalogo') }}" class="btn-outline" style="font-size:.82rem">
                Ver catálogo completo <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
            </a>
        </div>
        <div class="types-grid">
            @forelse($tipos as $tipo)
                <div class="type-card">
                    <div class="type-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>{{ $tipo->nombre }}</h3>
                    <p>{{ $tipo->descripcion ?? 'Programa de formación especializada diseñado para potenciar tu perfil profesional con alto estándar académico.' }}</p>
                    <a href="{{ route('catalogo') }}" class="type-link">
                        Ver programas <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            @empty
                <div class="type-card" style="grid-column:1/-1;text-align:center;padding:2.5rem">
                    <p style="color:var(--t-muted)">Próximamente se publicarán las modalidades disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     CATALOG
══════════════════════════════════════ -->
<section class="catalog-section" id="catalogo">
    <div class="container">
        <span class="eyebrow">Oferta académica</span>
        <h2 class="title-serif dark" style="margin-bottom:.7rem">
            Catálogo de <span>Programas</span> Académicos
        </h2>
        <p class="subtitle dark" style="margin-bottom:2rem">
            Explora nuestra oferta completa y filtra por sede para encontrar el programa ideal en tu ciudad.
        </p>

        <div class="filter-row">
            <button class="filter-btn active" data-filter="todos">Todos los programas</button>
            @foreach($sucursalesDisponibles as $sucursal)
                <button class="filter-btn" data-filter="{{ strtolower(str_replace(' ','', $sucursal->nombre)) }}">
                    {{ $sucursal->nombre }}
                </button>
            @endforeach
        </div>

        <div class="catalog-grid" id="catalogGrid">
            @forelse($ofertas as $oferta)
                @php
                    $planPrincipal = $oferta->planesConceptos->first(fn($pc) =>
                        $pc->plan_pago && strtolower($pc->plan_pago->nombre) === 'al contado'
                    ) ?? $oferta->planesConceptos->first();
                    $precio = $planPrincipal ? $planPrincipal->pago_bs : 0;

                    $sedeSlug  = strtolower(str_replace(' ','', optional($oferta->sucursal)->nombre ?? ''));
                    $tipoNombre = optional(optional($oferta->posgrado)->tipo)->nombre ?? 'Programa';
                    $duracion = (isset($oferta->posgrado->duracion_numero) && isset($oferta->posgrado->duracion_unidad))
                        ? "{$oferta->posgrado->duracion_numero} {$oferta->posgrado->duracion_unidad}" : null;
                @endphp
                <div class="prog-card" data-sede="{{ $sedeSlug }}">
                    <div class="prog-img">
                        @if($oferta->portada)
                            <img src="{{ asset('storage/' . $oferta->portada) }}"
                                 alt="{{ optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre }}"
                                 onerror="this.src='https://placehold.co/600x300/2e1600/fc7b04?text={{ urlencode($tipoNombre) }}'">
                        @else
                            <img src="https://placehold.co/600x300/2e1600/fc7b04?text={{ urlencode($tipoNombre) }}"
                                 alt="{{ optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre }}">
                        @endif
                        <span class="prog-type-badge">{{ $tipoNombre }}</span>
                        @if($oferta->fase)
                            <span class="prog-fase-badge">{{ $oferta->fase->nombre }}</span>
                        @endif
                        @if(optional(optional($oferta->posgrado)->convenio)->imagen)
                            <div class="prog-convenio">
                                <img src="{{ asset($oferta->posgrado->convenio->imagen) }}"
                                     alt="{{ $oferta->posgrado->convenio->nombre }}"
                                     onerror="this.parentElement.style.display='none'">
                            </div>
                        @endif
                    </div>
                    <div class="prog-body">
                        <div class="prog-sede">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ optional($oferta->sucursal)->nombre ?? 'Sin sede asignada' }}
                        </div>
                        <h3 class="prog-title">{{ optional($oferta->programa)->nombre ?? optional($oferta->posgrado)->nombre ?? 'Programa sin nombre' }}</h3>
                        <p class="prog-desc">{{ Str::limit(optional($oferta->posgrado)->objetivo ?? 'Información disponible próximamente.', 110) }}</p>
                        <div class="prog-meta">
                            @if($oferta->fecha_inicio_programa)
                                <div class="prog-meta-item">
                                    <i class="far fa-calendar-alt"></i>
                                    Inicio: {{ $oferta->fecha_inicio_programa->format('d \d\e F, Y') }}
                                </div>
                            @endif
                            @if($duracion)
                                <div class="prog-meta-item">
                                    <i class="far fa-clock"></i>
                                    Duración: {{ $duracion }}
                                </div>
                            @endif
                            @if(optional(optional($oferta->posgrado)->area)->nombre)
                                <div class="prog-meta-item">
                                    <i class="fas fa-layer-group"></i>
                                    {{ $oferta->posgrado->area->nombre }}
                                </div>
                            @endif
                        </div>
                        <div class="prog-footer">
                            <div class="prog-price">
                                @if($precio > 0)
                                    Bs. {{ number_format($precio, 0, ',', '.') }}
                                    <small>Precio de colegiatura</small>
                                @else
                                    <span style="font-size:.85rem;color:#9a6040;font-family:'Inter',sans-serif;font-weight:500">Consultar precio</span>
                                @endif
                            </div>
                            <a href="{{ route('oferta.detalle', $oferta->id) }}" class="btn-primary" style="font-size:.78rem;padding:.45rem 1rem">
                                Más información
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-prog-msg">
                    <i class="fas fa-book-open"></i>
                    <h3>No hay programas disponibles</h3>
                    <p>Próximamente publicaremos nuestra oferta académica. Contáctanos para más información.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     WHY US
══════════════════════════════════════ -->
<section class="why-section">
    <div class="container">
        <span class="eyebrow">Por qué elegirnos</span>
        <h2 class="title-serif" style="margin-bottom:.7rem">
            Una institución comprometida<br>con tu <em>crecimiento profesional</em>
        </h2>
        <p class="subtitle" style="margin-bottom:2.5rem">
            Más que un título: te ofrecemos una experiencia formativa que marca la diferencia.
        </p>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-num">01</div>
                <div class="why-icon"><i class="fas fa-award"></i></div>
                <h3>Excelencia Académica</h3>
                <p>Currículos actualizados, metodologías activas y evaluación continua para garantizar
                   aprendizajes de impacto real en tu ejercicio profesional.</p>
            </div>
            <div class="why-card">
                <div class="why-num">02</div>
                <div class="why-icon"><i class="fas fa-globe-americas"></i></div>
                <h3>Reconocimiento Internacional</h3>
                <p>Convenios con universidades e instituciones del exterior que respaldan la validez
                   y el peso académico de nuestros programas.</p>
            </div>
            <div class="why-card">
                <div class="why-num">03</div>
                <div class="why-icon"><i class="fas fa-users-cog"></i></div>
                <h3>Acompañamiento Integral</h3>
                <p>Asesoría personalizada desde la inscripción hasta la titulación, con soporte
                   académico y administrativo en cada etapa de tu formación.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     TEAM
══════════════════════════════════════ -->
<section class="team-section" id="equipo">
    <div class="container">
        <span class="eyebrow">Nuestro equipo</span>
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem">
            <h2 class="title-serif dark" style="margin:0">Personal <span>Académico</span></h2>
        </div>
        <div class="carousel-wrap">
            <button class="car-btn" id="teamPrev"><i class="fas fa-chevron-left"></i></button>
            <div class="carousel-track-wrap" id="teamWrap">
                <div class="carousel-track" id="teamTrack">
                    @if($trabajadores->isEmpty())
                        <div class="team-card">
                            <div class="team-img">
                                <img src="{{ asset('images/hombre.png') }}" alt="Equipo">
                            </div>
                            <div class="team-info">
                                <h4>Equipo Académico</h4>
                                <span class="team-role">Docente</span>
                            </div>
                        </div>
                    @else
                        @foreach($trabajadores as $area => $grupo)
                            @foreach($grupo as $trabajador)
                                @php $cp = $trabajador->trabajadores_cargos->first(); @endphp
                                @if($cp && $cp->cargo)
                                    <div class="team-card">
                                        <div class="team-img">
                                            @if($trabajador->persona->fotografia)
                                                <img src="{{ asset($trabajador->persona->fotografia) }}"
                                                     alt="{{ $trabajador->persona->nombres }}">
                                            @elseif($trabajador->persona->sexo === 'Hombre')
                                                <img src="{{ asset('images/hombre.png') }}" alt="Foto">
                                            @else
                                                <img src="{{ asset('images/mujer.png') }}" alt="Foto">
                                            @endif
                                        </div>
                                        <div class="team-info">
                                            <h4>
                                                {{ $trabajador->persona->apellido_paterno }}
                                                {{ $trabajador->persona->apellido_materno }},
                                                {{ $trabajador->persona->nombres }}
                                            </h4>
                                            <span class="team-role">{{ $cp->cargo->nombre }}</span>
                                            <div class="team-sede">
                                                <i class="fas fa-map-marker-alt"></i>
                                                @if($cp->sucursale)
                                                    {{ optional($cp->sucursale->sede)->nombre }} — {{ $cp->sucursale->nombre }}
                                                @else
                                                    {{ $area }} (Todas las sedes)
                                                @endif
                                            </div>
                                            <div class="team-contacts">
                                                @if($trabajador->persona->correo)
                                                    <a href="mailto:{{ $trabajador->persona->correo }}"
                                                       class="tcb tcb-email" title="{{ $trabajador->persona->correo }}">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                @endif
                                                @if($trabajador->persona->celular)
                                                    <a href="https://wa.me/591{{ $trabajador->persona->celular }}"
                                                       target="_blank" class="tcb tcb-wa" title="{{ $trabajador->persona->celular }}">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                </div>
            </div>
            <button class="car-btn" id="teamNext"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     PARTNERS
══════════════════════════════════════ -->
@if($convenios->isNotEmpty())
<section class="partners-section">
    <div class="container">
        <span class="eyebrow">Alianzas estratégicas</span>
        <h2 class="title-serif" style="margin-bottom:.5rem">
            Instituciones de <span>Convenio</span>
        </h2>
        <p class="subtitle">
            Respaldamos nuestros programas con el aval de instituciones reconocidas a nivel nacional e internacional.
        </p>
    </div>
    <div class="partners-ticker-wrap" style="margin-top:2rem">
        <div class="partners-ticker">
            @foreach($convenios as $c)
                <div class="partner-item">
                    <img src="{{ asset($c->imagen) }}" alt="{{ $c->nombre }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <span style="display:none;font-size:.75rem;font-weight:700;color:var(--or1);text-align:center">{{ $c->nombre }}</span>
                </div>
            @endforeach
            @foreach($convenios as $c)
                <div class="partner-item">
                    <img src="{{ asset($c->imagen) }}" alt="{{ $c->nombre }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <span style="display:none;font-size:.75rem;font-weight:700;color:var(--or1);text-align:center">{{ $c->nombre }}</span>
                </div>
            @endforeach
            @foreach($convenios as $c)
                <div class="partner-item">
                    <img src="{{ asset($c->imagen) }}" alt="{{ $c->nombre }}"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <span style="display:none;font-size:.75rem;font-weight:700;color:var(--or1);text-align:center">{{ $c->nombre }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ═══════════════════════════════════
     SEDES
══════════════════════════════════════ -->
@if($sucursales->isNotEmpty())
<section class="sedes-section" id="sedes">
    <div class="container">
        <span class="eyebrow">Presencia nacional</span>
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:1rem;margin-bottom:2.5rem">
            <h2 class="title-serif dark" style="margin:0">Nuestras <span>Sedes</span></h2>
        </div>
        <div class="carousel-wrap">
            <button class="car-btn" id="sedePrev"><i class="fas fa-chevron-left"></i></button>
            <div class="sede-track-wrap" id="sedeWrap">
                <div class="sede-track" id="sedeTrack">
                    @foreach($sucursales as $sucursal)
                        <div class="sede-card" data-sede="{{ strtolower(str_replace(' ','', $sucursal->nombre)) }}">
                            <div class="sede-map">
                                <iframe
                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC11Mvkl0voVbsklAQ-eTIBLfWmJB2w64k&q={{ $sucursal->latitud }},{{ $sucursal->longitud }}"
                                    width="100%" height="170" style="border:0" allowfullscreen loading="lazy">
                                </iframe>
                            </div>
                            <div class="sede-info">
                                @if($sucursal->sede)
                                    <div class="sede-parent">{{ $sucursal->sede->nombre }}</div>
                                @endif
                                <div class="sede-name">{{ $sucursal->nombre }}</div>
                                @if($sucursal->direccion)
                                    <div class="sede-dir">
                                        <i class="fas fa-map-pin"></i>
                                        {{ $sucursal->direccion }}
                                    </div>
                                @endif
                                <div class="sede-stats">
                                    <div class="sede-stat">
                                        <span class="sede-stat-num">{{ $sucursal->ofertas_academicas()->count() }}</span>
                                        <span class="sede-stat-lbl">Programas</span>
                                    </div>
                                    <div class="sede-stat">
                                        <span class="sede-stat-num">
                                            {{ $sucursal->ofertas_academicas()->withCount('inscripciones')->get()->sum('inscripciones_count') }}
                                        </span>
                                        <span class="sede-stat-lbl">Inscritos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="car-btn" id="sedeNext"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>
@endif

<!-- ═══════════════════════════════════
     CTA
══════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">
        <div class="cta-inner">
            <span class="eyebrow" style="justify-content:center;color:var(--gold-lt)">Da el siguiente paso</span>
            <h2>Transforma tu futuro con <span>Innova Ciencia Virtual</span></h2>
            <p>
                Inscríbete hoy o solicita asesoría personalizada. Nuestro equipo está listo para
                orientarte en la elección del programa que mejor se adapte a tus objetivos.
            </p>
            <div class="cta-actions">
                <a href="{{ route('catalogo') }}" class="btn-primary" style="font-size:1rem;padding:.8rem 2rem">
                    <i class="fas fa-book-open"></i> Ver Programas
                </a>
                <a href="#contacto" class="btn-outline" style="border-color:rgba(255,255,255,.3);color:var(--white)">
                    <i class="fas fa-paper-plane"></i> Solicitar Información
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<footer id="contacto">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-brand-name">Innova <span>Ciencia</span> Virtual</div>
                <p class="footer-desc">
                    Institución de posgrado comprometida con la formación de profesionales de alto nivel,
                    con metodología innovadora, docentes especializados y respaldo de convenios
                    académicos nacionales e internacionales.
                </p>
                <div class="socials">
                    <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h5>Navegación</h5>
                <ul class="footer-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#nosotros">Quiénes somos</a></li>
                    <li><a href="#programas">Tipos de Programa</a></li>
                    <li><a href="{{ route('catalogo') }}">Catálogo Académico</a></li>
                    <li><a href="#equipo">Equipo Académico</a></li>
                    <li><a href="#sedes">Nuestras Sedes</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>Programas</h5>
                <ul class="footer-links">
                    @foreach($tipos as $tipo)
                        <li><a href="{{ route('catalogo') }}">{{ $tipo->nombre }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer-col">
                <h5>Contáctanos</h5>
                <ul class="footer-contact">
                    @foreach($sucursales->take(3) as $s)
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>
                                <strong style="display:block;color:rgba(255,255,255,.7);font-size:.78rem">
                                    {{ optional($s->sede)->nombre }} — {{ $s->nombre }}
                                </strong>
                                @if($s->direccion) {{ $s->direccion }} @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Innova Ciencia Virtual. Todos los derechos reservados.</p>
            <p>
                <a href="#">Términos y Condiciones</a>
                &nbsp;·&nbsp;
                <a href="#">Política de Privacidad</a>
            </p>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════
     SCRIPTS
══════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── Loading screen: puro CSS/JS, sin depender de GSAP ── */
    const loading = document.getElementById('loading');
    const ldText  = loading ? loading.querySelector('.ld-text') : null;

    if (ldText) {
        setTimeout(() => { ldText.style.transition = 'opacity .5s'; ldText.style.opacity = '1'; }, 400);
    }
    setTimeout(() => {
        if (loading) {
            loading.style.transition = 'opacity .7s ease';
            loading.style.opacity = '0';
            setTimeout(() => {
                loading.style.display = 'none';
                boot();
            }, 720);
        }
    }, 1400);

    /* ── Header: independiente de GSAP ── */
    const hdr = document.getElementById('hdr');
    window.addEventListener('scroll', () => hdr && hdr.classList.toggle('scrolled', scrollY > 60), { passive: true });

    function boot() {
        if (typeof gsap === 'undefined') return;
        gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

        /* ── Scroll bar ── */
        const sb = document.getElementById('scrollBar');
        if (sb) {
            ScrollTrigger.create({ start: 0, end: 'bottom bottom',
                onUpdate: s => sb.style.transform = `scaleX(${s.progress})`
            });
        }

        /* ── Hero ── */
        const tl = gsap.timeline();
        tl.fromTo('.hero-tag',   { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: .6, ease: 'power3.out' })
          .fromTo('.hero-h1',    { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: .9, ease: 'power3.out' }, '-=.2')
          .fromTo('.hero-desc',  { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: .7, ease: 'power3.out' }, '-=.4')
          .fromTo('.hero-actions .btn-primary, .hero-actions .btn-outline',
                  { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: .6, stagger: .1, ease: 'power3.out' }, '-=.3')
          .fromTo('.hero-card',  { opacity: 0, x: 24 }, { opacity: 1, x: 0, duration: .55, stagger: .12, ease: 'power3.out' }, '-=.5');

        /* Orb float */
        gsap.to('.orb-1', { y: -30, x: 20, duration: 8, repeat: -1, yoyo: true, ease: 'sine.inOut' });
        gsap.to('.orb-2', { y: 25,  x: -15, duration: 10, repeat: -1, yoyo: true, ease: 'sine.inOut' });

        /* ── Stats counter ── */
        ScrollTrigger.create({
            trigger: '.stats-strip', start: 'top 85%', once: true,
            onEnter: () => {
                document.querySelectorAll('.stat-num[data-target]').forEach(el => {
                    const target = +el.dataset.target;
                    gsap.fromTo({ val: 0 }, { val: target }, {
                        duration: 1.8, ease: 'power2.out',
                        onUpdate: function() { el.textContent = Math.round(this.targets()[0].val); }
                    });
                });
            }
        });

        /* ── Scroll reveal helper ── */
        const neutralVal = { x: 0, y: 0, scale: 1, rotate: 0, skewX: 0, skewY: 0 };
        function reveal(sel, from, opts = {}) {
            gsap.utils.toArray(sel).forEach((el, i) => {
                const to = {};
                Object.keys(from).forEach(k => { to[k] = neutralVal[k] !== undefined ? neutralVal[k] : 0; });
                gsap.fromTo(el, { opacity: 0, ...from }, {
                    opacity: 1, ...to,
                    duration: opts.duration ?? .75, delay: i * (opts.stagger ?? .08),
                    ease: opts.ease ?? 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 88%', once: true }
                });
            });
        }

        reveal('.pillar',     { y: 24 });
        reveal('.av-badge',   { y: 24, scale: .95 }, { ease: 'back.out(1.5)' });
        reveal('.type-card',  { y: 30 });
        reveal('.prog-card',  { y: 32 }, { stagger: .06 });
        reveal('.why-card',   { y: 28 }, { stagger: .1 });
        reveal('.sede-card',  { y: 24 }, { stagger: .08 });

        gsap.utils.toArray('.section-title-anim, .eyebrow, .title-serif').forEach(el => {
            gsap.fromTo(el, { opacity: 0, y: 22 }, {
                opacity: 1, y: 0, duration: .75, ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%', once: true }
            });
        });

        /* ── Mobile menu ── */
        const burger   = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');
        const overlay  = document.getElementById('mobOverlay');
        const closeMenu = () => {
            burger.classList.remove('open');
            navLinks.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        };
        burger.addEventListener('click', () => {
            const open = burger.classList.toggle('open');
            navLinks.classList.toggle('open', open);
            overlay.classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        });
        overlay.addEventListener('click', closeMenu);
        navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

        /* ── Catalog filter ── */
        const filterBtns = document.querySelectorAll('.filter-btn');
        const progCards  = document.querySelectorAll('.prog-card');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const f = this.dataset.filter;
                progCards.forEach(c => {
                    const show = f === 'todos' || c.dataset.sede === f;
                    c.style.display = show ? '' : 'none';
                });
            });
        });

        /* ── Sede → filter link ── */
        document.querySelectorAll('.sede-card').forEach(card => {
            card.addEventListener('click', () => {
                const btn = document.querySelector(`.filter-btn[data-filter="${card.dataset.sede}"]`);
                if (btn) {
                    btn.click();
                    gsap.to(window, { scrollTo: { y: '#catalogo', offsetY: 90 }, duration: .9, ease: 'power2.inOut' });
                }
            });
        });

        /* ── CTA section ── */
        ScrollTrigger.create({
            trigger: '.cta-section', start: 'top 80%', once: true,
            onEnter: () => {
                gsap.fromTo('.cta-inner h2', { opacity: 0, y: 36 }, { opacity: 1, y: 0, duration: 1, ease: 'power3.out' });
                gsap.fromTo('.cta-inner p',  { opacity: 0, y: 22 }, { opacity: 1, y: 0, duration: .8, delay: .25, ease: 'power3.out' });
                gsap.fromTo('.cta-actions > *', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: .6, delay: .45, stagger: .12, ease: 'power3.out' });
            }
        });

        /* ── Footer ── */
        ScrollTrigger.create({
            trigger: 'footer', start: 'top 88%', once: true,
            onEnter: () => gsap.utils.toArray('.footer-col').forEach((c, i) =>
                gsap.fromTo(c, { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: .65, delay: i * .1, ease: 'power3.out' }))
        });

        /* ── Generic drag carousel ── */
        function buildCarousel(trackId, wrapId, prevId, nextId) {
            const track  = document.getElementById(trackId);
            const wrap   = document.getElementById(wrapId);
            const prev   = document.getElementById(prevId);
            const next   = document.getElementById(nextId);
            if (!track || !wrap || !prev || !next) return;

            let idx = 0, tx = 0, startX = 0, prevTx = 0, dragging = false;

            const cw = () => {
                if (!track.children.length) return 0;
                const s = getComputedStyle(track.children[0]);
                return track.children[0].offsetWidth + parseFloat(s.marginRight || 0) + parseFloat(s.marginLeft || 0);
            };
            const tw = () => { let w = 0; Array.from(track.children).forEach(c => { const s = getComputedStyle(c); w += c.offsetWidth + parseFloat(s.marginRight || 0) + parseFloat(s.marginLeft || 0); }); return w; };
            const maxTx   = () => Math.max(0, tw() - wrap.offsetWidth);
            const maxIdx  = () => Math.max(0, track.children.length - Math.floor(wrap.offsetWidth / (cw() || 1)));

            function update(animate = true) {
                idx = Math.max(0, Math.min(idx, maxIdx()));
                const max = maxTx();
                tx = idx >= maxIdx() ? -max : -idx * cw();
                tx = Math.max(-max, Math.min(0, tx));
                if (animate) gsap.to(track, { x: tx, duration: .5, ease: 'power2.out' });
                else gsap.set(track, { x: tx });
                gsap.to(prev, { opacity: idx <= 0 ? .3 : 1, duration: .3 });
                gsap.to(next, { opacity: idx >= maxIdx() ? .3 : 1, duration: .3 });
                prev.disabled = idx <= 0;
                next.disabled = idx >= maxIdx();
            }

            prev.addEventListener('click', () => { if (idx > 0) { idx--; update(); } });
            next.addEventListener('click', () => { if (idx < maxIdx()) { idx++; update(); } });

            const ds = e => { dragging = true; startX = e.touches ? e.touches[0].clientX : e.clientX; prevTx = tx; track.style.cursor = 'grabbing'; };
            const dm = e => { if (!dragging) return; const x = e.touches ? e.touches[0].clientX : e.clientX; tx = Math.max(-maxTx(), Math.min(0, prevTx + x - startX)); gsap.set(track, { x: tx }); };
            const de = e => {
                if (!dragging) return; dragging = false; track.style.cursor = '';
                const x = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
                const d = x - startX; const cwv = cw();
                if (Math.abs(d) > 50 && cwv > 0) { const sh = Math.ceil(Math.abs(d) / cwv); idx = d < 0 ? Math.min(maxIdx(), idx + sh) : Math.max(0, idx - sh); }
                update();
            };

            wrap.addEventListener('mousedown', ds);
            document.addEventListener('mousemove', dm);
            document.addEventListener('mouseup', de);
            wrap.addEventListener('touchstart', ds, { passive: true });
            document.addEventListener('touchmove', dm, { passive: true });
            document.addEventListener('touchend', de);
            track.addEventListener('selectstart', e => { if (dragging) e.preventDefault(); });

            let rt; window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(() => { idx = Math.min(idx, maxIdx()); update(false); }, 250); });
            setTimeout(() => update(false), 100);
        }

        buildCarousel('teamTrack', 'teamWrap', 'teamPrev', 'teamNext');
        buildCarousel('sedeTrack', 'sedeWrap', 'sedePrev', 'sedeNext');
    }
});
</script>
</body>
</html>
