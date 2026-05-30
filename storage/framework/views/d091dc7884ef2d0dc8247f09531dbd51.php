<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.signin'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

    <body>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('css'); ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap"
            rel="stylesheet">
        <style>
            :root {
                --flame: #fc7b04;
                --flame-light: #ff9a3c;
                --flame-dark: #e86e00;
                --amber-glow: #c8902a;
                --deep-warmth: #1a0d05;
                --midnight: #0a0604;
                --surface-dark: rgba(26, 13, 5, 0.6);
                --surface-subtle: rgba(252, 123, 4, 0.04);
                --border-subtle: rgba(252, 123, 4, 0.08);
                --border-active: rgba(252, 123, 4, 0.25);
                --text-primary: #fff8f0;
                --text-secondary: rgba(255, 248, 240, 0.55);
                --text-muted: rgba(255, 248, 240, 0.35);
                --success: #22c55e;
                --error: #ef4444;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'DM Sans', sans-serif;
                background: var(--midnight);
                overflow-x: hidden;
                min-height: 100vh;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .login-viewport {
                min-height: 100vh;
                display: flex;
                position: relative;
                background: var(--midnight);
            }

            /* === AMBIENT BACKGROUND === */
            .ambient-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                pointer-events: none;
                overflow: hidden;
            }

            .ambient-bg::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: 
                    radial-gradient(ellipse 80% 50% at 20% 40%, rgba(252, 123, 4, 0.06) 0%, transparent 50%),
                    radial-gradient(ellipse 60% 40% at 80% 60%, rgba(200, 144, 42, 0.04) 0%, transparent 50%),
                    radial-gradient(ellipse 100% 60% at 50% 100%, rgba(232, 110, 0, 0.03) 0%, transparent 40%);
            }

            .grid-lines {
                position: absolute;
                inset: 0;
                background-image: 
                    linear-gradient(rgba(252, 123, 4, 0.015) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(252, 123, 4, 0.015) 1px, transparent 1px);
                background-size: 60px 60px;
                mask-image: radial-gradient(ellipse 80% 60% at 50% 50%, black 30%, transparent 70%);
            }

            /* === LEFT PANEL - BRANDING === */
            .brand-panel {
                flex: 1;
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: clamp(2.5rem, 5vw, 5rem);
                overflow: hidden;
                z-index: 1;
                background: linear-gradient(135deg, var(--deep-warmth) 0%, var(--midnight) 100%);
            }

            .brand-panel::before {
                content: '';
                position: absolute;
                inset: 0;
                background: 
                    radial-gradient(ellipse 700px 500px at 25% 35%, rgba(252, 123, 4, 0.07) 0%, transparent 65%),
                    radial-gradient(ellipse 400px 300px at 75% 75%, rgba(200, 144, 42, 0.04) 0%, transparent 60%);
                pointer-events: none;
            }

            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(60px);
                opacity: 0.15;
                animation: orbFloat 20s ease-in-out infinite;
            }

            .orb-1 {
                width: 400px;
                height: 400px;
                top: -10%;
                right: -5%;
                background: var(--flame);
                animation-delay: 0s;
            }

            .orb-2 {
                width: 250px;
                height: 250px;
                bottom: 10%;
                left: 5%;
                background: var(--amber-glow);
                animation-delay: -8s;
            }

            .orb-3 {
                width: 180px;
                height: 180px;
                top: 45%;
                right: 20%;
                background: var(--flame-dark);
                animation-delay: -15s;
                opacity: 0.08;
            }

            @keyframes orbFloat {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(20px, -15px) scale(1.05); }
                66% { transform: translate(-10px, 10px) scale(0.98); }
            }

            .brand-content {
                position: relative;
                z-index: 2;
                max-width: 520px;
            }

            .brand-logo {
                display: inline-flex;
                align-items: center;
                gap: 0.85rem;
                margin-bottom: 2.8rem;
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
            }

            .logo-mark {
                width: 44px;
                height: 44px;
                background: linear-gradient(145deg, var(--flame), var(--flame-dark));
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 20px rgba(252, 123, 4, 0.3);
            }

            .logo-mark svg {
                width: 24px;
                height: 24px;
                fill: var(--text-primary);
            }

            .brand-logo-text {
                font-family: 'Playfair Display', serif;
                font-size: 1.4rem;
                font-weight: 600;
                color: var(--text-primary);
                letter-spacing: -0.02em;
            }

            .brand-headline {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2.4rem, 4vw, 3.4rem);
                font-weight: 500;
                line-height: 1.15;
                color: var(--text-primary);
                margin-bottom: 1.4rem;
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.1s both;
            }

            .brand-headline em {
                font-style: italic;
                color: var(--flame-light);
                font-weight: 600;
            }

            .brand-description {
                font-size: 1.05rem;
                line-height: 1.8;
                color: var(--text-secondary);
                font-weight: 400;
                max-width: 440px;
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.2s both;
            }

            .brand-stats {
                display: flex;
                gap: 3rem;
                margin-top: 3rem;
                padding-top: 2rem;
                border-top: 1px solid var(--border-subtle);
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both;
            }

            .stat-item {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .stat-number {
                font-family: 'Playfair Display', serif;
                font-size: 1.75rem;
                font-weight: 600;
                color: var(--flame-light);
                line-height: 1;
            }

            .stat-label {
                font-size: 0.72rem;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.12em;
                font-weight: 500;
            }

            .trust-badge {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                margin-top: 2rem;
                padding: 0.85rem 1.25rem;
                background: var(--surface-subtle);
                border: 1px solid var(--border-subtle);
                border-radius: 50px;
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.4s both;
                backdrop-filter: blur(8px);
            }

            .trust-badge svg {
                width: 18px;
                height: 18px;
                fill: var(--flame);
            }

            .trust-badge span {
                font-size: 0.8rem;
                color: var(--text-secondary);
                font-weight: 400;
            }

            /* === RIGHT PANEL - LOGIN FORM === */
            .login-panel {
                width: 540px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: clamp(2rem, 4vw, 4rem);
                position: relative;
                background: linear-gradient(180deg, var(--midnight) 0%, var(--deep-warmth) 100%);
                z-index: 1;
            }

            .login-panel::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 1px;
                height: 100%;
                background: linear-gradient(180deg, transparent 0%, var(--flame) 30%, var(--flame) 70%, transparent 100%);
                opacity: 0.15;
            }

            .login-panel::after {
                content: '';
                position: absolute;
                top: 20%;
                right: 0;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(252, 123, 4, 0.04) 0%, transparent 70%);
                pointer-events: none;
            }

            .login-container {
                width: 100%;
                max-width: 420px;
                animation: fadeSlideUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both;
            }

            .login-header {
                margin-bottom: 2.5rem;
            }

            .login-greeting {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.15em;
                color: var(--flame);
                font-weight: 600;
                margin-bottom: 0.75rem;
            }

            .login-greeting::before {
                content: '';
                width: 20px;
                height: 2px;
                background: var(--flame);
                border-radius: 2px;
            }

            .login-title {
                font-family: 'Playfair Display', serif;
                font-size: clamp(2rem, 3vw, 2.5rem);
                font-weight: 500;
                color: var(--text-primary);
                line-height: 1.12;
                margin-bottom: 0.5rem;
            }

            .login-subtitle {
                font-size: 0.95rem;
                color: var(--text-muted);
                font-weight: 400;
                line-height: 1.6;
            }

            /* Form Styles */
            .auth-form {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .form-field {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .form-label-custom {
                font-size: 0.78rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--text-secondary);
                font-weight: 500;
            }

            .form-label-custom .required {
                color: var(--flame);
                margin-left: 2px;
            }

            .input-wrapper {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 1.1rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--flame);
                font-size: 1.1rem;
                pointer-events: none;
                transition: all 0.3s ease;
                z-index: 2;
                opacity: 0.5;
            }

            .form-input-custom {
                width: 100%;
                padding: 1rem 1.1rem 1rem 3rem;
                background: var(--surface-dark);
                border: 1px solid var(--border-subtle);
                border-radius: 12px;
                color: var(--text-primary);
                font-family: 'DM Sans', sans-serif;
                font-size: 0.95rem;
                font-weight: 400;
                transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
                outline: none;
                -webkit-appearance: none;
            }

            .form-input-custom::placeholder {
                color: var(--text-muted);
            }

            .form-input-custom:hover {
                border-color: var(--border-active);
                background: rgba(26, 13, 5, 0.75);
            }

            .form-input-custom:focus {
                border-color: var(--flame);
                background: rgba(26, 13, 5, 0.85);
                box-shadow: 0 0 0 4px rgba(252, 123, 4, 0.08), 0 4px 20px rgba(252, 123, 4, 0.06);
            }

            .form-input-custom:focus ~ .input-icon,
            .form-input-custom:focus + .input-icon {
                color: var(--flame);
                opacity: 1;
            }

            .form-input-custom.is-invalid {
                border-color: rgba(239, 68, 68, 0.5);
                background: rgba(239, 68, 68, 0.03);
            }

            .form-input-custom.is-invalid:focus {
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.06);
            }

            .password-toggle {
                position: absolute;
                right: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: var(--text-muted);
                cursor: pointer;
                padding: 0.4rem;
                font-size: 1.1rem;
                transition: all 0.25s ease;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
            }

            .password-toggle:hover {
                color: var(--flame);
                background: var(--surface-subtle);
            }

            .form-input-password {
                padding-right: 3rem;
            }

            .invalid-feedback {
                font-size: 0.8rem;
                color: var(--error);
                margin-top: 0.25rem;
                padding-left: 0.2rem;
                display: flex;
                align-items: center;
                gap: 0.35rem;
            }

            .invalid-feedback::before {
                content: '!';
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 16px;
                height: 16px;
                background: var(--error);
                color: white;
                border-radius: 50%;
                font-size: 0.65rem;
                font-weight: 600;
            }

            .form-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 0.35rem;
            }

            .remember-me {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                cursor: pointer;
                user-select: none;
            }

            .remember-me input[type="checkbox"] {
                appearance: none;
                -webkit-appearance: none;
                width: 18px;
                height: 18px;
                min-width: 18px;
                border: 1.5px solid var(--border-active);
                border-radius: 5px;
                background: var(--surface-dark);
                cursor: pointer;
                position: relative;
                transition: all 0.25s ease;
            }

            .remember-me input[type="checkbox"]:hover {
                border-color: var(--flame);
            }

            .remember-me input[type="checkbox"]:checked {
                background: var(--flame);
                border-color: var(--flame);
            }

            .remember-me input[type="checkbox"]:checked::after {
                content: '';
                position: absolute;
                left: 5px;
                top: 2px;
                width: 5px;
                height: 9px;
                border: solid var(--midnight);
                border-width: 0 2px 2px 0;
                transform: rotate(45deg);
            }

            .remember-me span {
                font-size: 0.85rem;
                color: var(--text-secondary);
                font-weight: 400;
            }

            .forgot-link {
                font-size: 0.85rem;
                color: var(--flame);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.2s ease;
                white-space: nowrap;
                position: relative;
            }

            .forgot-link::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 1px;
                background: var(--flame);
                transition: width 0.3s ease;
            }

            .forgot-link:hover {
                color: var(--flame-light);
            }

            .forgot-link:hover::after {
                width: 100%;
            }

            .submit-btn {
                width: 100%;
                padding: 1rem 1.75rem;
                background: linear-gradient(135deg, var(--flame) 0%, var(--flame-dark) 100%);
                border: none;
                border-radius: 12px;
                color: var(--text-primary);
                font-family: 'DM Sans', sans-serif;
                font-size: 0.95rem;
                font-weight: 600;
                letter-spacing: 0.03em;
                cursor: pointer;
                transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
                position: relative;
                overflow: hidden;
                margin-top: 0.5rem;
                box-shadow: 0 4px 20px rgba(252, 123, 4, 0.25);
            }

            .submit-btn::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, var(--flame-light), var(--flame));
                opacity: 0;
                transition: opacity 0.4s ease;
            }

            .submit-btn::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
                transition: left 0.6s ease;
            }

            .submit-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(252, 123, 4, 0.35), 0 4px 12px rgba(252, 123, 4, 0.2);
            }

            .submit-btn:hover::before {
                opacity: 1;
            }

            .submit-btn:hover::after {
                left: 100%;
            }

            .submit-btn span {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                width: 100%;
                transition: all 0.3s ease;
            }

            .submit-btn:active {
                transform: translateY(0);
                box-shadow: 0 2px 10px rgba(252, 123, 4, 0.2);
            }

            .submit-btn svg {
                width: 18px;
                height: 18px;
                fill: currentColor;
                transition: transform 0.3s ease;
            }

            .submit-btn:hover svg {
                transform: translateX(3px);
            }

            /* Divider */
            .divider {
                display: flex;
                align-items: center;
                gap: 1.2rem;
                margin: 0.5rem 0;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--border-subtle), transparent);
            }

            .divider span {
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: var(--text-muted);
                white-space: nowrap;
            }

            /* Social buttons */
            .social-buttons {
                display: flex;
                gap: 0.75rem;
                justify-content: center;
            }

            .social-btn {
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--surface-dark);
                border: 1px solid var(--border-subtle);
                border-radius: 12px;
                color: var(--text-muted);
                font-size: 1.15rem;
                cursor: pointer;
                transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
                text-decoration: none;
            }

            .social-btn:hover {
                border-color: var(--flame);
                color: var(--flame);
                background: var(--surface-subtle);
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(252, 123, 4, 0.12);
            }

            .social-btn:active {
                transform: translateY(0);
            }

            /* Register link */
            .register-link {
                text-align: center;
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid var(--border-subtle);
                font-size: 0.9rem;
                color: var(--text-muted);
            }

            .register-link a {
                color: var(--flame);
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
                position: relative;
            }

            .register-link a::after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 0;
                height: 1px;
                background: var(--flame);
                transition: width 0.3s ease;
            }

            .register-link a:hover {
                color: var(--flame-light);
            }

            .register-link a:hover::after {
                width: 100%;
            }

            /* Animations */
            @keyframes fadeSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Subtle grain overlay */
            .grain-overlay {
                position: fixed;
                inset: 0;
                pointer-events: none;
                z-index: 1000;
                opacity: 0.025;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
                background-repeat: repeat;
                background-size: 200px 200px;
            }

            /* === RESPONSIVE === */
            @media (max-width: 1200px) {
                .login-panel {
                    width: 500px;
                }
            }

            @media (max-width: 1024px) {
                .brand-panel {
                    display: none;
                }

                .login-panel {
                    width: 100%;
                    border-left: none;
                    background: radial-gradient(ellipse 100% 80% at 50% 0%, rgba(252, 123, 4, 0.05) 0%, var(--midnight) 60%);
                }

                .login-panel::before {
                    display: none;
                }

                .login-panel::after {
                    display: none;
                }
            }

            @media (max-width: 640px) {
                .login-panel {
                    padding: 2rem 1.5rem;
                    min-height: 100dvh;
                    align-items: flex-start;
                    padding-top: 3rem;
                }

                .login-container {
                    max-width: 100%;
                }

                .login-header {
                    margin-bottom: 2rem;
                }

                .login-greeting {
                    font-size: 0.7rem;
                }

                .login-title {
                    font-size: 1.75rem;
                }

                .login-subtitle {
                    font-size: 0.9rem;
                }

                .auth-form {
                    gap: 1.25rem;
                }

                .form-input-custom {
                    padding: 0.9rem 1rem 0.9rem 2.75rem;
                    font-size: 0.9rem;
                }

                .form-options {
                    flex-direction: column;
                    gap: 0.75rem;
                    align-items: flex-start;
                }

                .social-btn {
                    width: 46px;
                    height: 46px;
                }

                .register-link {
                    margin-top: 1.75rem;
                    padding-top: 1.25rem;
                }

                .submit-btn {
                    padding: 0.95rem 1.5rem;
                }
            }

            @media (max-width: 380px) {
                .login-panel {
                    padding: 1.75rem 1.25rem;
                    padding-top: 2.5rem;
                }

                .social-buttons {
                    gap: 0.6rem;
                }

                .social-btn {
                    width: 44px;
                    height: 44px;
                    font-size: 1rem;
                }
            }

            /* Landscape phones */
            @media (max-height: 650px) and (orientation: landscape) {
                .login-panel {
                    padding: 1.5rem 2rem;
                    align-items: center;
                }

                .login-header {
                    margin-bottom: 1.25rem;
                }

                .login-title {
                    font-size: 1.6rem;
                }

                .auth-form {
                    gap: 1rem;
                }
            }

            /* Focus visible for accessibility */
            @media (prefers-reduced-motion: reduce) {
                *,
                *::before,
                *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }

            .form-input-custom:focus-visible,
            .submit-btn:focus-visible,
            .social-btn:focus-visible,
            .forgot-link:focus-visible,
            .register-link a:focus-visible {
                outline: 2px solid var(--flame);
                outline-offset: 2px;
            }

            /* Smooth scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: var(--midnight);
            }

            ::-webkit-scrollbar-thumb {
                background: var(--border-subtle);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--border-active);
            }
        </style>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>
        <div class="ambient-bg">
            <div class="grid-lines"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
        <div class="grain-overlay"></div>

        <div class="login-viewport">
            <!-- Left Brand Panel -->
            <div class="brand-panel">
                <div class="brand-content">
                    <div class="brand-logo">
                        <div class="logo-mark">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="brand-logo-text">Innova Ciencia</span>
                    </div>

                    <h1 class="brand-headline">
                        Donde la <em>innovación</em> impulsa el conocimiento
                    </h1>

                    <p class="brand-description">
                        Plataforma integral para la gestión académica y científica. Conectando investigadores, estudiantes e
                        instituciones en un ecosistema de excelencia educativa.
                    </p>

                    <div class="brand-stats">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Investigadores</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">120+</span>
                            <span class="stat-label">Instituciones</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Programas</span>
                        </div>
                    </div>

                    <div class="trust-badge">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                        </svg>
                        <span>Plataforma segura y confiable</span>
                    </div>
                </div>
            </div>

            <!-- Right Login Panel -->
            <div class="login-panel">
                <div class="login-container">
                    <div class="login-header">
                        <p class="login-greeting">Bienvenido de nuevo</p>
                        <h2 class="login-title">Iniciar Sesión</h2>
                        <p class="login-subtitle">Ingresa tus credenciales para acceder a tu cuenta</p>
                    </div>

                    <form action="<?php echo e(route('login')); ?>" method="POST" class="auth-form">
                        <?php echo csrf_field(); ?>

                        <div class="form-field">
                            <label for="username" class="form-label-custom">
                                Usuario o correo <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="text" class="form-input-custom <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('email', 'admin@innova.com')); ?>" id="username" name="email"
                                    placeholder="usuario o tu@correo.com" autocomplete="username">
                                <i class="ri-user-line input-icon"></i>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-field">
                            <label for="password-input" class="form-label-custom">
                                Contraseña <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password"
                                    class="form-input-custom form-input-password <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="password" placeholder="Tu contraseña" id="password-input" value="admin123"
                                    autocomplete="current-password">
                                <i class="ri-lock-line input-icon"></i>
                                <button type="button" class="password-toggle" id="password-addon"
                                    aria-label="Mostrar contraseña">
                                    <i class="ri-eye-fill"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox" id="auth-remember-check">
                                <span>Recordarme</span>
                            </label>
                            <a href="<?php echo e(route('password.update')); ?>" class="forgot-link">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="submit-btn">
                            <span>
                                Continuar
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z" />
                                </svg>
                            </span>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const orbs = document.querySelectorAll('.orb');
                let ticking = false;

                document.addEventListener('mousemove', function(e) {
                    if (ticking) return;
                    ticking = true;

                    requestAnimationFrame(function() {
                        const x = (e.clientX / window.innerWidth - 0.5) * 2;
                        const y = (e.clientY / window.innerHeight - 0.5) * 2;

                        orbs.forEach(function(el, i) {
                            const speed = (i + 1) * 15;
                            const baseOpacity = 0.15 - (i * 0.03);
                            el.style.transform = 'translate(' + (x * speed * 0.3) + 'px, ' + (y * speed * 0.3) + 'px)';
                            el.style.opacity = baseOpacity + (Math.abs(x) * 0.05);
                        });

                        ticking = false;
                    });
                });

                const inputs = document.querySelectorAll('.form-input-custom');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.querySelector('.input-icon')?.classList.add('active');
                    });
                    input.addEventListener('blur', function() {
                        if (!this.value) {
                            this.parentElement.querySelector('.input-icon')?.classList.remove('active');
                        }
                    });
                });

                // Password toggle
                const toggleBtn = document.getElementById('password-addon');
                const passwordInput = document.getElementById('password-input');
                if (toggleBtn && passwordInput) {
                    toggleBtn.addEventListener('click', function() {
                        const isPassword = passwordInput.type === 'password';
                        passwordInput.type = isPassword ? 'text' : 'password';
                        this.querySelector('i').className = isPassword ? 'ri-eye-off-fill' : 'ri-eye-fill';
                        this.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    });
                }
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/auth/login.blade.php ENDPATH**/ ?>