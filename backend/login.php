<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Empresa - AquaReport</title>
    <link rel="stylesheet" href="../frontend/css/index.css"> 
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            transition: opacity 0.5s ease;
            background: #071b34;
            overflow-x: hidden;
            opacity: 0;
        }
        
        a {
            transition: all 0.3s ease;
        }

        .menu-btn {
            display: none; 
            background: none;
            border: none;
            color: white !important;
            font-size: 1.7rem;
            cursor: pointer;
            padding: 10px;
            transition: transform 0.4s ease !important, color 0.4s ease !important;
        }

        .menu-active-rotate {
            transform: rotate(90deg) !important;
            color: #83EAF1 !important;
        }

        @media (max-width: 768px) {
            .menu-btn { 
                display: flex !important; 
                margin-top: 20px;
            }

            .nav-links {
                display: none; 
                flex-direction: column;
                position: absolute;
                top: 80px;
                right: 0;
                width: 40%;
                background: rgba(7, 27, 52, 0.98);
                padding: 20px;
                text-align: center;
                border-bottom: 1px solid rgba(111, 197, 255, 0.2);
            }
            .nav-links.active { display: flex !important; }
        }

        .login-hero-container {
            position: relative; 
            width: 100%; 
            min-height: 100vh;
            display: flex; 
            align-items: center; 
            justify-content: center;
            padding: 20px;
        }

        .fondo {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover; 
            filter: brightness(0.5); 
            z-index: -1;
        }
        .login-card {
            background: rgba(7, 27, 52, 0.7);
            backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 50px;
            border: 1px solid rgba(111, 197, 255, 0.3);
            width: 100%;
            max-width: 450px;
            text-align: center;
            color: white;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            line-height: 1.5;
            margin-top: 100px;
        }

        input {
            width: 100%; 
            padding: 18px; 
            margin: 12px 0;
            border-radius: 15px; 
            border: 1px solid rgba(111, 197, 255, 0.3);
            background: rgba(255,255,255,0.05); 
            color: white; 
            outline: none;
            font-size: 1rem;
        }

        button[type="submit"] {
            width: 100%; 
            padding: 15px; 
            border-radius: 15px;
            background: linear-gradient(45deg, #63A4FF, #83EAF1);
            border: none; color: #071b34; 
            font-weight: bold;
            cursor: pointer; 
            margin-top: 15px; 
            transition: 0.3s;
        }

        button:hover { 
            filter: brightness(1.2); 
        }

        .titulo-gradient {
            background: linear-gradient(90deg, #63A4FF, #83EAF1, #63A4FF);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: desplazamiento 6s linear infinite;
            font-size: clamp(1.5rem, 5vw, 2.2rem);
            font-weight: 800; 
            margin-bottom: 25px; 
            line-height: 1.2;
        }

        @keyframes desplazamiento {
            0% { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        .login-options {
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            margin: 15px 0; 
            font-size: 0.85rem; 
            color: white; 
            gap: 10px;
        }

        .checkbox-container { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            cursor: pointer; 
            white-space: nowrap; }

        .forgot-pass { 
            color: #83EAF1; 
            text-decoration: none; 
            transition: 0.3s; 
            white-space: nowrap; 
        }
        
        .caps-warning {
            background: rgba(111, 197, 255, 0.1); 
            border: 1px solid rgba(111, 197, 255, 0.2);
            padding: 10px; 
            border-radius: 12px; 
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8); 
            margin-bottom: 15px;
            display: flex; 
            align-items: center; 
            gap: 8px; 
            text-align: left;
        }

        .security-footer {
            display: flex; 
            justify-content: center; 
            flex-wrap: wrap;
            gap: 15px; 
            margin-top: 25px; 
            padding-top: 20px;
            border-top: 1px solid rgba(111, 197, 255, 0.2);
            color: rgba(255, 255, 255, 0.6); 
            font-size: 0.75rem;
        }

        @media (max-width: 400px) {
            .login-options { flex-direction: column; align-items: flex-start; }
            .login-card { padding: 25px; }
        }

        .customCheckBox { 
            height: 40px; 
            padding: 0 20px; 
            font-size: 0.9rem; }

        @media (max-width: 480px) {
        .login-card {
            padding: 25px; 
            max-width: 95%; 
        }
        input {
            padding: 12px; 
            font-size: 0.9rem;
        }
        .customCheckBox {
            height: 30px;
            font-size: 0.75rem;
            padding: 0 10px;
        }
        .titulo-gradient { font-size: 1.6rem !important; }
    }

        .customCheckBox {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            padding: 0 15px;
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(159, 214, 255, 0.3); 
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.8rem;
        }

        .customCheckBox:hover {
            background-color: rgba(159, 214, 255, 0.05);
            border-color: #9fd6ff;
            color: white;
        }
        .customCheckBoxInput:checked + .customCheckBoxWrapper .customCheckBox {
            background-color: #9fd6ff; 
            color: #071b34; 
            font-weight: 700;
            border-color: #9fd6ff;
        }

        .customCheckBoxInput { 
            display: none; 
        }

        .container-loader {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader {
            position: relative;
            width: 200px;
            height: 200px;
            perspective: 800px;
        }

        .crystal {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 60px;
            height: 60px;
            opacity: 0;
            transform-origin: bottom center;
            transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
            animation: spin 4s linear infinite, emerge 2s ease-in-out infinite alternate,
                fadeIn 0.3s ease-out forwards;
            border-radius: 10px;
            visibility: hidden;
        }

        @keyframes spin {
            from {
                transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
            }
            to {
                transform: translate(-50%, -50%) rotateX(45deg) rotateZ(360deg);
            }
        }

        @keyframes emerge {
            0%,
            100% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0;
            }
            50% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            to {
                visibility: visible;
                opacity: 0.8;
            }
        }

        .crystal:nth-child(1) {
            background: linear-gradient(45deg, #003366, #336699);
            animation-delay: 0s;
        }

        .crystal:nth-child(2) {
            background: linear-gradient(45deg, #003399, #3366cc);
            animation-delay: 0.3s;
        }

        .crystal:nth-child(3) {
            background: linear-gradient(45deg, #0066cc, #3399ff);
            animation-delay: 0.6s;
        }

        .crystal:nth-child(4) {
            background: linear-gradient(45deg, #0099ff, #66ccff);
            animation-delay: 0.9s;
        }

        .crystal:nth-child(5) {
            background: linear-gradient(45deg, #33ccff, #99ccff);
            animation-delay: 1.2s;
        }

        .crystal:nth-child(6) {
            background: linear-gradient(45deg, #66ffff, #ccffff);
            animation-delay: 1.5s;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(7, 27, 52, 0.6); 
            backdrop-filter: blur(10px);             
            -webkit-backdrop-filter: blur(10px);
            display: none;                           
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader-box {
            background: rgba(7, 27, 52, 0.9);
            padding: 25px 30px;
            border-radius: 24px;
            border: 1px solid rgba(111, 197, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            text-align: center;
            max-width: 250px;
        }

        .loader-text {
            color: #33ccff;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .container-loader .loader {
            margin: 20px 0;
        }

    </style>
</head>
<body>
    <div class="modal-overlay" id="loader-modal">
    <div class="loader-box">
        <div class="container-loader"> 
            <div class="loader">
                <div class="crystal"></div>
                <div class="crystal"></div>
                <div class="crystal"></div>
                <div class="crystal"></div>
                <div class="crystal"></div>
                <div class="crystal"></div>
            </div>
        </div>
        <p class="loader-text" id="loader-message">Iniciando sesión...</p>
    </div>
</div>
    <nav>
        <div class="nav-container">
            <img src="../frontend/img/logo.png" alt="Logo" class="logo">
            <button class="menu-btn" id="menuBtn">☰</button>
            <ul class="nav-links" id="navLinks">
                <li><a href="../frontend/index.html">Volver al inicio</a></li>
                <li><a href="mailto:olguinzavalaadrian099@gmail.com">Contactanos</a></li>
            </ul>
        </div>
    </nav>
    <div class="login-hero-container">
        <img src="../frontend/img/fondo2.png" alt="Fondo" class="fondo">
        <div class="login-card">
            <h2 class="titulo-gradient">Acceso Empresa</h2>
            <form action="procesar_login.php" method="POST" id="login-form">
                <input type="text" name="usuario" placeholder="Nombre de usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <div class="login-options">
                <div class="customCheckBoxHolder">
                    <input type="checkbox" id="cCB1" class="customCheckBoxInput">
                    <label for="cCB1" class="customCheckBoxWrapper">
                        <div class="customCheckBox">
                            <div class="inner">Recordar contraseña</div>
                        </div>
                    </label>
                </div>
                <a href="mailto:olguinzavalaadrian099@gmail.com" class="forgot-pass">Restablecer Contraseña</a>
                </div>
                <div class="caps-warning">
                    <span>ⓘ</span> Asegúrate de que 'Mayúsculas' esté desactivado.
                </div>
                <button type="submit">Iniciar Sesión</button>
                <div class="security-footer">
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Seguro
                </div>
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Privado
                </div>
                <div class="security-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Verificado
                </div>
            </div>
            </form>
        </div>
    </div>
<script>
const menuBtn = document.getElementById('menuBtn');
        const navLinks = document.getElementById('navLinks');
        window.addEventListener('pageshow', () => { document.body.style.opacity = "1"; });
        if (menuBtn && navLinks) {
            menuBtn.addEventListener("click", () => {
                const isActive = navLinks.classList.toggle("active");
                menuBtn.classList.toggle("menu-active-rotate", isActive);
                
                setTimeout(() => {
                    menuBtn.textContent = isActive ? "×" : "☰";
                }, 150);
            });
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', (e) => {
                    navLinks.classList.remove("active");
                    menuBtn.classList.remove("menu-active-rotate");
                    menuBtn.textContent = "☰";
                });
            });
        }
    const loginForm = document.getElementById('login-form');
    const loaderModal = document.getElementById('loader-modal');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            loaderModal.style.display = 'flex';
        });
    }
</script>
</body>
</html>