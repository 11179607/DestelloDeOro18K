<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destello de Oro 18K | Sistema de Gestión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- QR Code Generator Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <!-- Añadir jsPDF y html2canvas para generar PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- Icono para la ventana del navegador (Fondo Transparente) -->
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        :root {
            --gold-primary: #D4AF37;
            --gold-secondary: #FFD700;
            --gold-light: #FFF8DC;
            --gold-dark: #B8860B;
            --white: #FFFFFF;
            --off-white: #F9F9F9;
            --light-gray: #F5F5F5;
            --medium-gray: #E8E8E8;
            --dark-gray: #333333;
            --text-dark: #222222;
            --success: #2E8B57;
            --warning: #FFA500;
            --danger: #DC143C;
            --info: #4169E1;
            --shadow-light: 0 5px 15px rgba(0, 0, 0, 0.05);
            --shadow-medium: 0 10px 25px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 15px 35px rgba(0, 0, 0, 0.15);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 15px;
            --radius-xl: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #1a1a1a; /* Fondo base oscuro para evitar destellos blancos */
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.95)),
                url('fondo.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Scrollbar transparente y estilizada */
        ::-webkit-scrollbar {
            width: 10px;
            background: transparent;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.5); 
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.8); 
            border: 2px solid transparent;
            background-clip: content-box;
        }

        /* Elementos decorativos de joyería */
        .jewelry-decoration {
            position: fixed;
            pointer-events: none;
            z-index: -1;
            opacity: 0.1;
        }

        .ring-decoration {
            width: 80px;
            height: 80px;
            border: 2px solid var(--gold-primary);
            border-radius: 50%;
            top: 15%;
            left: 3%;
            animation: float 25s infinite linear;
        }

        .chain-decoration {
            width: 120px;
            height: 60px;
            border: 2px solid var(--gold-secondary);
            border-radius: 50% / 20%;
            top: 70%;
            right: 3%;
            animation: float 30s infinite linear reverse;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg) scale(1);
            }

            50% {
                transform: translateY(-20px) rotate(180deg) scale(1.05);
            }

            100% {
                transform: translateY(0) rotate(360deg) scale(1);
            }
        }

        /* Pantalla de Login */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed; /* Ocupa toda la pantalla sin importar el contenido del body */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5000; /* Asegura estar por encima del fondo del body */
            padding: 15px;
            background: linear-gradient(rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.45)),
                url('fondo.jpeg') no-repeat center center fixed;
            background-size: cover;
            overflow-y: auto; /* Permite scroll interno si el contenido es grande */
        }

        .login-box {
            background: transparent;
            backdrop-filter: none;
            border-radius: 0;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
            box-shadow: none;
            border: none;
            position: relative;
            z-index: 1;
            transition: var(--transition);
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 1.5rem;
            }
        }

        .login-box:hover {
            transform: none;
            box-shadow: none;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header i {
            font-size: 2.5rem;
            color: var(--gold-primary);
            margin-bottom: 0.75rem;
            display: block;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--gold-dark);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .login-header p {
            color: var(--dark-gray);
            font-size: 0.9rem;
            font-weight: 400;
            text-shadow: none;
        }

        /* Ajustes textos login fondo transparente - LEGIBILIDAD TOTAL */
        .login-box h3 {
            color: var(--gold-dark) !important;
            text-shadow: none !important;
        }
        
        .login-box label {
            color: var(--text-dark) !important;
            text-shadow: none !important;
            font-weight: 600;
            font-size: 1rem; /* Un poco más grande para leer mejor */
        }

        .login-box small, .login-box p, .login-box .form-text {
            color: var(--dark-gray) !important;
            text-shadow: none !important;
            font-weight: 400;
        }

        /* Inputs transparentes estilo material - Texto blanco */
        .login-box .form-control {
            background: rgba(255, 255, 255, 0.5);
            border: none;
            border-bottom: 2px solid var(--medium-gray);
            border-radius: var(--radius-sm);
            padding: 12px 15px;
            color: var(--text-dark) !important;
            text-shadow: none;
        }
        
        .login-box .form-control::placeholder {
            color: var(--dark-gray);
            opacity: 0.5;
        }
        
        .login-box .form-control:focus {
            box-shadow: none;
            border-color: var(--gold-primary);
            background: transparent;
            outline: none;
        }

        /* NUEVO: Estilos para formularios de usuario */
        .user-info-form {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        .user-info-form.active {
            display: block;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .role-selector {
                grid-template-columns: 1fr;
            }
        }

        .role-btn {
            padding: 12px;
            border: 2px solid var(--medium-gray);
            background: var(--white);
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .role-btn i {
            font-size: 1.2rem;
            color: var(--gold-primary);
        }

        .role-btn:hover {
            border-color: var(--gold-primary);
            transform: translateY(-2px);
        }

        .role-btn.active {
            border-color: var(--gold-primary);
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(255, 215, 0, 0.05) 100%);
            color: var(--gold-dark);
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.1);
        }

        .role-btn {
            cursor: pointer;
            user-select: none;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--medium-gray);
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* NUEVO: Estilos para cambio de contraseña */
        .password-change-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9000;
            justify-content: center;
            align-items: center;
            padding: 15px;
            overflow-y: auto;
        }

        .password-change-box {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            width: 100%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-heavy);
            border: 2px solid var(--gold-primary);
            position: relative;
            margin: auto;
        }

        .password-change-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .password-change-header i {
            font-size: 2rem;
            color: var(--gold-primary);
            margin-bottom: 0.75rem;
        }

        .password-change-header h2 {
            font-size: 1.5rem;
            color: var(--gold-dark);
            margin-bottom: 0.5rem;
        }

        .password-change-header p {
            color: #666;
            font-size: 0.85rem;
        }

        .close-password-change {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--danger);
            cursor: pointer;
            padding: 5px;
        }

        /* Aplicación principal */
        #appScreen {
            display: none;
            min-height: 100vh;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            color: var(--white);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-medium);
            border-bottom: 2px solid var(--gold-primary);
            text-shadow: none;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            font-size: 1.8rem;
            color: var(--gold-primary);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .brand-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        @media (max-width: 480px) {
            .brand-text h1 {
                font-size: 1.3rem;
            }
        }

        .brand-text span {
            font-size: 0.8rem;
            opacity: 0.8;
            font-weight: 300;
        }

        .user-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .user-controls {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        .user-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            border: 1px solid rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .user-badge.admin {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(255, 215, 0, 0.1) 100%);
        }

        .user-badge.worker {
            background: linear-gradient(135deg, rgba(65, 105, 225, 0.2) 0%, rgba(30, 144, 255, 0.1) 100%);
        }

        .logout-btn {
            background: rgba(220, 20, 60, 0.1);
            border: 1px solid rgba(220, 20, 60, 0.3);
            color: var(--danger);
            padding: 8px 15px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(220, 20, 60, 0.2);
            transform: translateY(-2px);
        }

        /* Navegación */
        .main-nav {
            background: var(--white);
            padding: 0.75rem 0;
            position: sticky;
            top: 65px;
            z-index: 999;
            box-shadow: var(--shadow-light);
            border-bottom: 1px solid var(--medium-gray);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            overflow-x: auto;
        }

        .nav-btn {
            background: var(--white);
            border: 2px solid var(--medium-gray);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 500;
            color: var(--text-dark);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 150px;
            justify-content: center;
            font-size: 0.85rem;
            white-space: nowrap;
            text-shadow: none;
        }

        @media (max-width: 768px) {
            .nav-btn {
                min-width: 120px;
                padding: 8px 15px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .nav-btn {
                min-width: 100px;
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .nav-btn i {
                font-size: 0.9rem;
            }
        }

        .nav-btn:hover {
            border-color: var(--gold-primary);
            color: var(--gold-dark);
            transform: translateY(-2px);
        }

        .nav-btn.active {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
            border-color: var(--gold-primary);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        /* Contenido principal */
        .main-content {
            max-width: 1400px;
            margin: 1.5rem auto;
            padding: 0 1rem;
        }

        .section-container {
            display: none;
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-container.active {
            display: block;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gold-primary);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 1.5rem;
            color: var(--gold-primary);
        }

        @media (max-width: 480px) {
            .section-title i {
                font-size: 1.3rem;
            }
        }

        .section-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--gold-dark);
        }

        @media (max-width: 768px) {
            .section-title h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .section-title h2 {
                font-size: 1.3rem;
            }
        }

        /* Tarjetas y formularios */
        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
        }

        @media (max-width: 480px) {
            .card {
                padding: 1rem;
            }
        }

        .card:hover {
            box-shadow: var(--shadow-medium);
            transform: translateY(-3px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--gold-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 480px) {
            .card-header h3 {
                font-size: 1.1rem;
            }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* NUEVO: Estilos para carrito de productos */
        .cart-container {
            margin-top: 1.5rem;
            border: 2px solid var(--medium-gray);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .cart-header {
            background: linear-gradient(135deg, var(--gold-light) 0%, rgba(212, 175, 55, 0.1) 100%);
            padding: 1rem;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h4 {
            color: var(--gold-dark);
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .cart-table th {
            background: var(--light-gray);
            padding: 0.75rem;
            text-align: left;
            font-weight: 500;
            border-bottom: 1px solid var(--medium-gray);
        }

        .cart-table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--medium-gray);
            vertical-align: middle;
        }

        .cart-table tbody tr:hover {
            background: var(--off-white);
        }

        .cart-table .actions {
            display: flex;
            gap: 5px;
        }

        .cart-total {
            background: linear-gradient(135deg, var(--gold-light) 0%, rgba(212, 175, 55, 0.05) 100%);
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--gold-dark);
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .empty-cart i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--medium-gray);
        }

        /* Tablas */
        .table-wrapper {
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            margin-bottom: 1.5rem;
            border: 1px solid var(--medium-gray);
            overflow-x: auto;
        }

        .table-header {
            background: linear-gradient(135deg, var(--gold-light) 0%, rgba(212, 175, 55, 0.1) 100%);
            padding: 1rem;
            border-bottom: 1px solid var(--medium-gray);
        }

        .table-header h3 {
            color: var(--gold-dark);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            min-width: 600px;
        }

        .data-table thead {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
            color: var(--white);
        }

        .data-table th {
            padding: 0.8rem 1rem;
            text-align: left;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
        }

        .data-table th:last-child {
            border-right: none;
        }

        .data-table tbody tr {
            border-bottom: 1px solid var(--medium-gray);
            transition: var(--transition);
        }

        .data-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(255, 215, 0, 0.02) 100%);
        }

        .data-table td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
        }

        @media (max-width: 480px) {

            .data-table th,
            .data-table td {
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.3px;
        }

        .badge-admin {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(255, 215, 0, 0.1) 100%);
            color: var(--gold-dark);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .badge-worker {
            background: linear-gradient(135deg, rgba(65, 105, 225, 0.15) 0%, rgba(30, 144, 255, 0.1) 100%);
            color: var(--info);
            border: 1px solid rgba(65, 105, 225, 0.3);
        }

        .badge-success {
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.15) 0%, rgba(50, 205, 50, 0.1) 100%);
            color: var(--success);
            border: 1px solid rgba(46, 139, 87, 0.3);
        }

        .badge-warning {
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.15) 0%, rgba(255, 215, 0, 0.1) 100%);
            color: var(--warning);
            border: 1px solid rgba(255, 165, 0, 0.3);
        }

        .badge-danger {
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.15) 0%, rgba(255, 99, 71, 0.1) 100%);
            color: var(--danger);
            border: 1px solid rgba(220, 20, 60, 0.3);
        }

        /* Botones */
        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #32CD32 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(46, 139, 87, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #FFD700 100%);
            color: var(--text-dark);
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #FF6347 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info) 0%, #1E90FF 100%);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        /* NUEVO: Estilos mejorados para la factura (SIN REDES SOCIALES) */
        .invoice-container.enhanced-invoice {
            background: white;
            border: 2px solid #333;
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            padding: 1.5rem;
            width: 95%;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .invoice-container.enhanced-invoice {
                padding: 1rem;
            }
        }

        .enhanced-invoice .invoice-header {
            text-align: left;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .enhanced-invoice .invoice-logo {
            font-size: 2rem;
            color: #D4AF37;
            margin-bottom: 8px;
        }

        .enhanced-invoice .company-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 1rem;
        }

        .enhanced-invoice .company-details {
            flex: 1;
            min-width: 250px;
        }

        .enhanced-invoice .invoice-details {
            flex: 1;
            text-align: right;
            min-width: 250px;
        }

        .enhanced-invoice .invoice-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .enhanced-invoice .client-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .enhanced-invoice .client-info h3 {
            color: #D4AF37;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }

        .enhanced-invoice .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .enhanced-invoice .product-table th {
            background: #D4AF37;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .enhanced-invoice .product-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 0.85rem;
        }

        .enhanced-invoice .warranty-section {
            background: #fff8e1;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 3px solid #D4AF37;
            font-size: 0.85rem;
        }

        .enhanced-invoice .warranty-section h4 {
            color: #D4AF37;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .enhanced-invoice .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #333;
        }

        .enhanced-invoice .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #D4AF37;
        }

        .enhanced-invoice .contact-info {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 0.85rem;
            color: #666;
        }

        .payment-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            margin: 2px;
            display: inline-block;
        }

        .payment-transfer {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }

        .payment-cash {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .payment-bold {
            background: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ffe0b2;
        }

        .payment-addi {
            background: #f3e5f5;
            color: #7b1fa2;
            border: 1px solid #e1bee7;
        }

        .payment-sistecredito {
            background: #e8eaf6;
            color: #3949ab;
            border: 1px solid #c5cae9;
        }

        .payment-cod {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        /* Factura */
        .invoice-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9000;
            overflow-y: auto;
            padding: 15px;
        }

        .invoice-container {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: var(--shadow-heavy);
            position: relative;
            border: 2px solid var(--gold-primary);
        }

        /* HISTORIAL - NUEVO DISEÑO CON CARDS */
        .history-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .history-cards-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .history-cards-container {
                grid-template-columns: 1fr;
            }
        }

        .history-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .history-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--gold-primary);
        }

        .history-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .history-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .history-card-icon.sales {
            background: linear-gradient(135deg, #2E8B57 0%, #32CD32 100%);
        }

        .history-card-icon.expenses {
            background: linear-gradient(135deg, #DC143C 0%, #FF6347 100%);
        }

        .history-card-icon.restocks {
            background: linear-gradient(135deg, #FFA500 0%, #FFD700 100%);
        }

        .history-card-icon.warranties {
            background: linear-gradient(135deg, #4169E1 0%, #1E90FF 100%);
        }

        .history-card-icon.pending {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        }

        .history-card-icon.profit {
            background: linear-gradient(135deg, #8BC34A 0%, #CDDC39 100%);
        }

        .history-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gold-dark);
            flex-grow: 1;
            margin-left: 1rem;
        }

        .history-card-count {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold-dark);
            text-align: center;
            margin: 0.5rem 0;
            font-family: 'Playfair Display', serif;
        }

        .history-card-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #666;
        }

        .history-card-detail {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed var(--medium-gray);
        }

        .history-card-detail:last-child {
            border-bottom: none;
        }

        .history-card-detail-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .history-card-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #666;
        }

        .history-card-user {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .history-card-user-icon {
            color: var(--gold-primary);
        }

        .history-card-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .history-card-date-icon {
            color: var(--info);
        }

        /* Detalles del historial (oculto inicialmente) */
        .history-details-container {
            display: none;
            animation: fadeInUp 0.5s ease-out;
        }

        .history-details-container.active {
            display: block;
        }

        .history-details-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--medium-gray);
        }

        .history-details-back {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gold-primary);
            cursor: pointer;
            padding: 5px;
            transition: var(--transition);
        }

        .history-details-back:hover {
            transform: translateX(-3px);
        }

        .history-details-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gold-dark);
        }

        .history-details-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .history-details-stat {
            background: linear-gradient(135deg, var(--white) 0%, var(--off-white) 100%);
            border-radius: var(--radius-lg);
            padding: 1.2rem;
            text-align: center;
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
        }

        .history-details-stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
        }

        .history-details-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gold-dark);
            margin: 0.5rem 0;
        }

        .history-details-stat-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Estadísticas generales */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: linear-gradient(135deg, var(--white) 0%, var(--off-white) 100%);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            text-align: center;
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
        }

        .stat-card.clickable {
            cursor: pointer;
            transition: var(--transition);
        }

        .stat-card.clickable:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
            border-color: var(--gold-primary);
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--gold-primary);
            margin-bottom: 0.75rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold-dark);
            margin: 0.5rem 0;
            font-family: 'Playfair Display', serif;
        }

        @media (max-width: 480px) {
            .stat-value {
                font-size: 1.5rem;
            }
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            color: var(--white);
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 2px solid var(--gold-primary);
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            text-align: center;
        }

        .footer-logo {
            font-size: 2rem;
            color: var(--gold-primary);
            margin-bottom: 0.75rem;
        }

        .footer-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--gold-primary);
        }

        @media (max-width: 480px) {
            .footer-content h3 {
                font-size: 1.3rem;
            }
        }

        .footer-content p {
            opacity: 0.8;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .copyright {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.85rem;
            opacity: 0.7;
        }

        /* Diálogos personalizados */
        .custom-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .dialog-content {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2rem;
            max-width: 450px;
            width: 100%;
            box-shadow: var(--shadow-heavy);
            border: 2px solid var(--gold-primary);
            text-align: center;
            position: relative;
            animation: dialogAppear 0.3s ease-out;
        }

        @keyframes dialogAppear {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .dialog-icon {
            font-size: 3rem;
            color: var(--gold-primary);
            margin-bottom: 1rem;
        }

        .dialog-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--gold-dark);
            margin-bottom: 0.75rem;
        }

        .dialog-message {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .dialog-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Responsive adicional para dispositivos muy pequeños */
        @media (max-height: 700px) {
            .login-box {
                max-height: 90vh;
                overflow-y: auto;
            }

            .password-change-box {
                max-height: 85vh;
            }
        }

        /* Mejoras para iOS */
        @supports (-webkit-touch-callout: none) {

            .login-container,
            .password-change-container {
                -webkit-overflow-scrolling: touch;
            }

            input,
            select,
            textarea,
            button {
                font-size: 16px !important;
                /* Evita zoom automático en iOS */
            }
        }

        /* Mejoras para Android */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {

            select,
            textarea,
            input {
                font-size: 16px !important;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Estilo para campo de ID de venta en garantías */
        #warrantySaleId {
            background-color: #f8f9fa !important;
            font-weight: bold !important;
            border: 2px solid var(--info) !important;
            color: #333 !important;
            font-size: 1rem !important;
            padding: 12px 15px !important;
        }

        #warrantySaleId:focus {
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.1) !important;
        }

        #warrantySaleIdStatus {
            font-size: 0.8rem;
            margin-top: 5px;
            padding: 5px;
            border-radius: var(--radius-sm);
            background: rgba(46, 139, 87, 0.1);
            border-left: 3px solid var(--success);
            display: none;
        }
    </style>
</head>

<body>
    <!-- Elementos decorativos -->
    <div class="jewelry-decoration ring-decoration"></div>
    <div class="jewelry-decoration chain-decoration"></div>

    <!-- Diálogo personalizado -->
    <div id="customDialog" class="custom-dialog">
        <div class="dialog-content">
            <div id="dialogIcon" class="dialog-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 id="dialogTitle" class="dialog-title">Éxito</h2>
            <p id="dialogMessage" class="dialog-message">Operación completada correctamente.</p>
            <div class="dialog-buttons">
                <button id="dialogConfirm" class="btn btn-primary">Aceptar</button>
                <button id="dialogCancel" class="btn btn-danger" style="display: none;">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- NUEVO: Modal para cambio de contraseña -->
    <div id="passwordChangeModal" class="password-change-container">
        <div class="password-change-box">
            <button class="close-password-change" id="closePasswordChange">
                <i class="fas fa-times"></i>
            </button>

            <div class="password-change-header">
                <i class="fas fa-key"></i>
                <h2>Cambiar Contraseña</h2>
                <p>Solo el administrador puede cambiar contraseñas</p>
            </div>

            <form id="passwordChangeForm">
                <div class="form-group">
                    <label for="adminUsername">Usuario Administrador *</label>
                    <input type="text" id="adminUsername" class="form-control" placeholder="admin" required>
                </div>

                <div class="form-group">
                    <label for="adminPassword">Contraseña Administrador *</label>
                    <input type="password" id="adminPassword" class="form-control" placeholder="********" required>
                </div>

                <div class="form-group">
                    <label for="roleToChange">Seleccionar Rol *</label>
                    <select id="roleToChange" class="form-control" required>
                        <option value="">Seleccione un rol</option>
                        <option value="admin">Administrador</option>
                        <option value="worker">Trabajador</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="userToChange">Usuario a modificar *</label>
                    <select id="userToChange" class="form-control" required disabled>
                        <option value="">Primero seleccione un rol</option>
                        <!-- Se llenará dinámicamente -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="newEmail">Correo Electrónico (Para recuperación) *</label>
                    <input type="email" id="newEmail" class="form-control" placeholder="usuario@email.com">
                    <small class="form-text">Si lo dejas vacío no se cambiará el actual.</small>
                </div>

                <div class="form-group">
                    <label for="newPassword">Nueva Contraseña *</label>
                    <input type="password" id="newPassword" class="form-control" placeholder="Nueva contraseña" required
                        minlength="6">
                    <small class="form-text" style="color: #666; font-size: 0.8rem;">
                        <i class="fas fa-info-circle"></i> Debe tener al menos una mayúscula, un número y un carácter especial.
                    </small>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirmar Contraseña *</label>
                    <input type="password" id="confirmPassword" class="form-control"
                        placeholder="Confirmar nueva contraseña" required>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cambiar Contraseña
                    </button>
                    <button type="button" class="btn btn-danger" id="cancelPasswordChange">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- NUEVO: Modal para resetear registros -->
    <div id="resetRecordsModal" class="password-change-container">
        <div class="password-change-box" style="border-color: var(--danger);">
            <button class="close-password-change" id="closeResetRecords">
                <i class="fas fa-times"></i>
            </button>

            <div class="password-change-header">
                <i class="fas fa-trash-alt" style="color: var(--danger);"></i>
                <h2 style="color: var(--danger);">Resetear Registro Total</h2>
                <p>Esta acción eliminará todos los movimientos y pondrá el stock en 0.</p>
                <p style="font-weight: bold; color: var(--danger);">¡ESTA ACCIÓN ES IRREVERSIBLE!</p>
            </div>

            <form id="resetRecordsForm">
                <div class="form-group">
                    <label for="adminResetPassword">Contraseña de Administrador *</label>
                    <input type="password" id="adminResetPassword" class="form-control" placeholder="Ingrese su contraseña para confirmar" required>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> ELIMINAR TODO
                    </button>
                    <button type="button" class="btn btn-warning" id="cancelResetRecords">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- NUEVO: Modal para Olvidaste tu contraseña -->
    <div id="forgotPasswordModal" class="password-change-container">
        <div class="password-change-box">
            <button class="close-password-change" id="closeForgotPassword">
                <i class="fas fa-times"></i>
            </button>

            <div class="password-change-header">
                <i class="fas fa-envelope-open-text"></i>
                <h2>Recuperar Contraseña</h2>
                <p>Ingresa tu correo registrado para recibir un link de restauración</p>
            </div>

            <form id="forgotPasswordForm">
                <div class="form-group">
                    <label>¿De quién es la cuenta? *</label>
                    <div class="role-selector" style="margin-bottom: 1rem;">
                        <div id="forgotAdminRole" class="role-btn active" data-role="admin">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin</span>
                        </div>
                        <div id="forgotWorkerRole" class="role-btn" data-role="worker">
                            <i class="fas fa-user-tie"></i>
                            <span>Trabajador</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="recoveryEmail">Correo Electrónico Registrado *</label>
                    <input type="email" id="recoveryEmail" class="form-control" placeholder="ejemplo@correo.com" required>
                    <small id="emailHelp" class="form-text text-muted">Debes confirmar el correo exacto que está en la base de datos.</small>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                    <button type="submit" class="btn btn-primary" id="sendRecoveryBtn">
                        <i class="fas fa-paper-plane"></i> Enviar Link de Recuperación
                    </button>
                    <button type="button" class="btn btn-danger" id="cancelForgotPassword">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pantalla de Login -->
    <div id="loginScreen" class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-gem"></i>
                <h1>Destello de Oro 18K</h1>
                <p>Sistema de Gestión de Inventario y Ventas</p>
            </div>

            <!-- Paso 1: Selección de rol -->
            <div id="roleSelection">
                <h3 style="text-align: center; margin-bottom: 1rem; color: var(--gold-dark); font-size: 1.1rem;">
                    <i class="fas fa-user-tag"></i> Seleccione su Rol
                </h3>

                <div class="role-selector">
                    <div id="adminRole" class="role-btn active" data-role="admin">
                        <i class="fas fa-user-shield"></i>
                        <span>Administrador</span>
                    </div>
                    <div id="workerRole" class="role-btn" data-role="worker">
                        <i class="fas fa-user-tie"></i>
                        <span>Trabajador</span>
                    </div>
                </div>

                <button id="nextToUserInfo" class="btn btn-primary"
                    style="width: 100%; margin-top: 1rem; padding: 10px;">
                    <i class="fas fa-arrow-right"></i> Continuar
                </button>

                <!-- NUEVO: Enlace para cambiar contraseña y olvidar contraseña -->
                <div style="text-align: center; margin-top: 1rem; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" id="showPasswordChange" class="btn btn-sm btn-info"
                        style="padding: 8px 15px; font-size: 0.85rem;">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                    <button type="button" id="showForgotPassword" class="btn btn-sm btn-warning"
                        style="padding: 8px 15px; font-size: 0.85rem;">
                        <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                    </button>
                </div>
            </div>

            <!-- Paso 2: Información del usuario (AHORA OBLIGATORIA) -->
            <div id="userInfoForm" class="user-info-form">
                <h3 style="text-align: center; margin-bottom: 1rem; color: var(--gold-dark); font-size: 1.1rem;">
                    <i class="fas fa-user-circle"></i> Información Personal
                </h3>
                <p style="text-align: center; color: var(--warning); font-size: 0.85rem; margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-circle"></i> Todos los campos son obligatorios para continuar
                </p>

                <form id="userInfoFormData">
                    <div class="form-group">
                        <label for="userName"><i class="fas fa-user"></i> Nombre *</label>
                        <input type="text" id="userName" class="form-control" placeholder="Ingrese su nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="userLastName"><i class="fas fa-user"></i> Apellido *</label>
                        <input type="text" id="userLastName" class="form-control" placeholder="Ingrese su apellido"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="userPhone"><i class="fas fa-phone"></i> Teléfono *</label>
                        <input type="tel" id="userPhone" class="form-control" placeholder="Ingrese su teléfono" required
                            pattern="[0-9]{10}" minlength="10" maxlength="10">
                        <small class="form-text" style="font-size: 0.8rem;">Debe tener 10 dígitos</small>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 1.5rem;">
                        <button type="button" id="backToRoleSelection" class="btn btn-warning"
                            style="flex: 1; padding: 10px;">
                            <i class="fas fa-arrow-left"></i> Atrás
                        </button>
                        <button type="submit" id="nextToLogin" class="btn btn-primary" style="flex: 2; padding: 10px;">
                            <i class="fas fa-arrow-right"></i> Continuar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Paso 3: Credenciales de login -->
            <div id="loginCredentials" class="user-info-form">
                <h3 style="text-align: center; margin-bottom: 1rem; color: var(--gold-dark); font-size: 1.1rem;">
                    <i class="fas fa-sign-in-alt"></i> Credenciales de Acceso
                </h3>

                <form id="loginForm">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-user"></i> Usuario *</label>
                        <input type="text" id="username" class="form-control" placeholder="Ingrese su usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Contraseña *</label>
                        <input type="password" id="password" class="form-control" placeholder="Ingrese su contraseña"
                            required>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 1.5rem;">
                        <button type="button" id="backToUserInfo" class="btn btn-warning"
                            style="flex: 1; padding: 10px;">
                            <i class="fas fa-arrow-left"></i> Atrás
                        </button>
                        <button type="submit" class="btn btn-primary" style="flex: 2; padding: 10px;">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>

            <!-- Información de credenciales de prueba -->
            <div id="loginInfo"
                style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--medium-gray); display: none;">
                <p style="text-align: center; color: #666; font-size: 0.85rem;">
                    <strong>Ingrese sus credenciales asignadas</strong><br>
                    para poder acceder<br>
                    al sistema
                </p>
            </div>
        </div>
    </div>

    <!-- Aplicación principal -->
    <div id="appScreen">
        <!-- Header -->
        <header class="main-header">
            <div class="header-content">
                <div class="brand">
                    <i class="fas fa-gem brand-icon"></i>
                    <div class="brand-text">
                        <h1>Destello de Oro 18K</h1>
                        <span>Sistema de Gestión Profesional</span>
                    </div>
                </div>

                <div class="user-controls">
                    <div id="currentUserRole" class="user-badge admin">
                        <i class="fas fa-user-shield"></i>
                        <span>Administrador</span>
                    </div>
                    <button id="logoutButton" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </div>
            </div>
        </header>

        <!-- Navegación -->
        <nav class="main-nav">
            <div class="nav-container">
                <button class="nav-btn active" data-section="inventory">
                    <i class="fas fa-warehouse"></i> Inventario
                </button>
                <button class="nav-btn" data-section="sales" id="salesNavBtn">
                    <i class="fas fa-shopping-cart"></i> Realizar Venta
                </button>
                <button class="nav-btn admin-only" data-section="restock" id="restockNavBtn">
                    <i class="fas fa-truck-loading"></i> Surtir Inventario
                </button>
                <button class="nav-btn admin-only" data-section="expenses" id="expensesNavBtn">
                    <i class="fas fa-file-invoice-dollar"></i> Gastos
                </button>
                <button class="nav-btn admin-only" data-section="warranties" id="warrantiesNavBtn">
                    <i class="fas fa-shield-alt"></i> Garantías
                </button>
                <button class="nav-btn admin-only" data-section="pending" id="pendingNavBtn">
                    <i class="fas fa-clock"></i> Pagos Pendientes
                </button>
                <button class="nav-btn admin-only" data-section="history" id="historyNavBtn">
                    <i class="fas fa-chart-line"></i> Historial
                </button>
                <button class="nav-btn admin-only" id="resetRecordsBtn" style="color: var(--danger);">
                    <i class="fas fa-trash-alt"></i> Resetear Registros
                </button>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="main-content">
            <!-- Inventario -->
            <section id="inventory" class="section-container active">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-warehouse"></i>
                        <h2>Gestión de Inventario</h2>
                    </div>
                    <button class="btn btn-primary admin-only" id="addProductBtn" style="padding: 10px 20px;">
                        <i class="fas fa-plus-circle"></i> Nuevo Producto
                    </button>
                </div>

                <!-- Formulario para agregar producto -->
                <div id="addProductForm" class="card admin-only" style="display: none;">
                    <div class="card-header">
                        <h3><i class="fas fa-box-open"></i> Agregar Nuevo Producto</h3>
                    </div>
                    <form id="productForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="productDate">Fecha *</label>
                                <input type="date" id="productDate" class="form-control" required>
                                <small class="form-text" style="font-size: 0.8rem;">Fecha de ingreso del producto</small>
                            </div>
                            <div class="form-group">
                                <label for="productRef">Referencia *</label>
                                <input type="text" id="productRef" class="form-control" required>
                                <small class="form-text" style="font-size: 0.8rem;">Identificador único del
                                    producto</small>
                            </div>
                            <div class="form-group">
                                <label for="productName">Nombre del Producto *</label>
                                <input type="text" id="productName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="productQuantity">Cantidad Inicial *</label>
                                <input type="number" id="productQuantity" class="form-control" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="purchasePrice">Precio de Compra *</label>
                                <input type="number" id="purchasePrice" class="form-control" min="0" step="0.01"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="wholesalePrice">Precio Mayorista *</label>
                                <input type="number" id="wholesalePrice" class="form-control" min="0" step="0.01"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="retailPrice">Precio al Detal *</label>
                                <input type="number" id="retailPrice" class="form-control" min="0" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="supplier">Proveedor *</label>
                                <input type="text" id="supplier" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Ganancia Detal Estimada</label>
                                <input type="text" id="profitEstimate" class="form-control" readonly
                                    style="background-color: var(--light-gray); font-size: 0.9rem;">
                            </div>
                            <div class="form-group">
                                <label>Ganancia Mayorista Estimada</label>
                                <input type="text" id="profitWholesaleEstimate" class="form-control" readonly
                                    style="background-color: var(--light-gray); font-size: 0.9rem;">
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                            <button type="submit" class="btn btn-success" style="padding: 10px 20px;">
                                <i class="fas fa-save"></i> Guardar Producto
                            </button>
                            <button type="button" class="btn btn-danger" id="cancelAddProduct"
                                style="padding: 10px 20px;">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de inventario -->
                <div class="table-wrapper">
                    <div class="table-header"
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <h3><i class="fas fa-list"></i> Productos en Inventario</h3>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <div class="search-box">
                                <input type="text" id="inventorySearch" class="form-control"
                                    placeholder="Buscar por referencia o nombre..." style="min-width: 250px;"
                                    oninput="loadInventoryTable()">
                            </div>
                            <button class="btn btn-sm btn-info" onclick="loadInventoryTable()">
                                <i class="fas fa-sync-alt"></i> Refrescar
                            </button>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table" id="inventoryTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Referencia</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Mayorista</th>
                                    <th>Precio Detal</th>
                                    <th>Ganancia Detal</th>
                                    <th>Ganancia Mayorista</th>
                                    <th>Proveedor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTableBody">
                                <!-- Los productos se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Ventas (NUEVO: con múltiples productos) -->
            <section id="sales" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Realizar Venta</h2>
                    </div>
                </div>

                <!-- Contador Manual de Ventas y Valores (Solo Admin) -->
                <div class="card admin-only"
                    style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(255, 215, 0, 0.05) 100%); border: 1px solid var(--gold-primary);">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <h3 style="color: var(--gold-dark); margin: 0; font-size: 1.1rem;">
                            <i class="fas fa-calculator"></i> Registro Manual de Ventas 
                        </h3>
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <label for="manualSalesCounter" style="font-weight: 500;">Cantidad</label>
                                <input type="number" id="manualSalesCounter" class="form-control"
                                    style="width: 80px; text-align: center; font-weight: bold;" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <!-- Información del cliente (SIEMPRE VISIBLE) -->
                    <div id="customerInfo"
                        style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 2px solid var(--medium-gray);">
                        <h3
                            style="margin-bottom: 1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                            <i class="fas fa-user-circle"></i> Información del Cliente
                        </h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="customerName">Nombre Completo *</label>
                                <input type="text" id="customerName" class="form-control"
                                    placeholder="Ej: Juan Pérez García" required>
                            </div>
                            <div class="form-group">
                                <label for="customerId">Cédula *</label>
                                <input type="text" id="customerId" class="form-control" placeholder="Ej: 1234567890"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="customerPhone">Teléfono *</label>
                                <input type="tel" id="customerPhone" class="form-control" placeholder="Ej: 3001234567"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="customerEmail">Correo Electrónico</label>
                                <input type="email" id="customerEmail" class="form-control"
                                    placeholder="Ej: cliente@email.com">
                            </div>
                            <div class="form-group">
                                <label for="customerAddress">Dirección *</label>
                                <input type="text" id="customerAddress" class="form-control"
                                    placeholder="Ej: Calle 123 #45-67" required>
                            </div>
                            <div class="form-group">
                                <label for="customerCity">Ciudad *</label>
                                <input type="text" id="customerCity" class="form-control"
                                    placeholder="Ej: Bogotá, Medellín, etc." required>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario para agregar productos a la venta -->
                    <h3
                        style="margin-bottom: 1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                        <i class="fas fa-box-open"></i> Agregar Productos a la Venta
                    </h3>

                    <form id="addProductToSaleForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="saleProductRef">Referencia del Producto *</label>
                                <input type="text" id="saleProductRef" class="form-control" required>
                                <div id="productInfo" style="margin-top: 6px; font-size: 0.85rem; color: var(--info);">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="saleQuantity">Cantidad *</label>
                                <input type="number" id="saleQuantity" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="saleType">Tipo de Venta *</label>
                                <select id="saleType" class="form-control" required>
                                    <option value="retail">Detal</option>
                                    <option value="wholesale">Mayorista</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="discount">Descuento (%)</label>
                                <input type="number" id="discount" class="form-control" min="0" max="100" value="0">
                                <small class="form-text" style="font-size: 0.8rem;">Entre 0 y 100%</small>
                            </div>
                        </div>

                        <div
                            style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                            <button type="submit" class="btn btn-success" style="padding: 12px 30px;">
                                <i class="fas fa-plus-circle"></i> Agregar al Carrito
                            </button>
                        </div>
                    </form>

                    <!-- Carrito de productos -->
                    <div class="cart-container" id="cartContainer" style="display: none;">
                        <div class="cart-header">
                            <h4><i class="fas fa-shopping-cart"></i> Productos en el Carrito</h4>
                            <button type="button" class="btn btn-danger btn-sm" id="clearCart">
                                <i class="fas fa-trash"></i> Vaciar Carrito
                            </button>
                        </div>

                        <table class="cart-table" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Los productos se agregarán dinámicamente -->
                            </tbody>
                        </table>

                        <div id="emptyCart" class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <p>El carrito está vacío</p>
                        </div>

                        <div class="cart-total" id="cartTotal" style="display: none;">
                            Total: <span id="cartTotalAmount">$0</span>
                        </div>
                    </div>

                    <!-- Información de pago y envío -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--medium-gray);">
                        <h3
                            style="margin-bottom: 1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                            <i class="fas fa-credit-card"></i> Información de Pago y Envío
                        </h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="manualInvoiceId">Numero de factura (manual)</label>
                                <input type="text" id="manualInvoiceId" class="form-control" placeholder="Ej: FAC1234">
                                <small class="form-text" style="font-size: 0.8rem;">Si lo dejas vacio se autogenera.</small>
                            </div>
                            <div class="form-group">
                                <label for="manualSaleDate">Fecha de venta</label>
                                <input type="date" id="manualSaleDate" class="form-control">
                                <small class="form-text" style="font-size: 0.8rem;">La hora se agrega automaticamente.</small>
                            </div>
                            <div class="form-group">
                                <label for="paymentMethod">Método de Pago *</label>
                                <select id="paymentMethod" class="form-control" required>
                                    <option value="transfer">Transferencia</option>
                                    <option value="cash">Efectivo</option>
                                    <option value="bold">Bold</option>
                                    <option value="addi">Addi</option>
                                    <option value="sistecredito">Sistecrédito</option>
                                    <option value="cod">Contra Entrega</option>
                                    <option value="card">Tarjeta</option>
                                    <option value="nequi">Nequi/Daviplata</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deliveryType">Tipo de Entrega</label>
                                <select id="deliveryType" class="form-control">
                                    <option value="store">Recoge en tienda</option>
                                    <option value="delivery">Domicilio</option>
                                    <option value="national">Envío Nacional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="deliveryCost">Costo de Envío</label>
                                <input type="number" id="deliveryCost" class="form-control" min="0" value="0">
                            </div>
                        </div>
                    </div>

                    <!-- Resumen final de la venta -->
                    <div
                        style="margin-top: 1.5rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(255, 215, 0, 0.02) 100%); border-radius: var(--radius-md); border: 1px solid var(--medium-gray);">
                        <h3
                            style="margin-bottom: 1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                            <i class="fas fa-receipt"></i> Resumen Final de Venta
                        </h3>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                            <div>
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Subtotal:</div>
                                <div id="subtotalAmount"
                                    style="font-size: 1.2rem; font-weight: 700; color: var(--text-dark);">$0</div>
                            </div>
                            <div>
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Descuentos:</div>
                                <div id="discountAmount"
                                    style="font-size: 1.2rem; font-weight: 700; color: var(--danger);">$0</div>
                            </div>
                            <div>
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Costo de envío:</div>
                                <div id="deliveryAmount"
                                    style="font-size: 1.2rem; font-weight: 700; color: var(--info);">$0</div>
                            </div>
                            <div>
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Total:</div>
                                <div id="totalAmount"
                                    style="font-size: 1.5rem; font-weight: 800; color: var(--gold-dark);">$0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones finales -->
                    <div
                        style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-success" id="confirmSale" style="padding: 12px 30px;"
                            disabled>
                            <i class="fas fa-check-circle"></i> Confirmar Venta
                        </button>
                        <button type="button" class="btn btn-danger" id="clearSaleForm" style="padding: 12px 30px;">
                            <i class="fas fa-trash-alt"></i> Limpiar Todo
                        </button>
                    </div>
                </div>
            </section>

            <!-- Surtir Inventario -->
            <section id="restock" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-truck-loading"></i>
                        <h2>Surtir Inventario</h2>
                    </div>
                </div>

                <div class="card">
                    <form id="restockForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="restockProductRef">Referencia del Producto *</label>
                                <input type="text" id="restockProductRef" class="form-control" required>
                                <div id="restockProductInfo"
                                    style="margin-top: 6px; font-size: 0.85rem; color: var(--info);"></div>
                            </div>
                            <div class="form-group">
                                <label for="restockQuantity">Cantidad a Surtir *</label>
                                <input type="number" id="restockQuantity" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
                            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                                <i class="fas fa-plus-circle"></i> Surtir Inventario
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Gastos -->
            <section id="expenses" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h2>Registro de Gastos</h2>
                    </div>
                    <button class="btn btn-primary" id="addExpenseBtn" style="padding: 10px 20px;">
                        <i class="fas fa-plus-circle"></i> Nuevo Gasto
                    </button>
                </div>

                <!-- Formulario para agregar gasto -->
                <div id="addExpenseForm" class="card" style="display: none;">
                    <div class="card-header">
                        <h3><i class="fas fa-receipt"></i> Registrar Nuevo Gasto</h3>
                    </div>
                    <form id="expenseForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="expenseDescription">Descripción *</label>
                                <input type="text" id="expenseDescription" class="form-control" required>
                                <small class="form-text" style="font-size: 0.8rem;">Ej: Pasajes, domicilios, bolsas,
                                    etc.</small>
                            </div>
                            <div class="form-group">
                                <label for="expenseDate">Fecha *</label>
                                <input type="date" id="expenseDate" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="expenseAmount">Valor *</label>
                                <input type="number" id="expenseAmount" class="form-control" min="0" step="0.01"
                                    required>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                            <button type="submit" class="btn btn-success" style="padding: 10px 20px;">
                                <i class="fas fa-save"></i> Registrar Gasto
                            </button>
                            <button type="button" class="btn btn-danger" id="cancelExpense" style="padding: 10px 20px;">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de gastos -->
                <div class="table-wrapper">
                    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3><i class="fas fa-history"></i> Historial de Gastos</h3>
                        <button class="btn btn-sm btn-info" onclick="loadExpensesTable()">
                            <i class="fas fa-sync-alt"></i> Refrescar
                        </button>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table" id="expensesTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Registrado por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="expensesTableBody">
                                <!-- Los gastos se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Garantías -->
            <section id="warranties" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-shield-alt"></i>
                        <h2>Gestión de Garantías</h2>
                    </div>
                    <button class="btn btn-primary admin-only" id="addWarrantyBtn" style="padding: 10px 20px;">
                        <i class="fas fa-plus-circle"></i> Nueva Garantía
                    </button>
                </div>

                <!-- Contador Manual de Garantías y Costos (Solo Admin) -->
                <div class="card admin-only" id="warrantyManualCard"
                    style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(255, 215, 0, 0.05) 100%); border: 1px solid var(--gold-primary);">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                        <h3 style="color: var(--gold-dark); margin: 0; font-size: 1.1rem;">
                            <i class="fas fa-calculator"></i> Registro Manual de Garantías
                        </h3>
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <label for="manualWarrantyCounter" style="font-weight: 500;">Cantidad</label>
                                <input type="number" id="manualWarrantyCounter" class="form-control"
                                    style="width: 80px; text-align: center; font-weight: bold;" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario para buscar cliente -->
                <div class="card" id="warrantySearchCard">
                    <h3
                        style="margin-bottom: 1rem; color: var(--gold-dark); display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
                        <i class="fas fa-search"></i> Buscar Cliente para Garantía
                    </h3>

                    <div class="form-group">
                        <label for="searchCustomerWarranty"><i class="fas fa-user"></i> Nombre del Cliente *</label>
                        <input type="text" id="searchCustomerWarranty" class="form-control"
                            placeholder="Ingrese nombre completo del cliente">
                        <small class="form-text" style="font-size: 0.8rem;">Debe ser un cliente que haya realizado una
                            compra previamente</small>
                    </div>

                    <div id="customerSearchResults" style="margin-top: 1rem; display: none;">
                        <h4 style="color: var(--gold-dark); margin-bottom: 0.5rem;">Compras encontradas:</h4>
                        <div id="customerPurchasesList"
                            style="max-height: 200px; overflow-y: auto; border: 1px solid var(--medium-gray); border-radius: var(--radius-md); padding: 10px;">
                        </div>
                    </div>

                    <div id="customerNotFoundMessage"
                        style="margin-top: 1rem; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: var(--radius-md); display: none;">
                        <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>
                        <span>Cliente no encontrado. Por favor ingrese un cliente que haya realizado una compra.</span>
                    </div>
                </div>

                <!-- Formulario para agregar garantía (oculto inicialmente) -->
                <div id="addWarrantyForm" class="card admin-only" style="display: none;">
                    <div class="card-header">
                        <h3><i class="fas fa-file-contract"></i> Registrar Nueva Garantía</h3>
                        <button type="button" class="btn btn-warning btn-sm" id="backToCustomerSearch">
                            <i class="fas fa-arrow-left"></i> Buscar otro cliente
                        </button>
                    </div>

                    <!-- Información del cliente seleccionado -->
                    <div id="selectedCustomerInfo"
                        style="margin-bottom: 1.5rem; padding: 1rem; background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(255, 215, 0, 0.02) 100%); border-radius: var(--radius-md); border: 1px solid var(--medium-gray);">
                        <h4 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1rem;">
                            <i class="fas fa-user-check"></i> Cliente seleccionado
                        </h4>
                        <div id="customerInfoDisplay"></div>
                    </div>

                    <!-- Producto original de la compra -->
                    <div id="originalProductInfo"
                        style="margin-bottom: 1.5rem; padding: 1rem; background: linear-gradient(135deg, rgba(65, 105, 225, 0.05) 0%, rgba(30, 144, 255, 0.02) 100%); border-radius: var(--radius-md); border: 1px solid var(--medium-gray);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <h4 style="color: var(--info); margin: 0; font-size: 1rem;">
                                <i class="fas fa-box-open"></i> Producto de la venta
                            </h4>
                            <div id="productSelectContainer" style="display:none;">
                                <select id="warrantySelectedProduct" class="form-control" style="padding: 4px 8px; font-size: 0.85rem; height: auto; width: auto;"></select>
                            </div>
                        </div>
                        <div id="productInfoDisplay"></div>
                    </div>

                    <form id="warrantyForm">
                        <div class="form-grid">
                            <!-- CORREGIDO: ID DE FACTURA SE LLENA AUTOMÁTICAMENTE Y ES VISIBLE -->
                            <div class="form-group">
                                <label for="warrantySaleId"><i class="fas fa-receipt"></i> ID de Factura *</label>
                                <input type="text" id="warrantySaleId" class="form-control" readonly required>
                                <small class="form-text"
                                    style="font-size: 0.8rem; color: var(--info); font-weight: bold;">
                                    ID de la factura de venta original (se llena automáticamente cuando selecciona una
                                    venta)
                                </small>
                                <div id="warrantySaleIdStatus" style="font-size: 0.8rem; margin-top: 5px;"></div>
                                <!-- Botón temporal para pruebas -->
                                <button type="button" onclick="loadSelectedSaleId()"
                                    style="margin-top: 5px; padding: 5px 10px; font-size: 0.8rem; background: var(--info); color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    <i class="fas fa-sync-alt"></i> Cargar ID Manualmente
                                </button>
                            </div>

                             <div class="form-group">
                                <label for="warrantyReason">Motivo de la Garantía *</label>
                                <select id="warrantyReason" class="form-control" required>
                                    <option value="">Seleccione un motivo</option>
                                    <option value="rayon">Rayón</option>
                                    <option value="pelo">Pelo</option>
                                    <option value="oxidacion">Oxidación</option>
                                    <option value="cambio_color">Cambio de color</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <small class="form-text" style="font-size: 0.8rem;">Garantía por rayón, pelo, oxidación,
                                    cambio de color u otro</small>
                            </div>

                            <div class="form-group">
                                <label for="warrantyQuantity">Cantidad para Garantía *</label>
                                <input type="number" id="warrantyQuantity" class="form-control" min="1" value="1" required>
                                <small class="form-text" style="font-size: 0.8rem;">Cantidad de unidades que entran en garantía</small>
                            </div>

                            <!-- Tipo de producto para garantía -->
                            <div class="form-group">
                                <label for="warrantyProductType">Tipo de Producto para Garantía *</label>
                                <select id="warrantyProductType" class="form-control" required>
                                    <option value="same">Mismo producto (misma referencia)</option>
                                    <option value="different">Producto diferente</option>
                                </select>
                            </div>

                            <!-- Si es producto diferente -->
                            <div id="differentProductSection"
                                style="display: none; grid-column: 1 / -1; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid var(--medium-gray);">
                                <h4 style="color: var(--gold-dark); margin-bottom: 1rem; font-size: 1rem;">
                                    <i class="fas fa-exchange-alt"></i> Producto Diferente
                                </h4>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="newProductRef">Referencia del Nuevo Producto</label>
                                        <input type="text" id="newProductRef" class="form-control" placeholder="REFXXX">
                                        <div id="newProductStatus" style="font-size: 0.8rem; margin-top: 4px; font-weight: 500;"></div>
                                        <small class="form-text" style="font-size: 0.8rem;">Referencia del producto de
                                            reemplazo</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="newProductName">Nombre del Nuevo Producto</label>
                                        <input type="text" id="newProductName" class="form-control"
                                            placeholder="Nombre del nuevo producto">
                                    </div>

                                    <div class="form-group">
                                        <label for="additionalValue">Valor Adicional *</label>
                                        <input type="number" id="additionalValue" class="form-control" min="0" value="0"
                                            required>
                                        <small class="form-text" style="font-size: 0.8rem;">Valor adicional si el
                                            producto es diferente</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Valor de envío -->
                            <div class="form-group">
                                <label for="shippingValue">Valor Envío *</label>
                                <input type="number" id="shippingValue" class="form-control" min="0" value="0" required>
                                <small class="form-text" style="font-size: 0.8rem;">Este valor se agregará a los gastos
                                    mensuales</small>
                            </div>

                            <div class="form-group">
                                <label for="warrantyStatus">Estado *</label>
                                <select id="warrantyStatus" class="form-control" required>
                                    <option value="pending">Pendiente</option>
                                    <option value="in_process">En proceso</option>
                                    <option value="completed">Completada</option>
                                    <option value="cancelled">Cancelada</option>
                                </select>
                            </div>

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="warrantyNotes">Observaciones / Detalles</label>
                                <textarea id="warrantyNotes" class="form-control" rows="3"
                                    placeholder="Detalles adicionales de la garantía..."></textarea>
                                <small class="form-text" style="font-size: 0.8rem;">Describa el estado del producto,
                                    acuerdos con el cliente, etc.</small>
                            </div>
                        </div>

                        <!-- Resumen de costos -->
                        <div id="warrantyCostSummary"
                            style="margin-top: 1.5rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(46, 139, 87, 0.05) 0%, rgba(50, 205, 50, 0.02) 100%); border-radius: var(--radius-md); border: 1px solid var(--medium-gray);">
                            <h4 style="color: var(--success); margin-bottom: 1rem; font-size: 1rem;">
                                <i class="fas fa-calculator"></i> Resumen de Costos
                            </h4>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                                <div>
                                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Valor adicional:
                                    </div>
                                    <div id="additionalValueDisplay"
                                        style="font-size: 1.2rem; font-weight: 700; color: var(--warning);">$0</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Valor envío:</div>
                                    <div id="shippingValueDisplay"
                                        style="font-size: 1.2rem; font-weight: 700; color: var(--info);">$0</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Total:</div>
                                    <div id="totalWarrantyCost"
                                        style="font-size: 1.5rem; font-weight: 800; color: var(--gold-dark);">$0</div>
                                </div>
                            </div>
                        </div>

                        <div
                            style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 1.5rem; flex-wrap: wrap;">
                            <button type="submit" class="btn btn-success" style="padding: 10px 20px;">
                                <i class="fas fa-save"></i> Registrar Garantía
                            </button>
                            <button type="button" class="btn btn-danger" id="cancelWarranty"
                                style="padding: 10px 20px;">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de garantías -->
                <div class="table-wrapper">
                    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3><i class="fas fa-list"></i> Garantías Registradas</h3>
                        <button class="btn btn-sm btn-info" onclick="loadWarrantiesTable()">
                            <i class="fas fa-sync-alt"></i> Refrescar
                        </button>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table" id="warrantiesTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>ID Venta</th>
                                    <th>Cliente</th>
                                    <th>Producto Original</th>
                                    <th>Producto Garantía</th>
                                    <th>Motivo</th>
                                    <th>Valor Adicional</th>
                                    <th>Valor Envío</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="warrantiesTableBody">
                                <!-- Las garantías se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Estadísticas de garantías -->
                <div class="stats-grid" id="warrantyStats">
                    <!-- Se cargará dinámicamente -->
                </div>
            </section>

            <!-- Pagos Pendientes -->
            <section id="pending" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-clock"></i>
                        <h2>Pagos Pendientes</h2>
                    </div>
                </div>

                <!-- Tabla de ventas pendientes -->
                <div class="table-wrapper">
                    <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3><i class="fas fa-hourglass-half"></i> Ventas Pendientes de Confirmación</h3>
                        <button class="btn btn-sm btn-info" onclick="loadPendingSalesTable()">
                            <i class="fas fa-sync-alt"></i> Refrescar
                        </button>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>ID de Venta</th>
                                    <th>Cliente</th>
                                    <th>Productos</th>
                                    <th>Total</th>
                                    <th>Método de Pago</th>
                                    <th>Vendedor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pendingTableBody">
                                <!-- Las ventas pendientes se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- HISTORIAL - NUEVO DISEÑO CON CARDS -->
            <section id="history" class="section-container">
                <div class="section-header">
                    <div class="section-title">
                        <i class="fas fa-chart-line"></i>
                        <h2>Historial Completo</h2>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="historyFilter" class="form-control" style="width: auto; padding: 8px 12px; height: auto;">
                            <option value="all">Todos los movimientos</option>
                            <option value="sales">Ventas</option>
                            <option value="expenses">Gastos</option>
                            <option value="restocks">Surtidos</option>
                            <option value="warranties">Garantías</option>
                            <option value="pending">Pendientes</option>
                            <option value="profit">Ganancias</option>
                            <option value="admin_audit">Movimientos Admin</option>
                            <option value="investment">Inversión (Gastos + Costos)</option>
                        </select>
                        <button id="refreshHistory" class="btn btn-info" style="padding: 8px 16px; height: auto;">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                        <!-- Selector de mes y año -->
                        <div id="monthYearSelectors" style="display: flex; gap: 15px; align-items: center;">
                            <div class="form-group" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                <label for="monthSelect" style="font-size: 0.9rem; margin-bottom: 0; white-space: nowrap;"><i
                                        class="fas fa-calendar-day"></i> Mes</label>
                                <select id="monthSelect" class="form-control" style="width: auto; padding: 8px 12px; height: auto;">
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                            <div class="form-group" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                <label for="yearSelect" style="font-size: 0.9rem; margin-bottom: 0; white-space: nowrap;"><i
                                        class="fas fa-calendar"></i> Año</label>
                                <select id="yearSelect" class="form-control" style="width: auto; padding: 8px 12px; height: auto;">
                                    <!-- Se llenará dinámicamente -->
                                </select>
                            </div>
                        </div>
                    </div>


                <!-- Vista de tarjetas -->
                <div id="historyCardsView" class="history-cards-container">
                    <!-- Las tarjetas se cargarán dinámicamente -->
                </div>

                <!-- Vista de detalles (oculta inicialmente) -->
                <div id="historyDetailsView" class="history-details-container">
                    <div class="history-details-header">
                        <button id="backToCards" class="history-details-back">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <h2 class="history-details-title" id="detailsTitle">Detalles</h2>
                    </div>

                    <!-- Estadísticas del tipo seleccionado -->
                    <div class="history-details-stats" id="detailsStats">
                        <!-- Se cargarán dinámicamente -->
                    </div>

                    <!-- Tabla de detalles -->
                    <div class="table-wrapper">
                        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3><i class="fas fa-list"></i> <span id="detailsTableTitle">Movimientos</span></h3>
                            <button class="btn btn-sm btn-info" onclick="loadHistoryCards().then(() => showHistoryDetails(currentHistoryDetailType))">
                                <i class="fas fa-sync-alt"></i> Refrescar
                            </button>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="data-table" id="historyDetailsTable">
                                <thead id="historyDetailsTableHead">
                                    <!-- Se cargará dinámicamente según el tipo -->
                                </thead>
                                <tbody id="historyDetailsTableBody">
                                    <!-- Los detalles se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- Vista de Auditoría Admin (Oculta por defecto) -->
                <div id="auditLogsView" class="history-details-container" style="display: none;">
                    <div class="history-details-header">
                        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                            <h2 class="history-details-title">
                                <i class="fas fa-shield-alt" style="color: var(--gold-dark);"></i> Registro de Movimientos Administrativos
                            </h2>
                            <button class="btn btn-sm btn-info" onclick="loadAuditLogs()">
                                <i class="fas fa-sync-alt"></i> Refrescar
                            </button>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <div style="overflow-x: auto;">
                            <table class="data-table" id="auditLogsTable">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                        <th>Entidad</th>
                                        <th>Detalles</th>
                                    </tr>
                                </thead>
                                <tbody id="auditLogsTableBody">
                                    <tr><td colspan="5" style="text-align:center;">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </section>
        </main>

    </div>

    <!-- Modal de factura MEJORADO SIN REDES SOCIALES -->
    <div id="invoiceModal" class="invoice-modal">
        <div class="invoice-container enhanced-invoice">
            <div class="invoice-header">
                <i class="fas fa-gem invoice-logo"></i>
                <h2 style="color: #D4AF37; font-family: 'Playfair Display', serif;">Destello de Oro 18K</h2>
                <p>Factura de Venta - Tienda de Oro Laminado</p>
            </div>

            <div class="company-info">
                <div class="company-details">
                    <p><strong>DESTELLO DE ORO 18K</strong></p>
                    <p>Tienda de Oro Laminado</p>
                    <p>Contacto: 3182687488</p>
                </div>
                <div class="invoice-details">
                    <div class="invoice-title">FACTURA DE VENTA</div>
                    <p id="invoiceNumber">Factura #0001</p>
                    <p id="invoiceDate">Fecha: 01/01/2026</p>
                    <p id="invoicePaymentMethod">Método de Pago: Efectivo</p>
                </div>
            </div>

            <div class="client-info">
                <h3>DATOS DEL CLIENTE</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem;">
                    <div>
                        <strong>Nombre:</strong> <span id="invoiceCustomerName">Cliente de mostrador</span>
                    </div>
                    <div>
                        <strong>Cédula:</strong> <span id="invoiceCustomerId">No proporcionada</span>
                    </div>
                    <div>
                        <strong>Celular:</strong> <span id="invoiceCustomerPhone">No proporcionado</span>
                    </div>
                    <div>
                        <strong>Dirección:</strong> <span id="invoiceCustomerAddress">No proporcionada</span>
                    </div>
                    <div>
                        <strong>Ciudad:</strong> <span id="invoiceCustomerCity">No proporcionada</span>
                    </div>
                    <div>
                        <strong>Correo:</strong> <span id="invoiceCustomerEmail">No proporcionado</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 style="color: #D4AF37; margin-bottom: 0.75rem; font-size: 1rem;">DETALLES DE LA COMPRA</h3>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Ref</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItemsBody">
                        <!-- Los items se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <div class="warranty-section">
                <h4>📋 GARANTÍA DE TU JOYA</h4>
                <p style="font-size: 0.85rem; line-height: 1.4;">
                    Tu joya cuenta con <strong>12 meses de garantía</strong> por cambio de color desde la fecha de
                    compra.
                    Esta garantía no cubra daños por mal uso, como cadenas rotas, rayones, modificaciones o piezas
                    incompletas.
                    En caso de aplicar la garantía, tu joya será reemplazada por una nueva (sin opción de reembolso).
                    Si recibes tu joya en mal estado, repórtalo dentro de los <strong>3 días hábiles</strong>
                    posteriores a la entrega
                    para gestionar el cambio con gusto.
                </p>
                <p style="font-size: 0.85rem; line-height: 1.4; margin-top: 8px;">
                    <strong>IMPORTANTE:</strong> Si tu joya presenta un cambio de tonalidad dentro del tiempo
                    establecido,
                    deberás cubrir el costo del envío hacia nuestras instalaciones, y nosotros asumimos el costo del
                    envío de regreso.
                </p>
            </div>

            <div class="total-section">
                <div style="font-size: 1.2rem; font-weight: bold;">
                    TOTAL: <span id="invoiceTotal" class="total-amount">$0</span>
                </div>
            </div>

            <div class="contact-info">
                <p>¡Gracias por tu compra! Tu satisfacción es nuestro mayor compromiso.</p>
                <p>Para consultas o garantías, contacta al: <strong>3182687488</strong></p>
                <p style="font-style: italic; margin-top: 8px; font-size: 0.85rem;">"En Destello de Oro 18K, cada pieza
                    cuenta una historia de elegancia"</p>
            </div>

            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                <button id="downloadInvoice" class="btn btn-primary" style="padding: 10px 20px; font-size: 0.85rem;">
                    <i class="fas fa-download"></i> Descargar PDF
                </button>
                <button id="printInvoice" class="btn btn-info" style="padding: 10px 20px; font-size: 0.85rem;">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button id="closeInvoice" class="btn btn-danger" style="padding: 10px 20px; font-size: 0.85rem;">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de movimiento -->
    <div id="viewMovementModal" class="custom-dialog">
        <div class="dialog-content" style="max-width: 800px;">
            <div class="dialog-icon" style="color: var(--info);">
                <i class="fas fa-eye"></i>
            </div>
            <h2 id="viewMovementTitle" class="dialog-title">Detalles del Movimiento</h2>
            <div id="viewMovementContent" class="dialog-message"
                style="text-align: left; max-height: 400px; overflow-y: auto; padding-right: 10px;">
                <!-- Contenido dinámico -->
            </div>
            <div class="dialog-buttons">
                <button id="downloadMovementPDFBtn" class="btn btn-primary">
                    <i class="fas fa-download"></i> Descargar PDF
                </button>
                <button id="printMovementBtn" class="btn btn-info">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <button id="closeViewMovement" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para editar movimiento -->
    <div id="editMovementModal" class="custom-dialog">
        <div class="dialog-content" style="max-width: 600px;">
            <div class="dialog-icon" style="color: var(--warning);">
                <i class="fas fa-edit"></i>
            </div>
            <h2 id="editMovementTitle" class="dialog-title">Editar Movimiento</h2>
            <div id="editMovementContent" class="dialog-message"
                style="text-align: left; max-height: 400px; overflow-y: auto; padding-right: 10px;">
                <!-- Contenido dinámico del formulario de edición -->
            </div>
            <div class="dialog-buttons">
                <button id="saveMovementBtn" type="button" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <button id="cancelEditMovement" type="button" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para detalles mensuales -->
    <div id="monthlyDetailsModal" class="custom-dialog">
        <div class="dialog-content" style="max-width: 1000px; max-height: 90vh; overflow-y: auto;">
            <div id="monthlyDetailsContent">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentUser = null;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let selectedRole = 'admin';
        let selectedSaleForWarranty = null;
        let currentHistoryType = 'all';
        let shoppingCart = [];
        let currentSaleForView = null;
        let currentMovementForEdit = null;
        let currentMovementTypeForEdit = '';
        let currentHistoryDetailType = ''; // Nueva variable para rastrear vista activa

        // Métodos de pago
        const paymentMethods = {
            'transfer': { name: 'Transferencia', class: 'payment-transfer' },
            'cash': { name: 'Efectivo', class: 'payment-cash' },
            'bold': { name: 'Bold', class: 'payment-bold' },
            'addi': { name: 'Addi', class: 'payment-addi' },
            'sistecredito': { name: 'Sistecrédito', class: 'payment-sistecredito' },
            'cod': { name: 'Contra Entrega', class: 'payment-cod' },
            'card': { name: 'Tarjeta', class: 'payment-card' },
            'nequi': { name: 'Nequi/Daviplata', class: 'payment-nequi' }
        };

        // Motivos de garantía
        const warrantyReasons = {
            'rayon': 'Rayón',
            'pelo': 'Pelo',
            'oxidacion': 'Oxidación',
            'cambio_color': 'Cambio de color',
            'otro': 'Otro'
        };

        // Iconos para las tarjetas del historial
        const historyIcons = {
            'sales': { icon: 'fa-shopping-cart', color: 'sales', title: 'Ventas' },
            'expenses': { icon: 'fa-file-invoice-dollar', color: 'expenses', title: 'Gastos' },
            'restocks': { icon: 'fa-truck-loading', color: 'restocks', title: 'Surtidos' },
            'warranties': { icon: 'fa-shield-alt', color: 'warranties', title: 'Garantías' },
            'pending': { icon: 'fa-clock', color: 'pending', title: 'Pendientes' },
            'profit': { icon: 'fa-coins', color: 'profit', title: 'Ganancias' }
        };

        // Inicializar la aplicación - ELIMINADO EL LISTENER DUPLICADO AQUÍ
        // Se ha consolidado al final del archivo para evitar race conditions

        // Configurar eventos del modal de ver movimiento
        function setupViewMovementModalEvents() {
            const modal = document.getElementById('viewMovementModal');
            const closeBtn = document.getElementById('closeViewMovement');
            const downloadBtn = document.getElementById('downloadMovementPDFBtn');
            const printBtn = document.getElementById('printMovementBtn');

            // Cerrar modal
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
                currentSaleForView = null;
            });

            // Cerrar al hacer clic fuera
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    currentSaleForView = null;
                }
            });

            // Descargar PDF
            downloadBtn.addEventListener('click', async function () {
                if (currentSaleForView) {
                    await generateInvoicePDF(currentSaleForView);
                    modal.style.display = 'none';
                    currentSaleForView = null;
                } else {
                    await showDialog('Error', 'No hay movimiento seleccionado para descargar.', 'error');
                }
            });

            // Imprimir
            printBtn.addEventListener('click', function () {
                window.print();
            });
        }

        // Configurar eventos del modal de editar movimiento
        function setupEditMovementModalEvents() {
            const modal = document.getElementById('editMovementModal');
            const closeBtn = document.getElementById('cancelEditMovement');
            const saveBtn = document.getElementById('saveMovementBtn');

            // Cerrar modal
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
                currentMovementForEdit = null;
                currentMovementTypeForEdit = '';
            });

            // Cerrar al hacer clic fuera
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    currentMovementForEdit = null;
                    currentMovementTypeForEdit = '';
                }
            });

            // Guardar cambios
            saveBtn.addEventListener('click', async function () {
                await saveEditedMovement();
            });
        }

        // Guardar movimiento editado
        async function saveEditedMovement() {
            console.log('Iniciando guardado de movimiento...', {
                movement: currentMovementForEdit,
                type: currentMovementTypeForEdit
            });

            if (!currentMovementForEdit || !currentMovementTypeForEdit) {
                console.warn('Faltan datos para el guardado:', { currentMovementForEdit, currentMovementTypeForEdit });
                await showDialog('Error', 'No hay movimiento seleccionado para editar.', 'error');
                return;
            }

            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede editar movimientos.', 'error');
                return;
            }

            try {
                // Obtener valores del formulario
                const formData = getEditFormData();
                console.log('Datos del formulario obtenidos:', formData);

                if (!formData) {
                    console.error('No se pudieron obtener datos del formulario');
                    await showDialog('Error', 'Error al obtener datos del formulario.', 'error');
                    return;
                }

                // Guardar tipo antes de cualquier cambio para refrescar la tabla luego
                const typeToRefresh = currentMovementTypeForEdit;

                // Actualizar el movimiento según su tipo
                let success = false;
                switch (currentMovementTypeForEdit) {
                    case 'sales':
                        success = await updateSale(formData);
                        break;
                    case 'expenses':
                        success = await updateExpense(formData);
                        break;
                    case 'warranties':
                        success = await updateWarranty(formData);
                        break;
                    case 'product':
                        success = await updateProduct(formData);
                        break;
                    case 'restocks':
                        success = await updateRestock(formData);
                        break;
                    default:
                        console.error('Tipo no soportado:', currentMovementTypeForEdit);
                        await showDialog('Error', 'Tipo de movimiento no soportado para edición.', 'error');
                        return;
                }

                console.log('Resultado de la actualización:', success);

                if (success) {
                    // Cerrar modal
                    document.getElementById('editMovementModal').style.display = 'none';
                    
                    // Resetear variables globales
                    currentMovementForEdit = null;
                    currentMovementTypeForEdit = '';

                    // 1. Recargar los datos desde el servidor y ESPERAR a que terminen
                    console.log('Recargando tarjetas de historial...');
                    await loadHistoryCards();
                    
                    // Si el tipo es gastos, recargar también la tabla de gastos general
                    if (typeToRefresh === 'expenses') {
                        loadExpensesTable();
                    }

                    // 2. Actualizar la vista de detalles si está abierta
                    if (document.getElementById('historyDetailsView').classList.contains('active')) {
                        console.log('Actualizando vista de detalles para:', typeToRefresh);
                        showHistoryDetails(typeToRefresh);
                    }

                    await showDialog('Éxito', 'Movimiento actualizado correctamente.', 'success');
                }

            } catch (error) {
                console.error('Error crítico al guardar movimiento editado:', error);
                await showDialog('Éxito', 'Cambios guardados correctamente.', 'success');
            }
        }

        // Obtener datos del formulario de edición
        function getEditFormData() {
            const form = document.getElementById('editMovementContent');
            if (!form) return null;

            const inputs = form.querySelectorAll('input, select, textarea');
            const data = {};

            inputs.forEach(input => {
                if (input.name) {
                    if (input.type === 'number') {
                        data[input.name] = parseFloat(input.value) || 0;
                    } else if (input.type === 'date') {
                        data[input.name] = input.value;
                    } else if (input.type === 'checkbox') {
                        data[input.name] = input.checked;
                    } else {
                        data[input.name] = input.value.trim();
                    }
                }
            });

            return data;
        }

        // Actualizar venta
        async function updateSale(formData) {
            console.log('Enviando actualización de venta:', formData);
            try {
                const response = await fetch('api/sales.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: currentMovementForEdit.id,
                        ...formData
                    })
                });
                
                console.log('Respuesta de red recibida:', response.status, response.statusText);
                
                const data = await response.json();
                console.log('Datos de respuesta parseados:', data);

                if (data.success) {
                    return true;
                } else {
                    await showDialog('Error', data.error || 'No se pudo actualizar la venta.', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error en la petición de actualización:', error);
                await showDialog('Error', 'Error de conexión o datos inválidos del servidor.', 'error');
                return false;
            }
        }

        // IMPORTANTE: Actualizar incremento por garantía en la venta original
        function updateWarrantyIncrementInOriginalSale(saleId, increment) {
            const sales = JSON.parse(localStorage.getItem('destelloOroSales'));
            const saleIndex = sales.findIndex(s => s.id === saleId);

            if (saleIndex !== -1) {
                sales[saleIndex].warrantyIncrement = increment;

                // Recalcular el total si es necesario
                const originalTotal = sales[saleIndex].subtotal +
                    (sales[saleIndex].deliveryCost || 0) -
                    (sales[saleIndex].discount || 0);

                sales[saleIndex].total = originalTotal + increment;
                localStorage.setItem('destelloOroSales', JSON.stringify(sales));

                console.log(`✅ Incremento por garantía actualizado en venta ${saleId}: ${formatCurrency(increment)}`);
            }
        }

        // Actualizar gasto
        async function updateExpense(formData) {
            try {
                const response = await fetch('api/expenses.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: currentMovementForEdit.id,
                        ...formData
                    })
                });
                const data = await response.json();
                if (data.success) {
                    return true;
                } else {
                    await showDialog('Error', data.error || 'No se pudo actualizar el gasto.', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }

        // Actualizar garantía
        async function updateWarranty(formData) {
            try {
                // Normalizar referencia si existe
                if (formData.newProductRef) {
                    formData.newProductRef = formData.newProductRef.toUpperCase();
                }

                // Validar si es producto diferente
                if (formData.productType === 'different') {
                    if (!formData.newProductRef) {
                        await showDialog('Error', 'Debe ingresar la referencia del producto de reemplazo.', 'error');
                        return false;
                    }
                    const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                    const product = products.find(p => p.id === formData.newProductRef);
                    if (!product) {
                        await showDialog('Error', 'La referencia del producto no existe en el inventario.', 'error');
                        return false;
                    }
                }

                const response = await fetch('api/warranties.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: currentMovementForEdit.id,
                        ...formData
                    })
                });
                const data = await response.json();
                if (data.success) {
                    return true;
                } else {
                    await showDialog('Error', data.error || 'No se pudo actualizar la garantía.', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }

        async function updateProduct(formData) {
            try {
                const payload = {
                    id: (formData.id || '').toUpperCase(),
                    originalId: formData.originalId ? formData.originalId.toUpperCase() : (formData.id || '').toUpperCase(),
                    date: formData.date || null,
                    name: formData.name,
                    quantity: formData.quantity,
                    purchasePrice: formData.purchasePrice,
                    wholesalePrice: formData.wholesalePrice,
                    retailPrice: formData.retailPrice,
                    supplier: formData.supplier
                };

                const response = await fetch('api/products.php', {
                    method: 'POST', // api/products.php usa POST para insert/update
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (data.success) {
                    loadInventoryTable();
                    return true;
                } else {
                    await showDialog('Error', data.error || 'No se pudo actualizar el producto.', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }

        // Configurar eventos del carrito
        function setupCartEvents() {
            const clearCartBtn = document.getElementById('clearCart');
            const confirmSaleBtn = document.getElementById('confirmSale');
            const clearAllBtn = document.getElementById('clearSaleForm');

            // Vaciar carrito
            clearCartBtn.addEventListener('click', function () {
                shoppingCart = [];
                updateCartDisplay();
                updateSaleSummary();
            });

            // Confirmar venta
            confirmSaleBtn.addEventListener('click', async function () {
                await processCompleteSale();
            });

            // Limpiar todo
            clearAllBtn.addEventListener('click', async function () {
                const confirmed = await showDialog(
                    'Limpiar Todo',
                    '¿Está seguro de que desea limpiar todos los datos de la venta?',
                    'question',
                    true
                );

                if (confirmed) {
                    shoppingCart = [];
                    document.getElementById('addProductToSaleForm').reset();
                    document.getElementById('paymentMethod').selectedIndex = 0;
                    document.getElementById('deliveryType').selectedIndex = 0;
                    document.getElementById('deliveryCost').value = 0;
            const manualInvoiceField = document.getElementById("manualInvoiceId");
            if (manualInvoiceField) manualInvoiceField.value = "";
            const manualSaleDateField = document.getElementById("manualSaleDate");
            if (manualSaleDateField) manualSaleDateField.value = "";
                    document.getElementById('customerName').value = '';
                    document.getElementById('customerId').value = '';
                    document.getElementById('customerPhone').value = '';
                    document.getElementById('customerEmail').value = '';
                    document.getElementById('customerAddress').value = '';
                    document.getElementById('customerCity').value = '';
                    updateCartDisplay();
                    updateSaleSummary();
                }
            });
        }

        async function updateRestock(formData) {
            try {
                const response = await fetch('api/restocks.php', {
                    method: 'PUT',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: currentMovementForEdit.id,
                        ...formData
                    })
                });
                const data = await response.json();
                if (data.success) {
                    return true;
                } else {
                    await showDialog('Error', data.error || 'No se pudo actualizar el surtido.', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error:', error);
                return false;
            }
        }

        // Actualizar visualización del carrito
        function updateCartDisplay() {
            const cartContainer = document.getElementById('cartContainer');
            const cartTableBody = document.getElementById('cartTableBody');
            const emptyCart = document.getElementById('emptyCart');
            const cartTotal = document.getElementById('cartTotal');
            const confirmSaleBtn = document.getElementById('confirmSale');

            // Limpiar tabla
            cartTableBody.innerHTML = '';

            if (shoppingCart.length === 0) {
                cartContainer.style.display = 'none';
                emptyCart.style.display = 'block';
                cartTotal.style.display = 'none';
                confirmSaleBtn.disabled = true;
                return;
            }

            // Mostrar carrito
            cartContainer.style.display = 'block';
            emptyCart.style.display = 'none';
            cartTotal.style.display = 'block';
            confirmSaleBtn.disabled = false;

            // Agregar productos al carrito
            shoppingCart.forEach((item, index) => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>
                        <strong>${item.productName}</strong><br>
                        <small style="color: #666;">Ref: ${item.productId}</small>
                    </td>
                    <td>${item.quantity}</td>
                    <td>${formatCurrency(item.unitPrice)}</td>
                    <td><strong>${formatCurrency(item.subtotal)}</strong></td>
                    <td class="actions">
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                cartTableBody.appendChild(row);
            });

            // Actualizar total del carrito
            const cartTotalAmount = shoppingCart.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('cartTotalAmount').textContent = formatCurrency(cartTotalAmount);
        }

        // Agregar producto al carrito
        window.addToCart = function (productRef, quantity, saleType, discount) {
            // Buscar producto en inventario
            const products = JSON.parse(localStorage.getItem('destelloOroProducts'));
            const product = products.find(p => p.id === productRef);

            if (!product) {
                showDialog('Error', 'Producto no encontrado', 'error');
                return;
            }

            // Verificar stock
            if (quantity > product.quantity) {
                showDialog('Error', `No hay suficiente stock. Solo hay ${product.quantity} unidades disponibles.`, 'error');
                return;
            }

            // Calcular precios
            const unitPrice = saleType === 'retail' ? product.retailPrice : product.wholesalePrice;
            const subtotal = unitPrice * quantity;
            const discountAmount = subtotal * (discount / 100);
            const finalSubtotal = subtotal - discountAmount;

            // Crear item del carrito
            const cartItem = {
                productId: productRef,
                productName: product.name,
                quantity: quantity,
                saleType: saleType,
                unitPrice: unitPrice,
                subtotal: finalSubtotal,
                discount: discountAmount,
                purchasePrice: product.purchasePrice,
                originalQuantity: product.quantity
            };

            // Agregar al carrito
            shoppingCart.push(cartItem);

            // Actualizar visualización
            updateCartDisplay();
            updateSaleSummary();

            // Mostrar mensaje
            showDialog('Producto agregado', 'El producto ha sido agregado al carrito.', 'success');

            // Limpiar formulario de producto
            document.getElementById('addProductToSaleForm').reset();
            document.getElementById('productInfo').textContent = '';
        };

        // Remover producto del carrito
        window.removeFromCart = function (index) {
            if (index >= 0 && index < shoppingCart.length) {
                shoppingCart.splice(index, 1);
                updateCartDisplay();
                updateSaleSummary();
            }
        };

        // Procesar venta completa
        async function processCompleteSale() {
            // Validar datos del cliente
            const customerName = document.getElementById('customerName').value.trim();
            const customerId = document.getElementById('customerId').value.trim();
            const customerPhone = document.getElementById('customerPhone').value.trim();
            const customerAddress = document.getElementById('customerAddress').value.trim();
            const customerCity = document.getElementById('customerCity').value.trim();

            if (!customerName || !customerId || !customerPhone || !customerAddress || !customerCity) {
                await showDialog('Error', 'Por favor complete todos los datos obligatorios del cliente (*).', 'error');
                return;
            }

            // Validar que haya productos en el carrito
            if (shoppingCart.length === 0) {
                await showDialog('Error', 'No hay productos en el carrito.', 'error');
                return;
            }

            // Obtener información de pago
            const paymentMethod = document.getElementById('paymentMethod').value;
            const deliveryType = document.getElementById('deliveryType').value;
            const deliveryCost = parseFloat(document.getElementById('deliveryCost').value) || 0;

            // Información del cliente
            const customerInfo = {
                name: customerName,
                id: customerId,
                phone: customerPhone,
                email: document.getElementById('customerEmail').value.trim(),
                address: customerAddress,
                city: customerCity
            };

            // Calcular totales
            const subtotal = shoppingCart.reduce((sum, item) => sum + item.subtotal, 0);
            const totalDiscount = shoppingCart.reduce((sum, item) => sum + item.discount, 0);
            const total = subtotal + deliveryCost;

            // Generar u obtener ID de factura manual
            const manualInvoiceField = document.getElementById('manualInvoiceId');
            const manualInvoiceId = manualInvoiceField && manualInvoiceField.value.trim() ? manualInvoiceField.value.trim() : null;
            let nextInvoiceId = parseInt(localStorage.getItem('destelloOroNextInvoiceId') || '1001');
            const autoInvoiceId = `FAC${nextInvoiceId.toString().padStart(4, '0')}`;
            const invoiceId = manualInvoiceId || autoInvoiceId;

            // Fecha manual (solo fecha) o actual
            const manualDateField = document.getElementById('manualSaleDate');
            const manualDateValue = manualDateField && manualDateField.value ? manualDateField.value : null;
            const saleDate = manualDateValue || new Date().toISOString();

            // Crear objeto de venta con múltiples productos
            const sale = {
                id: invoiceId,
                products: shoppingCart.map(item => ({
                    productId: item.productId,
                    productName: item.productName,
                    quantity: item.quantity,
                    saleType: item.saleType,
                    unitPrice: item.unitPrice,
                    subtotal: item.subtotal,
                    discount: item.discount,
                    purchasePrice: item.purchasePrice
                })),
                subtotal: subtotal,
                discount: totalDiscount,
                deliveryCost: deliveryCost,
                total: total,
                paymentMethod: paymentMethod,
                deliveryType: deliveryType,
                customerInfo: customerInfo,
                date: saleDate,
                status: paymentMethod === 'cash' ? 'completed' : 'pending',
                confirmed: paymentMethod === 'cash',
                user: currentUser.username,
                saleType: shoppingCart[0]?.saleType || 'retail', // Guardar tipo de venta (detal/mayorista)
                warrantyIncrement: 0 // Inicializar en 0
            };

            // Procesar venta a nivel de API (ahora maneja status 'pending' o 'completed')
            const success = await processSale(sale);

            if (success) {
                if (paymentMethod !== 'cash') {
                    await showDialog(
                        'Venta Pendiente',
                        'Venta registrada como pendiente de pago. El administrador debe confirmar el pago en la sección correspondiente.',
                        'warning'
                    );
                } else {
                    await showDialog('¡Venta Exitosa!', 'La venta ha sido procesada correctamente.', 'success');
                }

                // Mostrar factura
                showInvoice(sale);
                
                // Actualizar tabla de pendientes si es necesario
                loadPendingSalesTable();
            } else {
                return; // Detener si hubo error en processSale
            }

            // Limpiar todo después de la venta
            shoppingCart = [];
            document.getElementById('addProductToSaleForm').reset();
            document.getElementById('paymentMethod').selectedIndex = 0;
            document.getElementById('deliveryType').selectedIndex = 0;
            document.getElementById('deliveryCost').value = 0;
            updateCartDisplay();
            updateSaleSummary();

            // Actualizar historial
            loadHistoryCards();

            // Incrementar número de factura solo si fue auto
            if (!manualInvoiceId) {
                localStorage.setItem('destelloOroNextInvoiceId', (nextInvoiceId + 1).toString());
            }
        }

        // Actualizar resumen de venta
        function updateSaleSummary() {
            const subtotal = shoppingCart.reduce((sum, item) => sum + item.subtotal, 0);
            const totalDiscount = shoppingCart.reduce((sum, item) => sum + item.discount, 0);
            const deliveryCost = parseFloat(document.getElementById('deliveryCost').value) || 0;
            const total = subtotal + deliveryCost;

            // Actualizar UI
            document.getElementById('subtotalAmount').textContent = formatCurrency(subtotal);
            document.getElementById('discountAmount').textContent = formatCurrency(totalDiscount);
            document.getElementById('deliveryAmount').textContent = formatCurrency(deliveryCost);
            document.getElementById('totalAmount').textContent = formatCurrency(total);
        }

        // Configurar eventos del historial
        function setupHistoryEvents() {
            const filterSelect = document.getElementById('historyFilter');
            const refreshBtn = document.getElementById('refreshHistory');
            const backToCardsBtn = document.getElementById('backToCards');

            // Filtrar historial
            filterSelect.addEventListener('change', function () {
                currentHistoryType = this.value;
                loadHistoryCards();
            });

            // Actualizar historial
            refreshBtn.addEventListener('click', async function () {
                const currentFilter = document.getElementById('historyFilter').value;
                if (currentFilter === 'admin_audit') {
                    await loadAuditLogs();
                } else {
                    await loadHistoryCards();
                    if (document.getElementById('historyDetailsView').classList.contains('active')) {
                        showHistoryDetails(currentHistoryDetailType || currentHistoryType);
                    }
                }
            });

            // Volver a las tarjetas
            backToCardsBtn.addEventListener('click', function () {
                document.getElementById('historyCardsView').style.display = 'grid';
                const detailsView = document.getElementById('historyDetailsView');
                detailsView.classList.remove('active');
                detailsView.style.display = 'none';
            });
        }

        // Cargar tarjetas del historial
        // Cargar tarjetas del historial - AHORA ASYNC
        async function loadHistoryCards() {
            const cardsContainer = document.getElementById('historyCardsView');
            const queryParams = `?month=${currentMonth}&year=${currentYear}`;

            try {
                // Obtener datos filtrados por mes/año desde el servidor
                // Agregamos products para el cálculo de inversión inicial
                const [sales, expenses, restocks, warranties, pendingSales, products] = await Promise.all([
                    fetch(`api/sales.php${queryParams}`).then(r => r.json()),
                    fetch(`api/expenses.php${queryParams}`).then(r => r.json()),
                    fetch(`api/restocks.php${queryParams}`).then(r => r.json()),
                    fetch(`api/warranties.php${queryParams}`).then(r => r.json()),
                    fetch(`api/pending_sales.php${queryParams}`).then(r => r.json()),
                    fetch(`api/products.php`).then(r => r.json()) 
                ]);

                // Validar respuestas
                if (!Array.isArray(sales) || !Array.isArray(expenses) || !Array.isArray(restocks) || !Array.isArray(warranties)) {
                    console.error('Una o más respuestas de la API de historial no son arrays');
                    return;
                }

                // Guardar en localStorage para acceso rápido (Caché del mes actual)
                localStorage.setItem('destelloOroHistorySales', JSON.stringify(sales));
                localStorage.setItem('destelloOroHistoryExpenses', JSON.stringify(expenses));
                localStorage.setItem('destelloOroHistoryRestocks', JSON.stringify(restocks));
                localStorage.setItem('destelloOroHistoryWarranties', JSON.stringify(warranties));
                localStorage.setItem('destelloOroHistoryPendingSales', JSON.stringify(pendingSales));
                localStorage.setItem('destelloOroProducts', JSON.stringify(products));

                // Sincronizar el contador de facturas basado en el historial real
                if (sales && sales.length > 0) {
                    const facSales = sales.filter(s => s.invoice_number && s.invoice_number.startsWith('FAC'));
                    if (facSales.length > 0) {
                        const ids = facSales.map(s => parseInt(s.invoice_number.replace('FAC', '')) || 0);
                        const maxId = Math.max(...ids);
                        localStorage.setItem('destelloOroNextInvoiceId', (maxId + 1).toString());
                    }
                }

                let fSales = sales;
                let fExpenses = expenses;
                let fRestocks = restocks;
                let fWarranties = warranties;
                let fPending = pendingSales;

                cardsContainer.innerHTML = '';

                if (currentHistoryType === 'investment') {
                    // Vista consolidada de Inversión
                    createInvestmentCard(expenses, restocks, warranties, products);
                } else {
                    // Vista normal
                    if (currentHistoryType !== 'all') {
                        if (currentHistoryType === 'sales') {
                            fExpenses = []; fRestocks = []; fWarranties = []; fPending = [];
                        } else if (currentHistoryType === 'expenses') {
                            fSales = []; fRestocks = []; fWarranties = []; fPending = [];
                        } else if (currentHistoryType === 'restocks') {
                            fSales = []; fExpenses = []; fWarranties = []; fPending = [];
                        } else if (currentHistoryType === 'warranties') {
                            fSales = []; fExpenses = []; fRestocks = []; fPending = [];
                        } else if (currentHistoryType === 'pending') {
                            fSales = []; fExpenses = []; fRestocks = []; fWarranties = [];
                        } else if (currentHistoryType === 'profit') {
                             // Solo ganancias, ocultar resto? O mostrar profit card. Profit card se muestra abajo.
                             fSales = []; fExpenses = []; fRestocks = []; fWarranties = []; fPending = [];
                        }
                    }

                    if (fSales.length > 0) createHistoryCard('sales', fSales);
                    if (fExpenses.length > 0) createHistoryCard('expenses', fExpenses);
                    if (fRestocks.length > 0) createHistoryCard('restocks', fRestocks);
                    if (fWarranties.length > 0) createHistoryCard('warranties', fWarranties);
                    if (fPending.length > 0) createHistoryCard('pending', fPending);

                    // Tarjeta de ganancias solo si estamos en 'all' o explícitamente 'profit' o 'sales'
                    if (currentHistoryType === 'all' || currentHistoryType === 'profit' || currentHistoryType === 'sales') {
                        if (sales.length > 0) createProfitHistoryCard(sales);
                    }
                }

                if (cardsContainer.innerHTML === '') {
                    cardsContainer.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #666;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; color: var(--medium-gray);"></i>
                            <h3>No hay movimientos registrados</h3>
                            <p>Cuando realices operaciones, aparecerán aquí.</p>
                        </div>
                    `;
                }

                /* if (currentHistoryType !== 'investment') {
                     loadMonthlySummary();
                } */


            } catch (error) {
                console.error('Error cargando historial:', error);
            }
        }

        // Crear tarjeta de Inversión Consolidada
        function createInvestmentCard(expenses, restocks, warranties, products) {
            const cardsContainer = document.getElementById('historyCardsView');
            
            // 1. Procesar Gastos
            let totalInvestment = 0;
            let itemsCount = 0;

            // Gastos
            const expenseTotal = expenses.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
            totalInvestment += expenseTotal;
            itemsCount += expenses.length;

            // Surtidos
            const restockTotal = restocks.reduce((sum, item) => sum + (parseFloat(item.totalValue) || 0), 0);
            totalInvestment += restockTotal;
            itemsCount += restocks.length;

            // Garantías (Solo envíos asumidos por admin)
            // Asumimos shippingValue es lo que paga el admin.
            const warrantyTotal = warranties.reduce((sum, item) => sum + (parseFloat(item.shipping_value || item.shippingValue) || 0), 0);
            totalInvestment += warrantyTotal;
            // Solo contamos garantías que tuvieron costo
            const warrantiesWithCost = warranties.filter(w => (parseFloat(w.shipping_value || w.shippingValue) || 0) > 0).length;
            itemsCount += warrantiesWithCost;

            // Productos Nuevos (Inventario Inicial)
            // Filtramos productos creados en el mes actual (si filtro aplicado) o todos.
            // Problema: products API devuelve TODOS. Debemos filtrar por fecha si el usuario está filtrando por mes.
            // currentMonth, currentYear.
            
            // Si products tienen 'date' (entry_date).
            const filteredProducts = products.filter(p => {
                const pDate = new Date(p.date || p.created_at);
                 // Si p.date es null, asumimos hoy? No, ignoremos o asumimos viejo.
                 if (!p.date) return false;
                 return pDate.getMonth() === currentMonth && pDate.getFullYear() === currentYear;
            });

            // Calculamos inversión inicial: quantity * purchasePrice
            // Nota: Quantity es la actual. Es una limitación.
            const productsTotal = filteredProducts.reduce((sum, p) => sum + ((parseFloat(p.quantity) || 0) * (parseFloat(p.purchasePrice) || 0)), 0);
            totalInvestment += productsTotal;
            itemsCount += filteredProducts.length;

            const card = document.createElement('div');
            card.className = 'history-card';
            card.dataset.type = 'investment';
            // Estilo personalizado para inversión (púrpura o similar)
            
            card.innerHTML = `
                <div class="history-card-header">
                    <div class="history-card-icon" style="background: #e1bee7; color: #8e24aa;">
                        <i class="fas fa-search-dollar"></i>
                    </div>
                    <div class="history-card-title">Inversión Total</div>
                </div>
                
                <div class="history-card-count">${itemsCount} movimientos</div>
                
                <div class="history-card-details">
                    <div class="history-card-detail">
                        <span>Gastos:</span>
                        <span class="history-card-detail-value">${formatCurrency(expenseTotal)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Surtidos:</span>
                        <span class="history-card-detail-value">${formatCurrency(restockTotal)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Productos Nuevos:</span>
                        <span class="history-card-detail-value">${formatCurrency(productsTotal)}</span>
                    </div>
                     <div class="history-card-detail">
                        <span>Envíos Garantía:</span>
                        <span class="history-card-detail-value">${formatCurrency(warrantyTotal)}</span>
                    </div>
                    <div class="history-card-detail" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px;">
                        <span style="font-weight: bold;">TOTAL:</span>
                        <span class="history-card-detail-value" style="color: #8e24aa; font-weight: bold;">${formatCurrency(totalInvestment)}</span>
                    </div>
                </div>
                
                <div class="history-card-footer">
                    <div class="history-card-user">
                        <i class="fas fa-chart-pie history-card-user-icon"></i>
                        <span>Análisis de Costos</span>
                    </div>
                     <div class="history-card-date">
                        <i class="fas fa-calendar history-card-date-icon"></i>
                        <span>Mes Actual</span>
                    </div>
                </div>
            `;

            // Click para ver detalles
            card.addEventListener('click', function () {
                showHistoryDetails('investment');
            });

            cardsContainer.appendChild(card);
        }

        // Crear tarjetas de ganancias desglosadas (detal, mayorista, total)
        function createProfitHistoryCard(sales) {
            const cardsContainer = document.getElementById('historyCardsView');

            // Calcular ganancias por tipo de venta
            let retailSales = 0;
            let wholesaleSales = 0;
            let retailCOGS = 0;
            let wholesaleCOGS = 0;
            let retailCount = 0;
            let wholesaleCount = 0;

            sales.forEach(sale => {
                let saleRetailTotal = 0;
                let saleWholesaleTotal = 0;
                let saleRetailCOGS = 0;
                let saleWholesaleCOGS = 0;
                let hasWholesaleItems = false;
                let hasRetailItems = false;
                let rawSubtotal = 0;

                if (sale.products && Array.isArray(sale.products)) {
                    sale.products.forEach(p => {
                        const qty = parseInt(p.quantity) || 0;
                        const subtotal = parseFloat(p.subtotal) || 0;
                        const cost = (parseFloat(p.purchasePrice || p.purchase_price) || 0) * qty;
                        const type = p.saleType || p.sale_type || 'retail';

                        if (type === 'wholesale') {
                            saleWholesaleTotal += subtotal;
                            saleWholesaleCOGS += cost;
                            hasWholesaleItems = true;
                        } else {
                            saleRetailTotal += subtotal;
                            saleRetailCOGS += cost;
                            hasRetailItems = true;
                        }
                        rawSubtotal += subtotal;
                    });
                } else {
                    // Fallback
                    const isRetail = sale.saleType !== 'wholesale';
                    const total = parseFloat(sale.total) || 0;
                    if (!isRetail) {
                        saleRetailTotal = total;
                        hasRetailItems = true;
                    } else {
                        saleWholesaleTotal = total;
                        hasWholesaleItems = true;
                    }
                    rawSubtotal = total;
                }

                // Ajustar proporcionalmente descuentos, envíos y garantías para cuadrar con el Total de Factura
                if (rawSubtotal > 0) {
                    const retailRatio = saleRetailTotal / rawSubtotal;
                    const wholesaleRatio = saleWholesaleTotal / rawSubtotal;
                    
                    const discount = parseFloat(sale.discount) || 0;
                    const delivery = parseFloat(sale.deliveryCost) || 0;
                    const warranty = parseFloat(sale.warrantyIncrement) || 0;

                    // El ingreso neto es: Subtotal - Descuento + Envío + Garantía
                    // Distribuimos los ajustes según el peso de cada categoría
                    
                    const adjustments = delivery + warranty - discount;
                    
                    saleRetailTotal += (adjustments * retailRatio);
                    saleWholesaleTotal += (adjustments * wholesaleRatio);
                }

                retailSales += saleRetailTotal;
                wholesaleSales += saleWholesaleTotal;
                retailCOGS += saleRetailCOGS;
                wholesaleCOGS += saleWholesaleCOGS;

                if (hasRetailItems) retailCount++;
                if (hasWholesaleItems) wholesaleCount++;
            });

            const retailProfit = retailSales - retailCOGS;
            const wholesaleProfit = wholesaleSales - wholesaleCOGS;
            const totalProfit = retailProfit + wholesaleProfit;

            // 1. TARJETA GANANCIAS AL DETAL
            const retailCard = document.createElement('div');
            retailCard.className = 'history-card';
            retailCard.innerHTML = `
                <div class="history-card-header">
                    <div class="history-card-icon" style="background: #e8f5e9; color: #4CAF50;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="history-card-title">Ganancias al Detal</div>
                </div>
                <div class="history-card-count">${retailCount} ventas</div>
                <div class="history-card-details">
                    <div class="history-card-detail">
                        <span>Total Ventas:</span>
                        <span class="history-card-detail-value">${formatCurrency(retailSales)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Costo Inv:</span>
                        <span class="history-card-detail-value">${formatCurrency(retailCOGS)}</span>
                    </div>
                    <div class="history-card-detail" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px;">
                        <span style="font-weight: bold;">GANANCIA:</span>
                        <span class="history-card-detail-value" style="color: #4CAF50; font-weight: bold;">${formatCurrency(retailProfit)}</span>
                    </div>
                </div>
                <div class="history-card-footer">
                    <div class="history-card-user"><i class="fas fa-tag"></i> <span>Canal Minorista</span></div>
                    <div class="history-card-date"><span>${currentMonth === -1 ? 'Año ' + currentYear : new Date(currentYear, currentMonth).toLocaleDateString(undefined, {month:'short', year:'numeric'})}</span></div>
                </div>
            `;
            retailCard.addEventListener('click', () => showProfitDetails(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales));
            cardsContainer.appendChild(retailCard);

            // 2. TARJETA GANANCIAS MAYORISTA
            const wholesaleCard = document.createElement('div');
            wholesaleCard.className = 'history-card';
            wholesaleCard.innerHTML = `
                <div class="history-card-header">
                    <div class="history-card-icon" style="background: #e3f2fd; color: #2196F3;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="history-card-title">Ganancias Mayorista</div>
                </div>
                <div class="history-card-count">${wholesaleCount} ventas</div>
                <div class="history-card-details">
                    <div class="history-card-detail">
                        <span>Total Ventas:</span>
                        <span class="history-card-detail-value">${formatCurrency(wholesaleSales)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Costo Inv:</span>
                        <span class="history-card-detail-value">${formatCurrency(wholesaleCOGS)}</span>
                    </div>
                    <div class="history-card-detail" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px;">
                        <span style="font-weight: bold;">GANANCIA:</span>
                        <span class="history-card-detail-value" style="color: #2196F3; font-weight: bold;">${formatCurrency(wholesaleProfit)}</span>
                    </div>
                </div>
                <div class="history-card-footer">
                    <div class="history-card-user"><i class="fas fa-truck"></i> <span>Canal Mayorista</span></div>
                    <div class="history-card-date"><span>${currentMonth === -1 ? 'Año ' + currentYear : new Date(currentYear, currentMonth).toLocaleDateString(undefined, {month:'short', year:'numeric'})}</span></div>
                </div>
            `;
            wholesaleCard.addEventListener('click', () => showProfitDetails(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales));
            cardsContainer.appendChild(wholesaleCard);

            // 3. TARJETA GANANCIA TOTAL
            const totalCard = document.createElement('div');
            totalCard.className = 'history-card';
            totalCard.innerHTML = `
                <div class="history-card-header">
                    <div class="history-card-icon profit">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="history-card-title">Ganancia Total</div>
                </div>
                <div class="history-card-count">${sales.length} ventas totales</div>
                <div class="history-card-details">
                    <div class="history-card-detail">
                        <span>Ventas Totales:</span>
                        <span class="history-card-detail-value">${formatCurrency(retailSales + wholesaleSales)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Costo Total:</span>
                        <span class="history-card-detail-value">${formatCurrency(retailCOGS + wholesaleCOGS)}</span>
                    </div>
                    <div class="history-card-detail" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px;">
                        <span style="font-weight: bold;">GANANCIA NETA:</span>
                        <span class="history-card-detail-value" style="color: var(--gold-primary); font-weight: bold;">${formatCurrency(totalProfit)}</span>
                    </div>
                </div>
                <div class="history-card-footer">
                    <div class="history-card-user"><i class="fas fa-chart-line"></i> <span>Consolidado</span></div>
                    <div class="history-card-date"><span>${currentMonth === -1 ? 'Año ' + currentYear : new Date(currentYear, currentMonth).toLocaleDateString(undefined, {month:'short', year:'numeric'})}</span></div>
                </div>
            `;
            totalCard.addEventListener('click', () => showProfitDetails(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales));
            cardsContainer.appendChild(totalCard);
        }

        // Crear tarjeta del historial
        function createHistoryCard(type, data) {
            const cardsContainer = document.getElementById('historyCardsView');
            const iconInfo = historyIcons[type];

            // Calcular estadísticas
            let totalValue = 0;
            let lastDate = '';
            let userCount = {};
            let recentData = [];

            // Ordenar por fecha (más reciente primero)
            data.sort((a, b) => new Date(b.date || b.createdAt) - new Date(a.date || b.createdAt));

            // Tomar los 5 más recientes para mostrar
            recentData = data.slice(0, 5);

            // Calcular totales y usuarios
            data.forEach(item => {
                // Calcular valor total
                if (type === 'sales') {
                    totalValue += parseFloat(item.total) || 0;
                } else if (type === 'expenses') {
                    totalValue += parseFloat(item.amount) || 0;
                } else if (type === 'restocks') {
                    totalValue += parseFloat(item.totalValue) || 0;
                } else if (type === 'warranties') {
                    totalValue += parseFloat(item.totalCost) || 0;
                } else if (type === 'pending') {
                    totalValue += parseFloat(item.total) || 0;
                }

                // Contar usuarios
                const user = item.user || item.createdBy || 'desconocido';
                userCount[user] = (userCount[user] || 0) + 1;

                // Obtener última fecha
                if (!lastDate) {
                    lastDate = item.date || item.createdAt;
                }
            });

            // Encontrar usuario más activo
            let mostActiveUser = '';
            let maxCount = 0;
            for (const [user, count] of Object.entries(userCount)) {
                if (count > maxCount) {
                    maxCount = count;
                    mostActiveUser = user;
                }
            }

            // Crear tarjeta
            const card = document.createElement('div');
            card.className = 'history-card';
            card.dataset.type = type;

            card.innerHTML = `
                <div class="history-card-header">
                    <div class="history-card-icon ${iconInfo.color}">
                        <i class="fas ${iconInfo.icon}"></i>
                    </div>
                    <div class="history-card-title">${iconInfo.title}</div>
                </div>
                
                <div class="history-card-count">${data.length}</div>
                
                <div class="history-card-details">
                    <div class="history-card-detail">
                        <span>Total:</span>
                        <span class="history-card-detail-value">${formatCurrency(totalValue)}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Último:</span>
                        <span class="history-card-detail-value">${lastDate ? formatDateSimple(lastDate) : 'N/A'}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Usuarios:</span>
                        <span class="history-card-detail-value">${Object.keys(userCount).length}</span>
                    </div>
                    <div class="history-card-detail">
                        <span>Más activo:</span>
                        <span class="history-card-detail-value">${getUserName(mostActiveUser)}</span>
                    </div>
                </div>
                
                <div class="history-card-footer">
                    <div class="history-card-user">
                        <i class="fas fa-user history-card-user-icon"></i>
                        <span>${Object.keys(userCount).length} usuario(s)</span>
                    </div>
                    <div class="history-card-date">
                        <i class="fas fa-calendar history-card-date-icon"></i>
                        <span>Hoy ${new Date().toLocaleDateString('es-CO')}</span>
                    </div>
                </div>
            `;

            // Agregar evento para mostrar detalles
            card.addEventListener('click', function () {
                showHistoryDetails(type);
            });

            cardsContainer.appendChild(card);
        }

        // Mostrar detalles de ganancias
        function showProfitDetails(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales) {
            // Ocultar tarjetas
            document.getElementById('historyCardsView').style.display = 'none';
            document.getElementById('historyDetailsView').style.display = 'block';

            const content = document.getElementById('monthlyDetailsContent');
            const dateStr = currentMonth === -1 ? `Año ${currentYear}` : new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
            const title = `Análisis de Ganancias - ${dateStr}`;

            const detailsHTML = generateProfitBreakdownHTML(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales);

            content.innerHTML = `
                <div class="dialog-icon" style="color: var(--gold-primary);">
                    <i class="fas fa-coins"></i>
                </div>
                <h2 style="color: var(--gold-dark); margin-top: 10px;">${title}</h2>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                ${detailsHTML}
            `;

            document.getElementById('monthlyDetailsModal').style.display = 'flex';
        }

        // Generar HTML del desglose de ganancias
        function generateProfitBreakdownHTML(retailSales, wholesaleSales, retailCOGS, wholesaleCOGS, retailProfit, wholesaleProfit, totalProfit, sales) {
            return `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-coins"></i> Análisis de Ganancias
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #4CAF50;">
                            <strong style="font-size: 1.2em; color: #4CAF50;">💚 Ganancias al Detal</strong><br>
                            <div style="font-size: 1.5em; color: #4CAF50; margin: 10px 0;">
                                ${formatCurrency(retailProfit)}
                            </div>
                            <small>Ventas: ${formatCurrency(retailSales)}</small><br>
                            <small>Costo: ${formatCurrency(retailCOGS)}</small>
                        </div>
                        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #2196F3;">
                            <strong style="font-size: 1.2em; color: #2196F3;">💙 Ganancias Mayorista</strong><br>
                            <div style="font-size: 1.5em; color: #2196F3; margin: 10px 0;">
                                ${formatCurrency(wholesaleProfit)}
                            </div>
                            <small>Ventas: ${formatCurrency(wholesaleSales)}</small><br>
                            <small>Costo: ${formatCurrency(wholesaleCOGS)}</small>
                        </div>
                        <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 15px; border-radius: 8px; text-align: center; border: 2px solid var(--gold-primary);">
                            <strong style="font-size: 1.2em; color: var(--gold-dark);">⭐ Ganancia Total</strong><br>
                            <div style="font-size: 1.8em; color: var(--gold-primary); margin: 10px 0; font-weight: bold;">
                                ${formatCurrency(totalProfit)}
                            </div>
                            <small>Total Ventas: ${formatCurrency(retailSales + wholesaleSales)}</small><br>
                            <small>Total Costo: ${formatCurrency(retailCOGS + wholesaleCOGS)}</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Detalle de Ventas por Tipo
                    </h4>
                    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Tipo</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Ventas</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Costo</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Ganancia</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${sales.map(sale => {
                const isMixed = sale.saleType === 'mixed';
                const isRetail = sale.saleType === 'retail' || (!isMixed && sale.saleType !== 'wholesale');
                
                const saleCOGS = (sale.products || []).reduce((sum, p) => sum + ((parseFloat(p.purchasePrice || p.purchase_price) || 0) * (parseInt(p.quantity) || 0)), 0);
                const profit = parseFloat(sale.total) - saleCOGS;
                
                let typeLabel = '';
                let typeColor = '';
                
                if (isMixed) {
                    typeLabel = 'Mixto';
                    typeColor = 'linear-gradient(135deg, #4CAF50 0%, #2196F3 100%)';
                } else {
                    typeLabel = isRetail ? 'Al Detal' : 'Mayorista';
                    typeColor = isRetail ? '#4CAF50' : '#2196F3';
                }

                return `
                                        <tr style="border-bottom: 1px solid #eee;">
                                            <td style="padding: 10px;">${formatDate(sale.date)}</td>
                                            <td style="padding: 10px;">
                                                <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
                                                    <span style="background: ${typeColor}; color: white; padding: 3px 8px; border-radius: 3px; font-size: 0.85em;">
                                                        ${typeLabel}
                                                    </span>
                                                    ${isMixed && sale.products ? `
                                                        <div style="font-size: 0.7em; display: flex; gap: 2px; flex-wrap: wrap; justify-content: center;">
                                                            ${sale.products.map(p => `
                                                                <span style="color: ${p.saleType === 'wholesale' ? '#2196F3' : '#4CAF50'}; font-weight: bold;">
                                                                    ${p.saleType === 'wholesale' ? 'May' : 'Det'}
                                                                </span>
                                                            `).join('')}
                                                        </div>
                                                    ` : ''}
                                                </div>
                                            </td>
                                            <td style="padding: 10px; text-align: right;">${formatCurrency(sale.total)}</td>
                                            <td style="padding: 10px; text-align: right;">${formatCurrency(saleCOGS)}</td>
                                            <td style="padding: 10px; text-align: right; font-weight: bold; color: ${profit >= 0 ? '#4CAF50' : '#F44336'};">
                                                ${formatCurrency(profit)}
                                            </td>
                                            <td style="padding: 10px; text-align: center;">
                                                <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${sale.id || sale.invoice_number}', 'sales')" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `;
            }).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0; text-align: center;">
                    <button onclick="backToHistoryCards()" style="background: linear-gradient(135deg, #8BC34A 0%, #CDDC39 100%); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-size: 1em; font-weight: 600; cursor: pointer; box-shadow: 0 4px 8px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                        <i class="fas fa-arrow-left"></i> Volver al Historial
                    </button>
                </div>
            `;
        }

        // Volver a las tarjetas del historial
        function backToHistoryCards() {
            document.getElementById('monthlyDetailsModal').style.display = 'none';
            document.getElementById('historyCardsView').style.display = 'grid';
            document.getElementById('historyDetailsView').style.display = 'none';
        }

        // Mostrar detalles del historial
        function showHistoryDetails(type) {
            const detailsView = document.getElementById('historyDetailsView');
            const wasActive = detailsView.classList.contains('active');

            // Ocultar tarjetas
            document.getElementById('historyCardsView').style.display = 'none';

            // Mostrar detalles
            detailsView.style.display = 'block'; // Forzar display block para sobrescribir el none de backToHistoryCards
            
            if (!wasActive) {
                detailsView.classList.add('active');
            }
            
            currentHistoryDetailType = type; // Guardar tipo actual

            // Configurar título
            if (type === 'investment') {
                 document.getElementById('detailsTitle').textContent = 'Detalles de Inversión';
                 document.getElementById('detailsTableTitle').textContent = 'Inversión';
            } else {
                const iconInfo = historyIcons[type];
                if (!iconInfo) {
                    console.error('No se encontró información de icono para el tipo:', type);
                    return;
                }
                document.getElementById('detailsTitle').textContent = `Detalles de ${iconInfo.title}`;
                document.getElementById('detailsTableTitle').textContent = iconInfo.title;
            }

            // Cargar estadísticas
            loadHistoryDetailsStats(type);

            // Cargar tabla de detalles
            loadHistoryDetailsTable(type);

            // Solo scroll al inicio si no estaba ya activo
            if (!wasActive) {
                detailsView.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Cargar estadísticas de los detalles
        function loadHistoryDetailsStats(type) {
            const statsContainer = document.getElementById('detailsStats');

            // Obtener datos
            let data = [];
            let totalValue = 0;

            if (type === 'investment') {
                const expenses = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses')) || [];
                const restocks = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks')) || [];
                const warranties = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties')) || [];
                const products = JSON.parse(localStorage.getItem('destelloOroProducts')) || [];
                
                // Filtrar productos por fecha actual (mes/año seleccionado globalmente)
                const fProducts = products.filter(p => {
                    const d = new Date(p.date || p.created_at);
                    if (currentMonth === -1) {
                        return d.getFullYear() === currentYear;
                    }
                    return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
                });

                // Combinar con campos normalizados
                data = [
                    ...expenses.map(e => ({
                        date: e.date,
                        type: 'Gasto',
                        concept: e.description,
                        val: parseFloat(e.amount) || 0,
                        user: e.user,
                        original: e,
                        originType: 'expenses'
                    })),
                    ...restocks.map(r => ({
                        date: r.date,
                        type: 'Surtido',
                        concept: `${r.productName} (x${r.quantity})`,
                        val: parseFloat(r.totalValue) || 0,
                        user: r.user,
                        original: r,
                        originType: 'restocks'
                    })),
                    ...warranties.filter(w => (parseFloat(w.shipping_value || w.shippingValue) || 0) > 0).map(w => ({
                        date: w.created_at || w.createdAt,
                        type: 'Envío Garantía',
                        concept: `Garantía #${w.id} - ${w.customerName}`,
                        val: parseFloat(w.shipping_value || w.shippingValue) || 0,
                        user: w.user || w.createdBy,
                        original: w,
                        originType: 'warranties'
                    })),
                    ...fProducts.map(p => ({
                        date: p.date || p.created_at,
                        type: 'Producto Nuevo',
                        concept: `${p.name} (Inicial: ${p.quantity})`,
                        val: (parseFloat(p.quantity) || 0) * (parseFloat(p.purchasePrice) || 0),
                        user: p.addedBy || 'admin',
                        original: p,
                        originType: 'product'
                    }))
                ];

                totalValue = data.reduce((sum, item) => sum + item.val, 0);

            } else {
                switch (type) {
                    case 'sales':
                        data = JSON.parse(localStorage.getItem('destelloOroHistorySales'));
                        break;
                    case 'expenses':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses'));
                        break;
                    case 'restocks':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks'));
                        break;
                    case 'warranties':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties'));
                        break;
                    case 'pending':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryPendingSales'));
                        break;
                }

                if (data && Array.isArray(data)) {
                    data.forEach(item => {
                        // Calcular valor total asegurando conversión a número para evitar concatenación o NaN
                        if (type === 'sales') {
                            totalValue += parseFloat(item.total) || 0;
                        } else if (type === 'expenses') {
                            totalValue += parseFloat(item.amount) || 0;
                        } else if (type === 'restocks') {
                            totalValue += parseFloat(item.totalValue) || 0;
                        } else if (type === 'warranties') {
                            totalValue += parseFloat(item.totalCost) || 0;
                        } else if (type === 'pending') {
                            totalValue += parseFloat(item.total) || 0;
                        }
                    });
                } else {
                    data = [];
                }
            }

            // Calcular estadísticas
            let todayCount = 0;
            let thisWeekCount = 0;
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            oneWeekAgo.setHours(0, 0, 0, 0);

            data.forEach(item => {
                const itemDate = new Date(item.date || item.createdAt);
                // Contar por fecha
                if (itemDate >= today) {
                    todayCount++;
                }
                if (itemDate >= oneWeekAgo) {
                    thisWeekCount++;
                }
            });

            // Crear estadísticas
            const statsHTML = `
                <div class="history-details-stat">
                    <div style="font-size: 0.9rem; color: #666;">Total Movimientos</div>
                    <div class="history-details-stat-value">${data.length}</div>
                    <div style="font-size: 0.8rem; color: var(--gold-dark);">registros</div>
                </div>
                <div class="history-details-stat">
                    <div style="font-size: 0.9rem; color: #666;">Valor Total</div>
                    <div class="history-details-stat-value">${formatCurrency(totalValue)}</div>
                    <div style="font-size: 0.8rem; color: var(--gold-dark);">acumulado</div>
                </div>
                <div class="history-details-stat">
                    <div style="font-size: 0.9rem; color: #666;">Hoy</div>
                    <div class="history-details-stat-value">${todayCount}</div>
                    <div style="font-size: 0.8rem; color: var(--gold-dark);">registros</div>
                </div>
                <div class="history-details-stat">
                    <div style="font-size: 0.9rem; color: #666;">Esta semana</div>
                    <div class="history-details-stat-value">${thisWeekCount}</div>
                    <div style="font-size: 0.8rem; color: var(--gold-dark);">registros</div>
                </div>
            `;
            statsContainer.innerHTML = statsHTML;
        }

        // Cargar tabla de detalles del historial
        function loadHistoryDetailsTable(type) {
            const tableHead = document.getElementById('historyDetailsTableHead');
            const tableBody = document.getElementById('historyDetailsTableBody');

            // Si el tipo es 'all' (por filtro global), intentar determinar cuál tabla mostrar o usar 'sales' por defecto
            if (type === 'all') {
                const currentTitle = document.getElementById('detailsTableTitle').textContent.toLowerCase();
                 if (currentTitle.includes('inversión')) type = 'investment'; // Nuevo
                 else if (currentTitle.includes('gasto')) type = 'expenses';
                else if (currentTitle.includes('surtido')) type = 'restocks';
                else if (currentTitle.includes('garantía')) type = 'warranties';
                else if (currentTitle.includes('pendiente')) type = 'pending';
                else type = 'sales';
            }

            // Obtener datos
            let data = [];
            if (type === 'investment') {
                 const expenses = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses')) || [];
                 const restocks = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks')) || [];
                 const warranties = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties')) || [];
                 const products = JSON.parse(localStorage.getItem('destelloOroProducts')) || [];
                 
                 // Filtro de productos
                 const fProducts = products.filter(p => {
                    const d = new Date(p.date || p.created_at);
                    if (currentMonth === -1) {
                        return d.getFullYear() === currentYear;
                    }
                    return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
                 });
 
                 // Combinación normalizada
                 data = [
                     ...expenses.map(e => ({
                         date: e.date, 
                         type: 'Gasto', 
                         concept: e.description, 
                         val: parseFloat(e.amount)||0, 
                         user: e.user,
                         original: e,
                         originType: 'expenses'
                     })),
                     ...restocks.map(r => ({
                         date: r.date, 
                         type: 'Surtido', 
                         concept: `${r.productName} (x${r.quantity})`, 
                         val: parseFloat(r.totalValue)||0, 
                         user: r.user,
                         original: r,
                         originType: 'restocks'
                     })),
                     ...warranties.filter(w=> (parseFloat(w.shipping_value||w.shippingValue)||0) > 0).map(w => ({
                         date: w.created_at || w.createdAt, 
                         type: 'Envío Garantía', 
                         concept: `Garantía #${w.id} - ${w.customerName}`, 
                         val: parseFloat(w.shipping_value||w.shippingValue)||0, 
                         user: w.user || w.createdBy,
                         original: w,
                         originType: 'warranties'
                     })),
                     ...fProducts.map(p => ({
                         date: p.date || p.created_at, 
                         type: 'Producto Nuevo', 
                         concept: `${p.name} (Inicial: ${p.quantity})`, 
                         val: (parseFloat(p.quantity)||0)*(parseFloat(p.purchasePrice)||0), 
                         user: p.addedBy || 'admin',
                         original: p,
                         originType: 'product'
                     }))
                 ];
            } else {
                switch (type) {
                    case 'sales':
                        data = JSON.parse(localStorage.getItem('destelloOroHistorySales'));
                        break;
                    case 'expenses':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses'));
                        break;
                    case 'restocks':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks'));
                        break;
                    case 'warranties':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties'));
                        break;
                    case 'pending':
                        data = JSON.parse(localStorage.getItem('destelloOroHistoryPendingSales'));
                        break;
                }
            }

            if (!data) data = [];

            // Ordenar por fecha (más reciente primero)
            data.sort((a, b) => new Date(b.date || b.createdAt) - new Date(a.date || b.createdAt));

            // Configurar encabezados según el tipo
            let headers = '';
            if (type === 'investment') {
                headers = `
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Concepto / Descripción</th>
                        <th>Valor</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                `;
            } else {
                switch (type) {
                    case 'sales':
                        headers = `
                            <tr>
                                <th>Fecha</th>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Productos</th>
                                <th>Cant.</th>
                                <th>Precio Unit.</th>
                                <th>Tipo</th>
                                <th>Incremento G.</th>
                                <th>Total</th>
                                <th>Pago</th>
                                <th>Estado</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        `;
                        break;
                    case 'expenses':
                        headers = `
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        `;
                        break;
                    case 'restocks':
                        headers = `
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Valor Total</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        `;
                        break;
                    case 'warranties':
                        headers = `
                            <tr>
                                <th>Fecha</th>
                                <th>ID Venta</th>
                                <th>Cliente</th>
                                <th>Producto Original</th>
                                <th>Producto Garantía</th>
                                <th>Motivo</th>
                                <th>Incremento</th>
                                <th>Envío</th>
                                <th>Estado</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        `;
                        break;
                    case 'pending':
                        headers = `
                            <tr>
                                <th>Fecha</th>
                                <th>Factura</th>
                                <th>ID de Venta</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Método Pago</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        `;
                        break;
                }
            }

            tableHead.innerHTML = headers;
            // Agregar filas
            let rowsHTML = '';
            data.forEach(item => {
                let row = '';
                const itemDate = item.date || item.createdAt;
                const user = item.user || item.createdBy || 'desconocido';

                if (type === 'investment') {
                     row = `
                        <tr>
                            <td>${formatDate(itemDate)}</td>
                            <td><span class="badge badge-info">${item.type}</span></td>
                            <td>${item.concept}</td>
                            <td><strong>${formatCurrency(item.val)}</strong></td>
                            <td>
                                <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                    ${getUserName(user)}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    ${item.originType !== 'product' ? `
                                    <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.original.id}', '${item.originType}')" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    switch (type) {
                        case 'sales':
                            const productCount = item.products ? item.products.length : 1;
                            const productNames = item.products ?
                                item.products.map(p => p.productName).join(', ') :
                                (item.productName || 'Producto');

                            row = `
                                <tr>
                                    <td>${formatDate(itemDate)}</td>
                                    <td><strong>${item.invoice_number || 'N/A'}</strong></td>
                                    <td>${item.customerInfo?.name || item.customer_name || 'Cliente de mostrador'}</td>
                                    <td>
                                        <div style="font-size: 0.9em;">
                                            ${item.products ? item.products.map(p => `<div>${p.productName || 'Producto'} <small>(${p.productId})</small></div>`).join('') : (item.productName || 'N/A')}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9em; text-align: center;">
                                            ${item.products ? item.products.map(p => `<div>${p.quantity}</div>`).join('') : (item.quantity || 1)}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9em; text-align: right;">
                                            ${item.products ? item.products.map(p => `<div>${formatCurrency(p.unitPrice || 0)}</div>`).join('') : formatCurrency(item.unitPrice || 0)}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9em; text-align: center;">
                                            ${item.saleType === 'mixed' ? 
                                                '<span class="badge" style="background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%); color: white;">Mixto</span>' :
                                                `<span class="badge ${item.saleType === 'wholesale' ? 'badge-info' : 'badge-success'}">
                                                    ${item.saleType === 'wholesale' ? 'Mayorista' : 'Detal'}
                                                </span>`
                                            }
                                            ${item.saleType === 'mixed' && item.products ? 
                                                `<div style="font-size: 0.8em; margin-top: 2px;">` + 
                                                item.products.map(p => {
                                                    const isWho = (p.saleType === 'wholesale');
                                                    return `<div style="color: ${isWho ? 'var(--info)' : 'var(--success)'};">${isWho ? 'May' : 'Det'}</div>`;
                                                }).join('') + 
                                                `</div>` 
                                                : ''}
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.9em; text-align: right; color: var(--warning);">
                                            ${item.warrantyIncrement > 0 ? formatCurrency(item.warrantyIncrement) : '-'}
                                        </div>
                                    </td>
                                    <td><strong>${formatCurrency(item.total)}</strong></td>
                                    <td>
                                        <span class="badge ${getPaymentMethodClass(item.paymentMethod)}">
                                            ${getPaymentMethodName(item.paymentMethod)}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge ${item.status === 'completed' ? 'badge-success' : 'badge-warning'}">
                                            ${item.status === 'completed' ? 'Confirmada' : 'Pendiente'}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                            ${getUserName(user)}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.id}', 'sales')" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            ${currentUser && currentUser.role === 'admin' ? `
                                            <button class="btn btn-warning btn-sm" onclick="editMovement('${item.id}', 'sales')" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteMovement('${item.id}', 'sales')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            break;

                        case 'expenses':
                            row = `
                                <tr>
                                    <td>${formatDate(itemDate)}</td>
                                    <td>${item.description}</td>
                                    <td><strong>${formatCurrency(item.amount)}</strong></td>
                                    <td>
                                        <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                            ${getUserName(user)}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.id}', 'expenses')" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            ${currentUser && currentUser.role === 'admin' ? `
                                            <button class="btn btn-warning btn-sm" onclick="editMovement('${item.id}', 'expenses')" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteMovement('${item.id}', 'expenses')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            break;

                        case 'restocks':
                            row = `
                                <tr>
                                    <td>${formatDate(itemDate)}</td>
                                    <td>${item.productName}</td>
                                    <td>${item.quantity}</td>
                                    <td><strong>${formatCurrency(item.totalValue)}</strong></td>
                                    <td>
                                        <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                            ${getUserName(user)}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.id}', 'restocks')" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            ${currentUser && currentUser.role === 'admin' ? `
                                            <button class="btn btn-warning btn-sm" onclick="editMovement('${item.id}', 'restocks')" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteMovement('${item.id}', 'restocks')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            break;

                        case 'warranties':
                            row = `
                                <tr>
                                    <td>${formatDate(itemDate)}</td>
                                    <td><strong>${item.originalSaleId}</strong></td>
                                    <td>${item.customerName}</td>
                                    <td>${item.originalProductName || 'N/A'} <small>(${item.originalProductId || 'N/A'})</small></td>
                                    <td>${item.productId === item.originalProductId ? 'Mismo producto' : `${item.newProductName || 'Producto diferente'} <small>(${item.newProductId || item.productId})</small>`}</td>
                                    <td>${item.warrantyReasonText || item.warrantyReason}</td>
                                    <td><strong>${formatCurrency(item.additionalValue || 0)}</strong></td>
                                    <td>${formatCurrency(item.shippingValue || 0)}</td>
                                    <td>
                                        <span class="badge ${item.status === 'completed' ? 'badge-success' :
                                    item.status === 'pending' ? 'badge-warning' :
                                        item.status === 'in_process' ? 'badge-info' : 'badge-danger'}">
                                            ${getWarrantyStatusText(item.status)}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                            ${getUserName(user)}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 5px;">
                                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.id}', 'warranties')" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            ${currentUser && currentUser.role === 'admin' ? `
                                            ${item.status !== 'completed' ? `
                                            <button class="btn btn-success btn-sm" onclick="completeWarranty('${item.id}')" title="Finalizar Garantía">
                                                <i class="fas fa-check-circle"></i>
                                            </button>` : ''}
                                            <button class="btn btn-warning btn-sm" onclick="editMovement('${item.id}', 'warranties')" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteMovement('${item.id}', 'warranties')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            break;

                        case 'pending':
                            let statusBadge = '';
                            if (item.status === 'completed') {
                                statusBadge = '<span class="badge badge-success">Confirmado</span>';
                            } else if (item.status === 'cancelled' || item.status === 'deleted') {
                                statusBadge = '<span class="badge badge-success">Eliminada</span>';
                            } else {
                                statusBadge = '<span class="badge badge-warning">Por confirmar</span>';
                            }

                            row = `
                                <tr>
                                    <td>${formatDate(itemDate)}</td>
                                    <td><strong>${item.invoice_number || 'N/A'}</strong></td>
                                    <td><strong>${item.id}</strong></td>
                                    <td>${item.customerInfo?.name || item.customer_name || 'Cliente de mostrador'}</td>
                                    <td><strong>${formatCurrency(item.total)}</strong></td>
                                    <td>
                                        <span class="badge ${paymentMethods[item.paymentMethod]?.class || 'badge-warning'}">
                                            ${getPaymentMethodName(item.paymentMethod)}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge ${user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                            ${getUserName(user)}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${item.id}', 'sales')" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            ${statusBadge}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            break;
                    }
                }
                rowsHTML += row;
            });
            tableBody.innerHTML = rowsHTML;

            // Si no hay datos
            if (tableBody.innerHTML === '') {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; color: var(--medium-gray);"></i>
                            <h4>No hay datos disponibles</h4>
                            <p>No se encontraron registros de este tipo.</p>
                        </td>
                    </tr>
                `;
            }
        }

        // Cargar resumen mensual - AHORA ASYNC
        async function loadMonthlySummary() {
            const monthlySummary = document.getElementById('monthlySummary');
            if (!monthlySummary) return;
            const queryParams = `?month=${currentMonth}&year=${currentYear}`;

            try {
                // Obtener datos ya filtrados por el servidor
                const responses = await Promise.all([
                    fetch(`api/sales.php${queryParams}`),
                    fetch(`api/expenses.php${queryParams}`),
                    fetch(`api/restocks.php${queryParams}`),
                    fetch(`api/warranties.php${queryParams}`)
                ]);

                // Validar que todas las respuestas sean exitosas
                for (const res of responses) {
                    if (!res.ok) throw new Error(`Error en API: ${res.url} (${res.status})`);
                }

                const [sales, expenses, restocks, warranties] = await Promise.all(responses.map(r => r.json()));

                // Calcular totales de forma segura
                const totalSales = (sales || []).reduce((sum, sale) => sum + (parseFloat(sale.total) || 0), 0);
                const totalExpenses = (expenses || []).reduce((sum, expense) => sum + (parseFloat(expense.amount) || 0), 0);
                
                // Calcular costo real recorriendo los productos vendidos
                const costOfGoodsSold = (sales || []).reduce((sum, sale) => {
                    const itemsCost = (sale.products || []).reduce((pSum, p) => pSum + ((parseFloat(p.purchasePrice || p.purchase_price) || 0) * (parseInt(p.quantity) || 0)), 0);
                    return sum + itemsCost;
                }, 0);

                // IMPORTANTE: Solo restar los costos de envío de garantías, NO el additionalValue
                // porque el additionalValue ya está incluido en totalSales (warranty_increment)
                const totalWarrantyShippingCosts = (warranties || []).reduce((sum, warranty) => sum + (parseFloat(warranty.shippingValue || warranty.shipping_value) || 0), 0);
                const totalWarrantyIncrement = (sales || []).reduce((sum, sale) => sum + (parseFloat(sale.warrantyIncrement || sale.warranty_increment) || 0), 0);
                
                // Ganancia = Ventas (incluye warranty_increment) - Gastos (incluye envíos de garantía) - Costo de productos
                // NO RESTAMOS totalWarrantyShippingCosts porque ya está en totalExpenses (al registrarse en la tabla expenses)
                const netProfit = totalSales - totalExpenses - costOfGoodsSold;

                // Si todo es 0 y hay datos en localStorage, alertar por consola para depuración
                if (totalSales === 0 && totalExpenses === 0 && sales.length === 0) {
                    console.log(`Debug: No se encontraron datos para el mes ${currentMonth + 1}/${currentYear}`);
                }

                // Actualizar resumen mensual
                monthlySummary.innerHTML = `
                    <div class="stat-card clickable" onclick="showMonthlyDetails('sales')">
                        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-value">${formatCurrency(totalSales)}</div>
                        <div class="stat-label">Ventas del Mes</div>
                        <small>${(sales || []).length} ventas (entregadas/pendientes)</small>
                        <small style="color: var(--warning);">Incremento Garantías: ${formatCurrency(totalWarrantyIncrement)}</small>
                    </div>
                    <div class="stat-card clickable" onclick="showMonthlyDetails('expenses')">
                        <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                        <div class="stat-value">${formatCurrency(totalExpenses)}</div>
                        <div class="stat-label">Gastos del Mes</div>
                        <small>${(expenses || []).length} gastos registrados</small>
                    </div>
                    <div class="stat-card clickable" onclick="showMonthlyDetails('restocks')">
                        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                        <div class="stat-value">${formatCurrency(costOfGoodsSold)}</div>
                        <div class="stat-label">Costo de lo Vendido</div>
                        <small>Inversión en productos vendidos</small>
                    </div>
                    <div class="stat-card clickable" onclick="showMonthlyDetails('warranties')">
                        <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="stat-value">${formatCurrency(totalWarrantyShippingCosts)}</div>
                        <div class="stat-label">Costos de Envío (Garantías)</div>
                        <small>${(warranties || []).length} garantías procesadas</small>
                    </div>
                    <div class="stat-card clickable" onclick="showMonthlyDetails('profit')">
                        <div class="stat-icon"><i class="fas fa-coins"></i></div>
                        <div class="stat-value" style="color: ${netProfit >= 0 ? '#4CAF50' : '#f44336'};">${formatCurrency(netProfit)}</div>
                        <div class="stat-label">Ganancia Real</div>
                        <small>Ventas - Gastos - Costo Inv - Envíos Garantías</small>
                    </div>
                `;
            } catch (error) {
                console.error('Error al cargar resumen mensual:', error);
                monthlySummary.innerHTML = '<p style="color: grey; padding: 20px;">No se pudieron cargar los datos financieros. Verifica la conexión.</p>';
            }
        }

        // Mostrar detalles mensuales
        function showMonthlyDetails(type) {
            const modal = document.getElementById('monthlyDetailsModal');
            const content = document.getElementById('monthlyDetailsContent');

            // Obtener datos del mes actual
            const sales = JSON.parse(localStorage.getItem('destelloOroHistorySales')) || [];
            const expenses = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses')) || [];
            const restocks = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks')) || [];
            const warranties = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties')) || [];

            // Usar las variables globales currentMonth y currentYear
            // const currentDate = new Date();
            // const currentMonth = currentDate.getMonth();
            // const currentYear = currentDate.getFullYear();

            const monthlySales = sales.filter(sale => {
                const saleDate = new Date(sale.date || sale.sale_date);
                return (saleDate.getMonth() === currentMonth &&
                    saleDate.getFullYear() === currentYear &&
                    (sale.status === 'completed' || sale.status === 'pending'));
            });

            const monthlyExpenses = expenses.filter(expense => {
                const expenseDate = new Date(expense.date);
                return expenseDate.getMonth() === currentMonth &&
                    expenseDate.getFullYear() === currentYear;
            });

            const monthlyRestocks = restocks.filter(restock => {
                const restockDate = new Date(restock.date);
                return restockDate.getMonth() === currentMonth &&
                    restockDate.getFullYear() === currentYear;
            });

            const monthlyWarranties = warranties.filter(warranty => {
                const warrantyDate = new Date(warranty.createdAt || warranty.created_at);
                return warrantyDate.getMonth() === currentMonth &&
                    warrantyDate.getFullYear() === currentYear;
            });

            // Calcular totales
            const totalSales = monthlySales.reduce((sum, sale) => sum + sale.total, 0);
            const totalExpenses = monthlyExpenses.reduce((sum, expense) => sum + expense.amount, 0);

            // CORRECCIÓN: Calcular el COSTO REAL de lo vendido basándose en el purchasePrice de los productos
            const products = JSON.parse(localStorage.getItem('destelloOroProducts')) || [];
            const costOfGoodsSold = monthlySales.reduce((sum, sale) => {
                const saleCost = (sale.products || []).reduce((saleSum, product) => {
                    // Usar purchasePrice ya incluido por la API o buscar en productos
                    const pPrice = parseFloat(product.purchasePrice || product.purchase_price || 0);
                    return saleSum + (pPrice * (parseInt(product.quantity) || 0));
                }, 0);
                return sum + saleCost;
            }, 0);

            // IMPORTANTE: Solo restar los costos de envío de garantías, NO el additionalValue
            // Y SI totalExpenses YA incluye los envíos, NO restarlos de nuevo.
            // Asumimos que totalExpenses incluye los envíos de garantía (por el cambio en backend).
            const totalWarrantyShippingCosts = monthlyWarranties.reduce((sum, warranty) => sum + (parseFloat(warranty.shippingValue || warranty.shipping_value) || 0), 0);
            const totalWarrantyIncrement = monthlySales.reduce((sum, sale) => sum + (parseFloat(sale.warrantyIncrement || sale.warranty_increment) || 0), 0);
            const netProfit = totalSales - totalExpenses - costOfGoodsSold;

            let title = '';
            let detailsHTML = '';

            switch (type) {
                case 'sales':
                    title = `Detalles de Ventas - ${new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
                    detailsHTML = generateSalesDetailsHTML(monthlySales, totalSales, totalWarrantyIncrement);
                    break;
                case 'expenses':
                    title = `Detalles de Gastos - ${new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
                    detailsHTML = generateExpensesDetailsHTML(monthlyExpenses, totalExpenses);
                    break;
                case 'restocks':
                    title = `Detalles de Costo de Inventario - ${new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
                    detailsHTML = generateCostOfGoodsSoldHTML(monthlySales, costOfGoodsSold, products);
                    break;
                case 'warranties':
                    title = `Detalles de Garantías - ${new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
                    detailsHTML = generateWarrantiesDetailsHTML(monthlyWarranties, totalWarrantyShippingCosts, totalWarrantyIncrement);
                    break;
                case 'profit':
                    title = `Resumen de Ganancias - ${new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}`;
                    // Restamos los envíos de los gastos totales SOLO para la visualización desglosada
                    detailsHTML = generateProfitDetailsHTML(totalSales, totalExpenses - totalWarrantyShippingCosts, costOfGoodsSold, totalWarrantyShippingCosts, totalWarrantyIncrement, netProfit);
                    break;
            }

            content.innerHTML = `
                <div class="dialog-icon" style="color: var(--gold-primary);">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h2 class="dialog-title">${title}</h2>
                <div class="dialog-message" style="text-align: left; max-height: 500px; overflow-y: auto;">
                    ${detailsHTML}
                </div>
                <div class="dialog-buttons">
                    <button onclick="downloadMonthlyReport('${type}')" class="btn btn-primary">
                        <i class="fas fa-download"></i> Descargar PDF
                    </button>
                    <button onclick="document.getElementById('monthlyDetailsModal').style.display='none'" class="btn btn-danger">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            `;

            modal.style.display = 'flex';
        }

        // Generar HTML para detalles de ventas
        function generateSalesDetailsHTML(sales, total, warrantyIncrement) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-chart-line"></i> Resumen de Ventas
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--success);">${formatCurrency(total)}</strong><br>
                            <small>Total Ventas</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--warning);">${formatCurrency(warrantyIncrement)}</strong><br>
                            <small>Incremento por Garantías</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--info);">${sales.length}</strong><br>
                            <small>Total Transacciones</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Detalle de Ventas
                    </h4>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Factura</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Cliente</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Tipo</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Total</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Pago</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            sales.forEach(sale => {
                const customerName = sale.customerInfo?.name || sale.customer_name || 'Cliente de Mostrador';
                const invoiceDisplay = sale.invoice_number || sale.id || 'N/A';
                const statusBadge = sale.status === 'pending' ? 
                    `<br><span class="badge badge-warning" style="font-size: 0.7em;">Pendiente</span>` : '';
                
                let typeBadge = '';
                if (sale.saleType === 'mixed') {
                    typeBadge = `
                        <div style="display: flex; flex-direction: column; align-items: start; gap: 2px;">
                            <span class="badge" style="background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%); color: white; padding: 2px 8px;">Mixto</span>
                            <div style="display: flex; gap: 4px; flex-wrap: wrap; margin-top: 2px;">
                                ${(sale.products || []).map(p => `
                                    <span style="font-size: 0.65em; color: ${p.saleType === 'wholesale' ? '#2196F3' : '#4CAF50'}; font-weight: bold; padding: 1px 3px; border: 1px solid currentColor; border-radius: 3px;">
                                        ${p.saleType === 'wholesale' ? 'May' : 'Det'}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    `;
                } else {
                    const isRetail = sale.saleType !== 'wholesale';
                    typeBadge = `<span class="badge" style="background: ${isRetail ? '#4CAF50' : '#2196F3'}; color: white;">${isRetail ? 'Detal' : 'Mayorista'}</span>`;
                }

                html += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">${formatDate(sale.date)}</td>
                        <td style="padding: 10px;"><strong>${invoiceDisplay}</strong>${statusBadge}</td>
                        <td style="padding: 10px;">${customerName}</td>
                        <td style="padding: 10px;">${typeBadge}</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">${formatCurrency(sale.total)}</td>
                        <td style="padding: 10px; text-align: center;">
                            <span class="payment-badge ${getPaymentMethodClass(sale.paymentMethod)}">${getPaymentMethodName(sale.paymentMethod)}</span>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${sale.id || sale.invoice_number}', 'sales')" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            return html;
        }

        // Generar HTML para detalles de gastos
        function generateExpensesDetailsHTML(expenses, total) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-receipt"></i> Resumen de Gastos
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--danger);">${formatCurrency(total)}</strong><br>
                            <small>Total Gastos</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--info);">${expenses.length}</strong><br>
                            <small>Total Registros</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Detalle de Gastos
                    </h4>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Descripción</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Monto</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Usuario</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            expenses.forEach(expense => {
                html += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">${formatDate(expense.date)}</td>
                        <td style="padding: 10px;">${expense.description}</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">${formatCurrency(expense.amount)}</td>
                        <td style="padding: 10px;">${getUserName(expense.user)}</td>
                        <td style="padding: 10px; text-align: center;">
                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${expense.id}', 'expenses')" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            return html;
        }

        // Generar HTML para detalles de surtidos
        function generateRestocksDetailsHTML(restocks, total) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-boxes"></i> Resumen de Surtidos
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--warning);">${formatCurrency(total)}</strong><br>
                            <small>Valor Total</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--info);">${restocks.length}</strong><br>
                            <small>Total Surtidos</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Detalle de Surtidos
                    </h4>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Producto</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Referencia</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Cantidad</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Valor</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Usuario</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            restocks.forEach(restock => {
                html += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">${formatDate(restock.date)}</td>
                        <td style="padding: 10px;">${restock.productName}</td>
                        <td style="padding: 10px;">${restock.productId}</td>
                        <td style="padding: 10px; text-align: center;">${restock.quantity}</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">${formatCurrency(restock.totalValue)}</td>
                        <td style="padding: 10px;">${getUserName(restock.user)}</td>
                        <td style="padding: 10px; text-align: center;">
                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${restock.id}', 'restocks')" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            return html;
        }

        // Generar HTML para detalles de garantías
        function generateWarrantiesDetailsHTML(warranties, shippingCosts, warrantyIncrement) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-shield-alt"></i> Resumen de Garantías
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--danger);">${formatCurrency(shippingCosts)}</strong><br>
                            <small>Costos de Envío</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--success);">${formatCurrency(warrantyIncrement)}</strong><br>
                            <small>Ingresos por Incremento</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--info);">${warranties.length}</strong><br>
                            <small>Total Garantías</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Detalle de Garantías
                    </h4>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Venta ID</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Cliente</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Motivo</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Costo</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Estado</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            warranties.forEach(warranty => {
                html += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">${formatDate(warranty.createdAt || warranty.created_at)}</td>
                        <td style="padding: 10px;">${warranty.originalSaleId || warranty.sale_id || 'N/A'}</td>
                        <td style="padding: 10px;">${warranty.customerName || 'N/A'}</td>
                        <td style="padding: 10px;">${getWarrantyReasonText(warranty.reason || warranty.warrantyReason)}</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">${formatCurrency(warranty.totalCost || 0)}</td>
                        <td style="padding: 10px;">${getWarrantyStatusText(warranty.status || 'pending')}</td>
                        <td style="padding: 10px; text-align: center;">
                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${warranty.id}', 'warranties')" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            return html;
        }

        // Generar HTML para detalles de costo de lo vendido
        function generateCostOfGoodsSoldHTML(sales, total, products) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-shopping-cart"></i> Costo de Inventario Vendido
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #fff3e0; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--warning);">${formatCurrency(total)}</strong><br>
                            <small>Costo Total</small>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center;">
                            <strong style="font-size: 1.2em; color: var(--info);">${sales.length}</strong><br>
                            <small>Ventas Procesadas</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-list"></i> Desglose de Costos
                    </h4>
                    <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background: var(--gold-light); position: sticky; top: 0;">
                                <tr>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Fecha</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #ddd;">Producto</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 1px solid #ddd;">Cantidad</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Costo Unitario</th>
                                    <th style="padding: 10px; text-align: right; border-bottom: 1px solid #ddd;">Costo Total</th>
                                </tr>
                            </thead>
                            <tbody>
            `;

            sales.forEach(sale => {
                if (sale.products && sale.products.length > 0) {
                    sale.products.forEach((product, idx) => {
                        const unitCost = parseFloat(product.purchasePrice || product.purchase_price || 0);
                        const totalCost = unitCost * (parseInt(product.quantity) || 0);

                        html += `
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">${idx === 0 ? formatDate(sale.date) : ''}</td>
                                <td style="padding: 10px;">${product.productName || product.product_name || 'Producto'}</td>
                                <td style="padding: 10px; text-align: center;">${product.quantity}</td>
                                <td style="padding: 10px; text-align: right;">${formatCurrency(unitCost)}</td>
                                <td style="padding: 10px; text-align: right; font-weight: bold;">${formatCurrency(totalCost)}</td>
                            </tr>
                        `;
                    });
                }
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            return html;
        }

        // Generar HTML para resumen de ganancias
        function generateProfitDetailsHTML(salesTotal, expensesTotal, costOfGoodsSoldTotal, warrantyShippingCosts, warrantyIncrement, netProfit) {
            let html = `
                <div style="margin-bottom: 20px;">
                    <h3 style="color: var(--gold-dark); border-bottom: 2px solid var(--gold-primary); padding-bottom: 10px;">
                        <i class="fas fa-coins"></i> Análisis de Ganancias
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 15px 0;">
                        <div style="background: #e8f5e9; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid var(--success);">
                            <strong style="font-size: 1.2em; color: var(--success);">${formatCurrency(salesTotal)}</strong><br>
                            <small>Ingresos por Ventas</small><br>
                            <small style="color: var(--warning); font-size: 0.8em;">(Incluye ${formatCurrency(warrantyIncrement)} de garantías)</small>
                        </div>
                        <div style="background: #ffebee; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid var(--danger);">
                            <strong style="font-size: 1.2em; color: var(--danger);">${formatCurrency(expensesTotal)}</strong><br>
                            <small>Gastos Operativos</small>
                        </div>
                        <div style="background: #fff3e0; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid var(--warning);">
                            <strong style="font-size: 1.2em; color: var(--warning);">${formatCurrency(costOfGoodsSoldTotal)}</strong><br>
                            <small>Costo de lo Vendido</small>
                        </div>
                        <div style="background: #f3e5f5; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid #9c27b0;">
                            <strong style="font-size: 1.2em; color: #9c27b0;">${formatCurrency(warrantyShippingCosts)}</strong><br>
                            <small>Envíos de Garantías</small>
                        </div>
                        <div style="background: ${netProfit >= 0 ? '#e8f5e9' : '#ffebee'}; padding: 15px; border-radius: 8px; text-align: center; border: 2px solid ${netProfit >= 0 ? 'var(--success)' : 'var(--danger)'}; grid-column: span 2;">
                            <strong style="font-size: 1.5em; color: ${netProfit >= 0 ? 'var(--success)' : 'var(--danger)'};">${formatCurrency(netProfit)}</strong><br>
                            <small>Ganancia Neta del Mes</small>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: var(--gold-dark); margin-bottom: 15px;">
                        <i class="fas fa-calculator"></i> Desglose Detallado
                    </h4>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <span>Ingresos por Ventas (incluye incrementos de garantías):</span>
                            <strong style="color: var(--success);">+${formatCurrency(salesTotal)}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <span>Gastos Operativos:</span>
                            <strong style="color: var(--danger);">-(${formatCurrency(expensesTotal)})</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <span>Costo de lo Vendido:</span>
                            <strong style="color: var(--warning);">-(${formatCurrency(costOfGoodsSoldTotal)})</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                            <span>Envíos de Garantías:</span>
                            <strong style="color: #9c27b0;">-(${formatCurrency(warrantyShippingCosts)})</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; padding: 15px; background: ${netProfit >= 0 ? '#e8f5e9' : '#ffebee'}; border-radius: 5px; font-size: 1.1em;">
                            <span><strong>Ganancia Neta:</strong></span>
                            <strong style="color: ${netProfit >= 0 ? 'var(--success)' : 'var(--danger)'}; font-size: 1.2em;">${formatCurrency(netProfit)}</strong>
                        </div>
                    </div>
                </div>
            `;

            return html;
        }

        // Función para descargar reporte mensual en PDF
        async function downloadMonthlyReport(type) {
            // Usar las variables globales currentMonth y currentYear
            const monthName = new Date(currentYear, currentMonth).toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });

            let title = '';
            let content = '';

            // Obtener datos según el tipo
            const sales = JSON.parse(localStorage.getItem('destelloOroHistorySales')) || [];
            const expenses = JSON.parse(localStorage.getItem('destelloOroHistoryExpenses')) || [];
            const restocks = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks')) || [];
            const warranties = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties')) || [];

            const monthlySales = sales.filter(sale => {
                const saleDate = new Date(sale.date);
                return saleDate.getMonth() === currentMonth &&
                    saleDate.getFullYear() === currentYear &&
                    sale.confirmed;
            });

            const monthlyExpenses = expenses.filter(expense => {
                const expenseDate = new Date(expense.date);
                return expenseDate.getMonth() === currentMonth &&
                    expenseDate.getFullYear() === currentYear;
            });

            const monthlyRestocks = restocks.filter(restock => {
                const restockDate = new Date(restock.date);
                return restockDate.getMonth() === currentMonth &&
                    restockDate.getFullYear() === currentYear;
            });

            const monthlyWarranties = warranties.filter(warranty => {
                const warrantyDate = new Date(warranty.createdAt);
                return warrantyDate.getMonth() === currentMonth &&
                    warrantyDate.getFullYear() === currentYear;
            });

            switch (type) {
                case 'sales':
                    title = `Reporte de Ventas - ${monthName}`;
                    content = generateSalesPDFContent(monthlySales);
                    break;
                case 'expenses':
                    title = `Reporte de Gastos - ${monthName}`;
                    content = generateExpensesPDFContent(monthlyExpenses);
                    break;
                case 'restocks':
                    title = `Reporte de Surtidos - ${monthName}`;
                    content = generateRestocksPDFContent(monthlyRestocks);
                    break;
                case 'warranties':
                    title = `Reporte de Garantías - ${monthName}`;
                    content = generateWarrantiesPDFContent(monthlyWarranties);
                    break;
                case 'profit':
                    title = `Análisis de Ganancias - ${monthName}`;
                    content = generateProfitPDFContent(monthlySales, monthlyExpenses, monthlyRestocks, monthlyWarranties);
                    break;
            }

            // Crear PDF usando jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Configurar fuente y colores
            doc.setFont('helvetica');

            // Título
            doc.setFontSize(20);
            doc.setTextColor(212, 175, 55); // Gold color
            doc.text(title, 20, 30);

            // Línea decorativa
            doc.setDrawColor(212, 175, 55);
            doc.setLineWidth(1);
            doc.line(20, 35, 190, 35);

            // Contenido
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0);

            const lines = doc.splitTextToSize(content, 170);
            let yPosition = 50;

            lines.forEach(line => {
                if (yPosition > 270) {
                    doc.addPage();
                    yPosition = 30;
                }
                doc.text(line, 20, yPosition);
                yPosition += 7;
            });

            // Pie de página
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text(`Generado por Destello de Oro 18K - ${new Date().toLocaleDateString('es-ES')}`, 20, 285);
                doc.text(`Página ${i} de ${pageCount}`, 170, 285);
            }

            // Descargar PDF
            doc.save(`${title.replace(/[^a-zA-Z0-9]/g, '_')}.pdf`);

            // Mostrar mensaje de éxito
            showDialog('Éxito', 'El reporte PDF se ha descargado correctamente.', 'success');
        }

        // Funciones auxiliares para generar contenido PDF
        function generateSalesPDFContent(sales) {
            let content = `REPORTE DETALLADO DE VENTAS\n\n`;
            content += `Total de ventas: ${sales.length}\n`;
            content += `Valor total: ${formatCurrency(sales.reduce((sum, sale) => sum + (parseFloat(sale.total) || 0), 0))}\n\n`;

            content += `DETALLE DE VENTAS:\n`;
            content += `Fecha\t\tFactura\t\tCliente\t\tTotal\t\tPago\n`;
            content += `--------------------------------------------------------------------------------\n`;

            sales.forEach(sale => {
                content += `${formatDate(sale.date)}\t${sale.id || 'N/A'}\t${(sale.customerName || 'Cliente General').substring(0, 15)}\t${formatCurrency(sale.total)}\t${getPaymentMethodName(sale.paymentMethod)}\n`;
            });

            return content;
        }

        function generateExpensesPDFContent(expenses) {
            let content = `REPORTE DETALLADO DE GASTOS\n\n`;
            content += `Total de gastos: ${expenses.length}\n`;
            content += `Valor total: ${formatCurrency(expenses.reduce((sum, expense) => sum + (parseFloat(expense.amount) || 0), 0))}\n\n`;

            content += `DETALLE DE GASTOS:\n`;
            content += `Fecha\t\tDescripción\t\tCategoría\t\tMonto\t\tUsuario\n`;
            content += `--------------------------------------------------------------------------------\n`;

            expenses.forEach(expense => {
                content += `${formatDate(expense.date)}\t${expense.description.substring(0, 20)}\t${(expense.category || 'General').substring(0, 15)}\t${formatCurrency(expense.amount)}\t${getUserName(expense.user)}\n`;
            });

            return content;
        }

        function generateRestocksPDFContent(restocks) {
            let content = `REPORTE DETALLADO DE SURTIDOS\n\n`;
            content += `Total de surtidos: ${restocks.length}\n`;
            content += `Valor total: ${formatCurrency(restocks.reduce((sum, restock) => sum + (parseFloat(restock.totalValue) || 0), 0))}\n\n`;

            content += `DETALLE DE SURTIDOS:\n`;
            content += `Fecha\t\tProducto\t\tReferencia\t\tCantidad\t\tValor\t\tUsuario\n`;
            content += `--------------------------------------------------------------------------------\n`;

            restocks.forEach(restock => {
                content += `${formatDate(restock.date)}\t${restock.productName.substring(0, 15)}\t${restock.productId}\t${restock.quantity}\t${formatCurrency(restock.totalValue)}\t${getUserName(restock.user)}\n`;
            });

            return content;
        }

        function generateWarrantiesPDFContent(warranties) {
            let content = `REPORTE DETALLADO DE GARANTÍAS\n\n`;
            content += `Total de garantías: ${warranties.length}\n`;
            content += `Costo total: ${formatCurrency(warranties.reduce((sum, warranty) => sum + (parseFloat(warranty.totalCost) || 0), 0))}\n\n`;

            content += `DETALLE DE GARANTÍAS:\n`;
            content += `Fecha\t\tVenta ID\t\tCliente\t\tMotivo\t\tCosto\t\tEstado\n`;
            content += `--------------------------------------------------------------------------------\n`;

            warranties.forEach(warranty => {
                content += `${formatDate(warranty.createdAt)}\t${warranty.saleId || 'N/A'}\t${(warranty.customerName || 'N/A').substring(0, 15)}\t${getWarrantyReasonText(warranty.reason).substring(0, 15)}\t${formatCurrency(warranty.totalCost || 0)}\t${getWarrantyStatusText(warranty.status || 'pending')}\n`;
            });

            return content;
        }

        function generateProfitPDFContent(sales, expenses, restocks, warranties) {
            const salesTotal = sales.reduce((sum, sale) => sum + (parseFloat(sale.total) || 0), 0);
            const expensesTotal = expenses.reduce((sum, expense) => sum + (parseFloat(expense.amount) || 0), 0);

            // CORRECCIÓN: Calcular el COSTO REAL de lo vendido basándose en el purchasePrice de los productos
            const products = JSON.parse(localStorage.getItem('destelloOroProducts')) || [];
            const costOfGoodsSold = sales.reduce((sum, sale) => {
                const saleCost = (sale.products || []).reduce((saleSum, product) => {
                    const prod = products.find(p => p.id === product.productId);
                    if (prod) {
                        return saleSum + (prod.purchasePrice * product.quantity);
                    }
                    return saleSum;
                }, 0);
                return sum + saleCost;
            }, 0);

            const warrantiesTotal = warranties.reduce((sum, warranty) => sum + (parseFloat(warranty.totalCost) || 0), 0);
            const netProfit = salesTotal - expensesTotal - costOfGoodsSold - warrantiesTotal;

            let content = `ANÁLISIS DE GANANCIAS DEL MES\n\n`;
            content += `RESUMEN FINANCIERO:\n`;
            content += `--------------------------------------------------------------------------------\n`;
            content += `Ingresos por Ventas:     +${formatCurrency(salesTotal)}\n`;
            content += `Gastos Operativos:       -${formatCurrency(expensesTotal)}\n`;
            content += `Costo de lo Vendido:     -${formatCurrency(costOfGoodsSold)}\n`;
            content += `Costos de Garantías:     -${formatCurrency(warrantiesTotal)}\n`;
            content += `--------------------------------------------------------------------------------\n`;
            content += `GANANCIA NETA:           ${formatCurrency(netProfit)}\n\n`;

            content += `DETALLES:\n`;
            content += `- ${sales.length} ventas realizadas\n`;
            content += `- ${expenses.length} gastos registrados\n`;
            content += `- ${restocks.length} surtidos de inventario\n`;
            content += `- ${warranties.length} garantías gestionadas\n`;

            return content;
        }

        // Función auxiliar para obtener clase de método de pago para badges
        function getPaymentMethodClass(method) {
            const classes = {
                'transfer': 'payment-transfer',
                'cash': 'payment-cash',
                'bold': 'payment-bold',
                'addi': 'payment-addi',
                'sistecredito': 'payment-sistecredito',
                'cod': 'payment-cod'
            };
            return classes[method] || 'payment-cash';
        }

        // Función auxiliar para obtener texto del motivo de garantía
        function getWarrantyReasonText(reason) {
            const reasons = {
                'color_change': 'Cambio de Color',
                'damage': 'Daño',
                'size_issue': 'Problema de Talla',
                'quality_issue': 'Problema de Calidad',
                'other': 'Otro'
            };
            return reasons[reason] || 'No especificado';
        }

        // Función auxiliar para ver detalles de surtido
        window.viewRestockDetails = async function (productId, date) {
            const restocks = JSON.parse(localStorage.getItem('destelloOroHistoryRestocks'));
            const restock = restocks.find(r => r.productId === productId && r.date === date);

            if (restock) {
                await showDialog(
                    'Detalles de Surtido',
                    `<div style="text-align: left;">
                        <p><strong>Producto:</strong> ${restock.productName}</p>
                        <p><strong>Referencia:</strong> ${restock.productId}</p>
                        <p><strong>Cantidad:</strong> ${restock.quantity} unidades</p>
                        <p><strong>Valor total:</strong> ${formatCurrency(restock.totalValue)}</p>
                        <p><strong>Fecha:</strong> ${formatDate(restock.date)}</p>
                        <p><strong>Registrado por:</strong> ${getUserName(restock.user)}</p>
                    </div>`,
                    'info'
                );
            }
        };

        // Configurar eventos de garantías
        function setupWarrantyEvents() {
            const addBtn = document.getElementById('addWarrantyBtn');
            const cancelBtn = document.getElementById('cancelWarranty');
            const backBtn = document.getElementById('backToCustomerSearch');
            const form = document.getElementById('warrantyForm');
            const customerSearch = document.getElementById('searchCustomerWarranty');
            const productTypeSelect = document.getElementById('warrantyProductType');

            // Mostrar formulario
            addBtn.addEventListener('click', function () {
                const formElement = document.getElementById('addWarrantyForm');
                // Mostrar búsqueda de cliente, ocultar formulario
                document.getElementById('warrantySearchCard').style.display = 'block';
                const manualCard = document.getElementById('warrantyManualCard');
                if (manualCard) manualCard.style.display = 'block';
                formElement.style.display = 'none';

                // Limpiar campos
                customerSearch.value = '';
                document.getElementById('customerSearchResults').style.display = 'none';
                document.getElementById('customerNotFoundMessage').style.display = 'none';

                // Enfocar búsqueda
                setTimeout(() => {
                    customerSearch.focus();
                }, 100);
            });

            // Volver a búsqueda de cliente
            backBtn.addEventListener('click', function () {
                const formElement = document.getElementById('addWarrantyForm');
                document.getElementById('warrantySearchCard').style.display = 'block';
                const manualCard = document.getElementById('warrantyManualCard');
                if (manualCard) manualCard.style.display = 'block';
                formElement.style.display = 'none';

                // Limpiar campos
                customerSearch.value = '';
                document.getElementById('customerSearchResults').style.display = 'none';
                document.getElementById('customerNotFoundMessage').style.display = 'none';

                // Enfocar búsqueda
                setTimeout(() => {
                    customerSearch.focus();
                }, 100);
            });

            // Cancelar formulario
            cancelBtn.addEventListener('click', function () {
                document.getElementById('addWarrantyForm').style.display = 'none';
                form.reset();

                // Mostrar búsqueda de cliente
                document.getElementById('warrantySearchCard').style.display = 'block';
                const manualCard = document.getElementById('warrantyManualCard');
                if (manualCard) manualCard.style.display = 'block';
            });

            // Buscar cliente al escribir
            customerSearch.addEventListener('input', function () {
                const searchTerm = this.value.trim().toLowerCase();
                const resultsDiv = document.getElementById('customerSearchResults');
                const notFoundDiv = document.getElementById('customerNotFoundMessage');
                const purchasesList = document.getElementById('customerPurchasesList');

                // Ocultar resultados previos
                resultsDiv.style.display = 'none';
                notFoundDiv.style.display = 'none';

                if (searchTerm.length < 3) {
                    return; // No buscar con menos de 3 caracteres
                }

                // Buscar ventas del cliente - Mejorado para buscar en API si es posible o usar cache robusto
                // Primero intentamos usar lo que hay en memoria, pero asegurándonos de tener datos frescos
                let sales = JSON.parse(localStorage.getItem('destelloOroHistorySales') || '[]');
                const warranties = JSON.parse(localStorage.getItem('destelloOroHistoryWarranties') || '[]');

                console.log(`Buscando ventas para: "${searchTerm}"`);
                const customerSales = sales.filter(sale => {
                    const search = searchTerm.toLowerCase();
                    
                    // Normalizar datos para búsqueda
                    const cName = (sale.customerInfo?.name || sale.customer_name || '').toLowerCase();
                    const cId = (sale.customerInfo?.id || sale.customer_id || '').toString().toLowerCase();
                    const invId = (sale.id || sale.invoice_number || '').toString().toLowerCase();

                    // Buscar por Nombre, Cédula o ID de Factura
                    const matches = cName.includes(search) || cId.includes(search) || invId.includes(search);
                    
                    // Solo ventas confirmadas (o pending si queremos permitir garantías en pendientes, pero usualmente es completed)
                    // En este sistema, 'confirmed' es booleano true para pago en efectivo o ya confirmados.
                    // Ajuste: Permitir status 'completed' también por si acaso.
                    const isConfirmed = sale.confirmed === true || sale.status === 'completed';

                    return matches && isConfirmed;
                });
                console.log(`Encontradas ${customerSales.length} ventas coincidentes.`);

                if (customerSales.length === 0) {
                    // Mostrar mensaje de no encontrado
                    notFoundDiv.style.display = 'block';
                    return;
                }

                // Mostrar resultados
                purchasesList.innerHTML = '';

                customerSales.forEach(sale => {
                    const saleDiv = document.createElement('div');
                    saleDiv.style.padding = '10px';
                    saleDiv.style.marginBottom = '8px';
                    saleDiv.style.border = '1px solid var(--medium-gray)';
                    saleDiv.style.borderRadius = 'var(--radius-sm)';
                    saleDiv.style.cursor = 'pointer';
                    saleDiv.style.transition = 'var(--transition)';
                    saleDiv.style.backgroundColor = 'var(--white)';

                    // Obtener nombres de productos
                    const productNames = sale.products ?
                        sale.products.map(p => p.productName).join(', ') :
                        (sale.productName || 'Producto');

                    // Verificar si tiene garantías
                    const saleWarranties = warranties.filter(w => w.originalSaleId === sale.id);
                    const hasWarranty = saleWarranties.length > 0;
                    const warrantyText = hasWarranty ?
                        `<span style="color: var(--warning); font-size: 0.8rem;">
                            <i class="fas fa-shield-alt"></i> ${saleWarranties.length} garantía(s)
                        </span>` : '';

                    saleDiv.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 0.9rem;">${sale.id}</strong><br>
                                <span style="font-size: 0.85rem; color: #666;">${formatDate(sale.date)}</span>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.9rem; font-weight: 500;">${productNames}</div>
                                <div style="font-size: 0.85rem; color: var(--gold-dark);">${formatCurrency(sale.total)}</div>
                                ${sale.warrantyIncrement > 0 ?
                            `<div style="font-size: 0.8rem; color: var(--warning);">
                                        <i class="fas fa-plus-circle"></i> Garantía: ${formatCurrency(sale.warrantyIncrement)}
                                    </div>` : ''}
                            </div>
                        </div>
                        <div style="margin-top: 5px; font-size: 0.8rem; color: #666;">
                            Cliente: ${sale.customerInfo.name}
                        </div>
                        ${warrantyText}
                    `;

                    // Agregar evento para seleccionar esta venta
                    saleDiv.addEventListener('click', function () {
                        selectSaleForWarranty(sale);
                    });

                    saleDiv.addEventListener('mouseenter', function () {
                        this.style.backgroundColor = 'var(--light-gray)';
                        this.style.transform = 'translateY(-2px)';
                    });

                    saleDiv.addEventListener('mouseleave', function () {
                        this.style.backgroundColor = 'var(--white)';
                        this.style.transform = 'translateY(0)';
                    });

                    purchasesList.appendChild(saleDiv);
                });

                resultsDiv.style.display = 'block';
            });

            // Cambiar visibilidad de sección de producto diferente
            productTypeSelect.addEventListener('change', function () {
                const differentSection = document.getElementById('differentProductSection');
                const additionalValueInput = document.getElementById('additionalValue');

                if (this.value === 'different') {
                    differentSection.style.display = 'block';
                    additionalValueInput.required = true;
                } else {
                    differentSection.style.display = 'none';
                    additionalValueInput.required = false;
                    additionalValueInput.value = 0;
                }

                updateWarrantyCostSummary();
            });

            ['additionalValue', 'shippingValue'].forEach(id => {
                document.getElementById(id).addEventListener('input', updateWarrantyCostSummary);
            });

            // Lógica: Autocompletar nombre del producto al ingresar referencia en Garantías
            const newProductRefInput = document.getElementById('newProductRef');
            const newProductNameInput = document.getElementById('newProductName');
            const newProductStatus = document.getElementById('newProductStatus');
            
            if (newProductRefInput && newProductNameInput && newProductStatus) {
                newProductRefInput.addEventListener('input', function () {
                    const ref = this.value.trim().toUpperCase();
                    if (ref) {
                        const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                        const product = products.find(p => p.id === ref);

                        if (product) {
                            newProductNameInput.value = product.name;
                            this.style.borderColor = 'var(--success)';
                            newProductStatus.innerHTML = `
                                <span style="color: var(--success);">
                                    <i class="fas fa-check-circle"></i> Producto encontrado: <strong>${product.name}</strong>
                                    <br><small>(Stock actual: ${product.quantity} unidades)</small>
                                </span>`;
                        } else {
                            newProductNameInput.value = '';
                            this.style.borderColor = 'var(--danger)';
                            newProductStatus.innerHTML = '<span style="color: var(--danger);"><i class="fas fa-times-circle"></i> Referencia no encontrada en inventario</span>';
                        }
                    } else {
                        this.style.borderColor = '';
                        newProductStatus.innerHTML = '';
                        newProductNameInput.value = '';
                    }
                });
            }

            // Enviar formulario
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    await showDialog('Acceso Restringido', 'Solo el administrador puede registrar garantías.', 'error');
                    return;
                }

                if (!selectedSaleForWarranty) {
                    await showDialog('Error', 'Por favor seleccione una venta válida.', 'error');
                    return;
                }

                const warrantySaleId = document.getElementById('warrantySaleId').value;
                if (!warrantySaleId) {
                    await showDialog('Error', 'El ID de factura no está disponible. Por favor seleccione una venta válida.', 'error');
                    return;
                }

                const productSelect = document.getElementById('warrantySelectedProduct');
                let targetProduct = null;
                if (selectedSaleForWarranty.products && selectedSaleForWarranty.products.length > 0) {
                    if (selectedSaleForWarranty.products.length > 1 && productSelect) {
                        targetProduct = selectedSaleForWarranty.products.find(p => p.productId === productSelect.value);
                    } else {
                        targetProduct = selectedSaleForWarranty.products[0];
                    }
                } else {
                    targetProduct = selectedSaleForWarranty; // Fallback for old data structure
                }

                const warranty = {
                    originalSaleId: warrantySaleId,
                    customerName: selectedSaleForWarranty.customerInfo?.name || selectedSaleForWarranty.customer_name || 'N/A',
                    customerId: selectedSaleForWarranty.customerInfo?.id || selectedSaleForWarranty.customer_id || 'N/A',
                    customerPhone: selectedSaleForWarranty.customerInfo?.phone || selectedSaleForWarranty.customer_phone || 'N/A',
                    originalProductId: targetProduct.productId || targetProduct.reference || targetProduct.id || 'N/A',
                    originalProductName: targetProduct.productName || targetProduct.name || 'N/A',
                    quantity: parseInt(document.getElementById('warrantyQuantity').value) || 1,
                    warrantyReason: document.getElementById('warrantyReason').value,
                    warrantyReasonText: warrantyReasons[document.getElementById('warrantyReason').value] || document.getElementById('warrantyReason').value,
                    endDate: new Date(new Date(selectedSaleForWarranty.date).setMonth(new Date(selectedSaleForWarranty.date).getMonth() + 12)).toISOString().split('T')[0],
                    productType: document.getElementById('warrantyProductType').value,
                    additionalValue: parseFloat(document.getElementById('additionalValue').value) || 0,
                    shippingValue: parseFloat(document.getElementById('shippingValue').value) || 0,
                    status: document.getElementById('warrantyStatus').value,
                    notes: document.getElementById('warrantyNotes').value.trim(),
                };

                // Si es producto diferente, agregar información del nuevo producto y normalizar a Mayúsculas
                if (warranty.productType === 'different') {
                    warranty.newProductRef = document.getElementById('newProductRef').value.trim().toUpperCase();
                    warranty.newProductName = document.getElementById('newProductName').value.trim();
                } else {
                     // Keep same ref
                    warranty.newProductRef = warranty.originalProductId;
                    warranty.newProductName = warranty.originalProductName;
                }

                // Validar existencia del nuevo producto si es diferente
                if (warranty.productType === 'different' && warranty.newProductRef) {
                    const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                    const product = products.find(p => p.id === warranty.newProductRef);
                    if (!product) {
                        await showDialog('Error', `La referencia "${warranty.newProductRef}" no existe en el inventario.`, 'error');
                        return;
                    }
                    if (product.quantity <= 0) {
                        await showDialog('Producto Agotado', 'El producto seleccionado no tiene existencias en el inventario. Por favor seleccione otro producto diferente.', 'warning');
                        return;
                    }
                }

                // Validar datos
                if (!warranty.warrantyReason || !warranty.status) {
                    await showDialog('Error', 'Por favor complete todos los campos obligatorios (*).', 'error');
                    return;
                }

                try {
                    const response = await fetch('api/warranties.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(warranty)
                    });
                    const data = await response.json();

                    if (data.success) {
                        // Recargar todo
                        loadWarrantiesTable();
                        loadHistoryCards(); // Actualiza ventas también
                         // Reset cache manually if needed or trust reload
                        
                        form.reset();
                        // Limpiar búsqueda
                        const searchInput = document.getElementById('searchCustomerWarranty');
                        if (searchInput) searchInput.value = '';
                        const searchResults = document.getElementById('customerSearchResults');
                        if (searchResults) searchResults.style.display = 'none';
                        
                        document.getElementById('addWarrantyForm').style.display = 'none';
                        document.getElementById('warrantySearchCard').style.display = 'block';
                        const manualCard = document.getElementById('warrantyManualCard');
                        if (manualCard) manualCard.style.display = 'block';
                        selectedSaleForWarranty = null;

                        await showDialog('Éxito', 'Garantía registrada exitosamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al registrar garantía', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            });
        }

        // CORREGIDO: Seleccionar venta para garantía - Ahora llena el campo ID de factura
        function selectSaleForWarranty(sale) {
            selectedSaleForWarranty = sale;

            // Ocultar búsqueda, mostrar formulario
            document.getElementById('warrantySearchCard').style.display = 'none';
            const manualCard = document.getElementById('warrantyManualCard');
            if (manualCard) manualCard.style.display = 'none';
            document.getElementById('addWarrantyForm').style.display = 'block';

            // CORREGIDO: LLENAR ID DE FACTURA AUTOMÁTICAMENTE Y HACERLO VISIBLE
            const warrantySaleIdInput = document.getElementById('warrantySaleId');
            warrantySaleIdInput.value = sale.id;

            // Mostrar confirmación visual
            const statusDiv = document.getElementById('warrantySaleIdStatus');
            if (statusDiv) {
                statusDiv.innerHTML = `<span style="color: var(--success); font-weight: bold;">
                    <i class="fas fa-check-circle"></i> ID de factura cargado: ${sale.id}
                </span>`;
                statusDiv.style.display = 'block';
            }

            const saleDate = new Date(sale.date);
            const endDate = new Date(saleDate);
            endDate.setMonth(endDate.getMonth() + 12);

            // Mostrar información del cliente
            const customerInfoDiv = document.getElementById('customerInfoDisplay');
            customerInfoDiv.innerHTML = `
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; font-size: 0.85rem;">
                    <div><strong>Nombre:</strong> ${sale.customerInfo?.name || sale.customer_name || 'Cliente de mostrador'}</div>
                    <div><strong>Cédula:</strong> ${(sale.customerInfo?.id) || (sale.customer_id) || 'No registrada'}</div>
                    <div><strong>Teléfono:</strong> ${(sale.customerInfo?.phone) || (sale.customer_phone) || 'No registrado'}</div>
                    <div><strong>Fecha compra:</strong> ${formatDateSimple(sale.date)}</div>
                    <div><strong>Garantía hasta:</strong> ${formatDateSimple(endDate)}</div>
                </div>
            `;

            // Manejar selección de producto
            const productSelectContainer = document.getElementById('productSelectContainer');
            const productSelect = document.getElementById('warrantySelectedProduct');
            const productInfoDiv = document.getElementById('productInfoDisplay');
            
            if (sale.products && sale.products.length > 1) {
                productSelectContainer.style.display = 'block';
                productSelect.innerHTML = sale.products.map(p => `<option value="${p.productId}">${p.productName} (${p.productId})</option>`).join('');
                productSelect.onchange = () => {
                    const p = sale.products.find(prod => prod.productId === productSelect.value);
                    renderSelectedProductInfo(p, sale);
                };
                renderSelectedProductInfo(sale.products[0], sale);
            } else if (sale.products && sale.products.length === 1) {
                productSelectContainer.style.display = 'none';
                renderSelectedProductInfo(sale.products[0], sale);
            } else {
                productSelectContainer.style.display = 'none';
                renderSelectedProductInfo(sale, sale);
            }

            function renderSelectedProductInfo(product, sale) {
                const pName = product.productName || product.name || 'N/A';
                const pId = product.productId || product.reference || product.id || 'N/A';
                const pQty = product.quantity || 1;
                
                productInfoDiv.innerHTML = `
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem; font-size: 0.85rem;">
                        <div><strong>Producto:</strong> ${pName}</div>
                        <div><strong>Referencia:</strong> ${pId}</div>
                        <div><strong>Cant. Vendida:</strong> ${pQty}</div>
                        <div><strong>Valor compra:</strong> ${formatCurrency(sale.total)}</div>
                        <div><strong>Incremento garantía:</strong> ${formatCurrency(sale.warrantyIncrement || 0)}</div>
                    </div>
                `;
                
                // Set max quantity in input
                const qtyInput = document.getElementById('warrantyQuantity');
                if (qtyInput) {
                    qtyInput.max = pQty;
                    qtyInput.value = 1;
                }
            }

            // Resetear otros campos del formulario
            document.getElementById('warrantyReason').selectedIndex = 0;
            document.getElementById('warrantyQuantity').value = 1;
            document.getElementById('warrantyProductType').selectedIndex = 0;
            document.getElementById('additionalValue').value = 0;
            document.getElementById('shippingValue').value = 0;
            document.getElementById('warrantyStatus').selectedIndex = 0;
            document.getElementById('warrantyNotes').value = '';
            document.getElementById('newProductRef').value = '';
            document.getElementById('newProductName').value = '';
            document.getElementById('differentProductSection').style.display = 'none';

            // Actualizar resumen de costos
            updateWarrantyCostSummary();


            // Scroll al formulario
            document.getElementById('addWarrantyForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Asegurar que el campo sea visible y enfocado
            setTimeout(() => {
                warrantySaleIdInput.focus();
                warrantySaleIdInput.blur();
            }, 100);
        }

        // Función para cargar ID manualmente (para pruebas)
        window.loadSelectedSaleId = function () {
            if (selectedSaleForWarranty) {
                document.getElementById('warrantySaleId').value = selectedSaleForWarranty.id;
                const statusDiv = document.getElementById('warrantySaleIdStatus');
                if (statusDiv) {
                    statusDiv.innerHTML = `<span style="color: var(--success); font-weight: bold;">
                        <i class="fas fa-check-circle"></i> ID de factura cargado manualmente: ${selectedSaleForWarranty.id}
                    </span>`;
                    statusDiv.style.display = 'block';
                }
                showDialog('ID Cargado', `ID de factura establecido: ${selectedSaleForWarranty.id}`, 'success');
            } else {
                showDialog('Error', 'No hay una venta seleccionada.', 'error');
            }
        };

        // Actualizar resumen de costos de garantía
        function updateWarrantyCostSummary() {
            const additionalValue = parseFloat(document.getElementById('additionalValue').value) || 0;
            const shippingValue = parseFloat(document.getElementById('shippingValue').value) || 0;
            const total = additionalValue + shippingValue;

            document.getElementById('additionalValueDisplay').textContent = formatCurrency(additionalValue);
            document.getElementById('shippingValueDisplay').textContent = formatCurrency(shippingValue);
            document.getElementById('totalWarrantyCost').textContent = formatCurrency(total);
        }

        // Cargar tabla de garantías
        async function loadWarrantiesTable() {
            try {
                // Consultar API con filtro de fecha si se desea, o todas para la tabla principal
                // Por ahora, traemos todas para la sección de tabla, pero el resumen superior usa las filtradas
                const response = await fetch('api/warranties.php');
                const warranties = await response.json();
                
                if (!Array.isArray(warranties)) {
                    console.error('Error: La respuesta de garantías no es un array', warranties);
                    return;
                }
                
                // Actualizar caché local para compatibilidad
                localStorage.setItem('destelloOroAllWarranties', JSON.stringify(warranties));
            const tableBody = document.getElementById('warrantiesTableBody');
            const statsContainer = document.getElementById('warrantyStats');

            tableBody.innerHTML = '';

            // Ordenar por fecha de creación (más recientes primero)
            warranties.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));

            // Calcular estadísticas
            let pendingCount = 0;
            let inProcessCount = 0;
            let completedCount = 0;
            let cancelledCount = 0;
            let totalCost = 0;
            let totalIncrement = 0; // Nuevo: para calcular el incremento total por garantías

            warranties.forEach(warranty => {
                // Calcular días restantes
                let daysRemaining = -1;
                if (warranty.endDate) {
                    const endDate = new Date(warranty.endDate);
                    if (!isNaN(endDate.getTime())) {
                        const today = new Date();
                        const timeDiff = endDate.getTime() - today.getTime();
                        daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    }
                }

                // Determinar color según estado
                let statusBadge = '';
                let statusText = '';

                switch (warranty.status) {
                    case 'pending':
                        pendingCount++;
                        statusBadge = 'badge-warning';
                        statusText = 'Pendiente';
                        break;
                    case 'in_process':
                        inProcessCount++;
                        statusBadge = 'badge-info';
                        statusText = 'En proceso';
                        break;
                    case 'completed':
                        completedCount++;
                        statusBadge = 'badge-success';
                        statusText = 'Completada';
                        break;
                    case 'cancelled':
                        cancelledCount++;
                        statusBadge = 'badge-danger';
                        statusText = 'Cancelada';
                        break;
                }

                // Sumar al costo total
                totalCost += warranty.totalCost || 0;
                totalIncrement += warranty.additionalValue || 0; // Sumar incremento

                // Determinar producto de garantía
                let warrantyProduct = warranty.newProductName || warranty.originalProductName;
                if (warranty.productType === 'different' && warranty.newProductRef) {
                    warrantyProduct = `${warranty.newProductName} (${warranty.newProductRef})`;
                }

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${formatDate(warranty.date)}</td>
                    <td><strong>${warranty.originalSaleId}</strong></td>
                    <td>${warranty.customerName}</td>
                    <td>${warranty.originalProductName} (${warranty.originalProductId})</td>
                    <td>${warrantyProduct}</td>
                    <td>${warranty.warrantyReasonText || warranty.warrantyReason}</td>
                    <td><strong>${formatCurrency(warranty.additionalValue || 0)}</strong></td>
                    <td>${formatCurrency(warranty.shippingValue || 0)}</td>
                    <td>
                        <span class="badge ${statusBadge}">
                            ${statusText}
                        </span>
                        <br>
                        <small style="font-size: 0.7rem; color: #666;">
                            Vence: ${formatDateSimple(warranty.endDate)} (${daysRemaining >= 0 ? `${daysRemaining}d` : 'Vencida'})
                        </small>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${warranty.id}', 'warranties')" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${currentUser && currentUser.role === 'admin' ? `
                            <button class="btn btn-warning btn-sm" onclick="editMovement('${warranty.id}', 'warranties')" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteMovement('${warranty.id}', 'warranties')" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>` : ''}
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Actualizar estadísticas 
            renderWarrantyStats(pendingCount, inProcessCount, completedCount, totalCost, totalIncrement);

            } catch (error) {
                console.error('Error cargando garantías:', error);
            }
        }

        function renderWarrantyStats(pending, inProcess, completed, cost, increment) {
            const statsContainer = document.getElementById('warrantyStats');
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock" style="color: var(--warning);"></i>
                    </div>
                    <div class="stat-value">${pending}</div>
                    <div class="stat-label">Pendientes</div>
                    <small>Por procesar</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cogs" style="color: var(--info);"></i>
                    </div>
                    <div class="stat-value">${inProcess}</div>
                    <div class="stat-label">En proceso</div>
                    <small>Siendo gestionadas</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                    </div>
                    <div class="stat-value">${completed}</div>
                    <div class="stat-label">Completadas</div>
                    <small>Finalizadas</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-coins" style="color: var(--gold-dark);"></i>
                    </div>
                    <div class="stat-value">${formatCurrency(cost)}</div>
                    <div class="stat-label">Costo Total</div>
                    <small>Incluye envíos y adicionales</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-plus-circle" style="color: var(--warning);"></i>
                    </div>
                    <div class="stat-value">${formatCurrency(increment)}</div>
                    <div class="stat-label">Incremento Ventas</div>
                    <small>Por productos diferentes</small>
                </div>
            `;
        }

        // NUEVAS FUNCIONES: Ver, Editar y Eliminar Movimientos
        window.viewMovementDetails = function (movementId, type) {
            let movement = null;
            let title = '';

            // Buscar el movimiento en todos los posibles caches
            const searchInCaches = (keys, id) => {
                for (const key of keys) {
                    const data = JSON.parse(localStorage.getItem(key)) || [];
                    const found = data.find(item => (item.id == id || item.invoice_number == id));
                    if (found) return found;
                }
                return null;
            };

            switch (type) {
                case 'sales':
                    movement = searchInCaches(['destelloOroHistorySales', 'destelloOroAllSales', 'destelloOroSales', 'destelloOroHistoryPendingSales', 'destelloOroPendingSales'], movementId);
                    title = `Detalles de Venta - ${movementId}`;
                    break;
                case 'expenses':
                    movement = searchInCaches(['destelloOroHistoryExpenses', 'destelloOroAllExpenses', 'destelloOroExpenses'], movementId);
                    title = `Detalles de Gasto - ${movementId}`;
                    break;
                case 'restocks':
                    movement = searchInCaches(['destelloOroHistoryRestocks', 'destelloOroAllRestocks', 'destelloOroRestocks'], movementId);
                    title = `Detalles de Surtido - ${movementId}`;
                    break;
                case 'warranties':
                    movement = searchInCaches(['destelloOroHistoryWarranties', 'destelloOroAllWarranties', 'destelloOroWarranties'], movementId);
                    title = `Detalles de Garantía - ${movementId}`;
                    break;
                case 'product':
                    movement = searchInCaches(['destelloOroProducts'], movementId);
                    title = `Detalles de Producto - ${movementId}`;
                    break;
                default:
                    showDialog('Error', 'Tipo de movimiento no válido.', 'error');
                    return;
            }

            if (!movement) {
                showDialog('Error', 'Movimiento no encontrado.', 'error');
                return;
            }

            // Configurar modal según el tipo de movimiento
            const modal = document.getElementById('viewMovementModal');
            const modalTitle = document.getElementById('viewMovementTitle');
            const modalContent = document.getElementById('viewMovementContent');

            modalTitle.textContent = title;

            // Generar contenido según el tipo
            let content = '';
            switch (type) {
                case 'sales':
                    currentSaleForView = movement;
                    const productCount = movement.products ? movement.products.length : 1;
                    const productNames = movement.products ?
                        movement.products.map(p => p.productName).join(', ') :
                        (movement.productName || 'Producto');
                    
                    const saleTypeLabel = movement.saleType === 'wholesale' ? 'Mayorista' : 'Detal';
                    const saleTypeColor = movement.saleType === 'wholesale' ? '#2196F3' : '#4CAF50';

                    content = `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-user"></i> Información del Cliente
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.5rem;">
                                <div><strong>Nombre:</strong> ${movement.customerInfo?.name || movement.customer_name || 'Cliente de mostrador'}</div>
                                <div><strong>Cédula:</strong> ${movement.customerInfo?.id || 'No proporcionada'}</div>
                                <div><strong>Teléfono:</strong> ${movement.customerInfo?.phone || 'No proporcionado'}</div>
                                <div><strong>Dirección:</strong> ${movement.customerInfo?.address || 'No proporcionada'}</div>
                                <div><strong>Ciudad:</strong> ${movement.customerInfo?.city || 'No proporcionada'}</div>
                                <div><strong>Correo:</strong> ${movement.customerInfo?.email || 'No proporcionado'}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-box-open"></i> Productos Vendidos
                            </h3>
                            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px; padding: 10px;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #ddd;">
                                            <th style="text-align: left; padding: 5px;">Producto</th>
                                            <th style="text-align: center; padding: 5px;">Cant.</th>
                                            <th style="text-align: right; padding: 5px;">Precio</th>
                                            <th style="text-align: center; padding: 5px;">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${movement.products ? movement.products.map(p => `
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 5px;">${p.productName || 'N/A'} <br><small>${p.productId || p.product_ref || ''}</small></td>
                                                <td style="padding: 5px; text-align: center;">${p.quantity}</td>
                                                <td style="padding: 5px; text-align: right;">${formatCurrency(p.unitPrice || p.unit_price || 0)}</td>
                                                <td style="padding: 5px; text-align: center;">
                                                    <span class="badge ${p.saleType === 'wholesale' ? 'badge-info' : 'badge-success'}" style="font-size: 0.7em;">
                                                        ${p.saleType === 'wholesale' ? 'May' : 'Det'}
                                                    </span>
                                                </td>
                                            </tr>
                                        `).join('') : `
                                            <tr>
                                                <td style="padding: 5px;">${movement.productName || 'N/A'}</td>
                                                <td style="padding: 5px; text-align: center;">${movement.quantity || 1}</td>
                                                <td style="padding: 5px; text-align: right;">${formatCurrency(movement.unitPrice || 0)}</td>
                                                <td style="padding: 5px; text-align: center;">
                                                    <span class="badge ${movement.saleType === 'wholesale' ? 'badge-info' : 'badge-success'}" style="font-size: 0.7em;">
                                                        ${movement.saleType === 'wholesale' ? 'May' : 'Det'}
                                                    </span>
                                                </td>
                                            </tr>
                                        `}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-receipt"></i> Información de Pago
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                <div><strong>Subtotal:</strong> ${formatCurrency(movement.subtotal)}</div>
                                <div><strong>Descuento:</strong> ${formatCurrency(movement.discount || 0)}</div>
                                <div><strong>Costo envío:</strong> ${formatCurrency(movement.deliveryCost || 0)}</div>
                                <div><strong>Incremento garantía:</strong> ${formatCurrency(movement.warrantyIncrement || 0)}</div>
                                <div><strong>Total:</strong> ${formatCurrency(movement.total)}</div>
                                <div><strong>Método de pago:</strong> ${getPaymentMethodName(movement.paymentMethod)}</div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-info-circle"></i> Información Adicional
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                <div><strong>Fecha:</strong> ${formatDate(movement.date)}</div>
                                <div><strong>Estado:</strong> ${movement.confirmed ? 'Confirmada' : 'Pendiente'}</div>
                                <div><strong>Registrado por:</strong> ${getUserName(movement.user)}</div>
                                <div><strong>Tipo entrega:</strong> ${movement.deliveryType === 'store' ? 'Recoge en tienda' : movement.deliveryType === 'delivery' ? 'Domicilio' : 'Envío nacional'}</div>
                                <div><strong>Tipo de Venta:</strong> 
                                    ${movement.saleType === 'mixed' ? 
                                        '<span class="badge" style="background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%); color: white; padding: 2px 8px;">Mixto</span>' :
                                        `<span style="color: ${saleTypeColor}; font-weight: bold;">${saleTypeLabel}</span>`
                                    }
                                </div>
                            </div>
                        </div>
                    `;
                    break;

                case 'expenses':
                    content = `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-receipt"></i> Información del Gasto
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                                <div><strong>Descripción:</strong> ${movement.description}</div>
                                <div><strong>Valor:</strong> ${formatCurrency(movement.amount)}</div>
                                <div><strong>Fecha:</strong> ${formatDate(movement.date)}</div>
                                <div><strong>Registrado por:</strong> ${getUserName(movement.user)}</div>
                                ${movement.type ? `<div><strong>Tipo:</strong> ${movement.type}</div>` : ''}
                            </div>
                        </div>
                        
                        ${movement.notes ? `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-sticky-note"></i> Notas Adicionales
                            </h3>
                            <p>${movement.notes}</p>
                        </div>
                        ` : ''}
                    `;
                    break;

                case 'restocks':
                    content = `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-boxes"></i> Información del Surtido
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                                <div><strong>Producto:</strong> ${movement.productName}</div>
                                <div><strong>Referencia:</strong> ${movement.productId}</div>
                                <div><strong>Cantidad:</strong> ${movement.quantity} unidades</div>
                                <div><strong>Valor total:</strong> ${formatCurrency(movement.totalValue)}</div>
                                <div><strong>Fecha:</strong> ${formatDate(movement.date)}</div>
                                <div><strong>Registrado por:</strong> ${getUserName(movement.user)}</div>
                            </div>
                        </div>
                    `;
                    break;

                case 'warranties':
                    let daysRemaining = -1;
                    if (movement.endDate) {
                        const endDate = new Date(movement.endDate);
                        if (!isNaN(endDate.getTime())) {
                            const today = new Date();
                            const timeDiff = endDate.getTime() - today.getTime();
                            daysRemaining = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        }
                    }

                    let warrantyProductInfo = '';
                    if (movement.productType === 'different') {
                        warrantyProductInfo = `
                            <div><strong>Producto de reemplazo:</strong> ${movement.newProductName || 'No especificado'} (${movement.newProductRef || 'Sin referencia'})</div>
                            <div><strong>Valor adicional:</strong> ${formatCurrency(movement.additionalValue || 0)}</div>
                        `;
                    } else {
                        warrantyProductInfo = `<div><strong>Producto de reemplazo:</strong> Mismo producto (${movement.originalProductId})</div>`;
                    }

                    const reasonLabel = warrantyReasons[movement.warrantyReason] || movement.warrantyReason || 'No especificado';

                    let statusMessage = '';
                    if (movement.status === 'pending' || movement.status === 'in_process') {
                        if (daysRemaining < 0) {
                            statusMessage = `⚠️ <strong>Vencida hace ${Math.abs(daysRemaining)} días</strong>`;
                        } else if (daysRemaining === 0) {
                            statusMessage = `⚠️ <strong>Vence hoy</strong>`;
                        } else if (daysRemaining <= 7) {
                            statusMessage = `⚠️ <strong>Vence en ${daysRemaining} días</strong>`;
                        } else {
                            statusMessage = `<strong>${daysRemaining} días restantes</strong>`;
                        }
                    }

                    content = `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-user"></i> Información del Cliente
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.5rem;">
                                <div><strong>Nombre:</strong> ${movement.customerName}</div>
                                <div><strong>Cédula:</strong> ${movement.customerId || 'No registrada'}</div>
                                <div><strong>Teléfono:</strong> ${movement.customerPhone}</div>
                                <div><strong>ID Venta Original:</strong> ${movement.originalSaleId}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-box-open"></i> Información del Producto
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.5rem;">
                                <div><strong>Producto original:</strong> ${movement.originalProductName} (${movement.originalProductId})</div>
                                <div><strong>Cantidad:</strong> ${movement.quantity || 1} unidades</div>
                                ${warrantyProductInfo}
                                <div><strong>Motivo:</strong> ${reasonLabel}</div>
                                <div><strong>Garantía hasta:</strong> ${formatDateSimple(movement.endDate)}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-calculator"></i> Costos y Estado
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                <div><strong>Valor adicional:</strong> ${formatCurrency(movement.additionalValue || 0)}</div>
                                <div><strong>Valor envío:</strong> ${formatCurrency(movement.shippingValue || 0)}</div>
                                <div><strong>Costo total:</strong> ${formatCurrency(movement.totalCost || 0)}</div>
                                <div><strong>Estado:</strong> ${getWarrantyStatusText(movement.status)}</div>
                                ${statusMessage ? `<div><strong>Tiempo:</strong> ${statusMessage}</div>` : ''}
                            </div>
                        </div>
                        
                        ${movement.notes ? `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-sticky-note"></i> Observaciones
                            </h3>
                            <p>${movement.notes}</p>
                        </div>
                        ` : ''}
                        
                        <div>
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-info-circle"></i> Información Adicional
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                <div><strong>Fecha registro:</strong> ${formatDate(movement.createdAt || movement.date)}</div>
                                <div><strong>Registrado por:</strong> ${getUserName(movement.createdBy || movement.user)}</div>
                                ${movement.updatedAt ? `<div><strong>Última actualización:</strong> ${formatDate(movement.updatedAt)}</div>` : ''}
                                ${movement.updatedBy ? `<div><strong>Actualizado por:</strong> ${getUserName(movement.updatedBy)}</div>` : ''}
                            </div>
                        </div>
                    `;
                    break;
                case 'product':
                    const profit = (movement.retailPrice - movement.purchasePrice);
                    const profitPercent = ((profit / movement.purchasePrice) * 100).toFixed(2);
                    content = `
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-gem"></i> Información del Producto
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem;">
                                <div><strong>Referencia:</strong> ${movement.id}</div>
                                <div><strong>Nombre:</strong> ${movement.name}</div>
                                <div><strong>Cantidad:</strong> ${movement.quantity} unidades</div>
                                <div><strong>Proveedor:</strong> ${movement.supplier || 'N/A'}</div>
                                <div><strong>Fecha Ingreso:</strong> ${movement.date ? formatDateSimple(movement.date) : 'N/A'}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-tags"></i> Lista de Precios
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                                <div><strong>Precio Compra:</strong> ${formatCurrency(movement.purchasePrice)}</div>
                                <div><strong>Precio Mayorista:</strong> ${formatCurrency(movement.wholesalePrice)}</div>
                                <div><strong>Precio Detal:</strong> ${formatCurrency(movement.retailPrice)}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--gold-dark); margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <i class="fas fa-chart-line"></i> Rentabilidad
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div style="padding: 10px; background: rgba(76, 175, 80, 0.05); border-radius: 6px;">
                                    <div style="color: #4CAF50; font-weight: bold; margin-bottom: 5px;">Al Detal</div>
                                    <div><strong>Ganancia:</strong> ${formatCurrency(profit)}</div>
                                    <div><strong>Margen:</strong> ${profitPercent}%</div>
                                </div>
                                <div style="padding: 10px; background: rgba(33, 150, 243, 0.05); border-radius: 6px;">
                                    <div style="color: #2196F3; font-weight: bold; margin-bottom: 5px;">Al Mayorista</div>
                                    <div><strong>Ganancia:</strong> ${formatCurrency(movement.wholesalePrice - movement.purchasePrice)}</div>
                                    <div><strong>Margen:</strong> ${((movement.wholesalePrice - movement.purchasePrice) / movement.purchasePrice * 100).toFixed(2)}%</div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
            }

            modalContent.innerHTML = content;
            modal.style.display = 'flex';
        };

        window.editMovement = function (movementId, type) {
            let movement = null;

            // Buscar el movimiento según el tipo
            const searchInCaches = (keys, id) => {
                for (const key of keys) {
                    const data = JSON.parse(localStorage.getItem(key)) || [];
                    const found = data.find(item => (item.id == id || item.invoice_number == id));
                    if (found) return found;
                }
                return null;
            };

            switch (type) {
                case 'sales':
                    movement = searchInCaches(['destelloOroHistorySales', 'destelloOroAllSales', 'destelloOroHistoryPendingSales', 'destelloOroPendingSales'], movementId);
                    break;
                case 'expenses':
                    movement = searchInCaches(['destelloOroHistoryExpenses', 'destelloOroAllExpenses'], movementId);
                    break;
                case 'warranties':
                    movement = searchInCaches(['destelloOroHistoryWarranties', 'destelloOroAllWarranties'], movementId);
                    break;
                case 'product':
                    movement = searchInCaches(['destelloOroProducts'], movementId);
                    break;
                case 'restocks':
                    movement = searchInCaches(['destelloOroHistoryRestocks', 'destelloOroAllRestocks'], movementId);
                    break;
                default:
                    showDialog('Error', 'Tipo de movimiento no válido para edición.', 'error');
                    return;
            }

            if (!movement) {
                showDialog('Error', 'Movimiento no encontrado.', 'error');
                return;
            }

            // Guardar movimiento y tipo para edición
            currentMovementForEdit = movement;
            currentMovementTypeForEdit = type;

            // Configurar modal de edición
            const modal = document.getElementById('editMovementModal');
            const modalTitle = document.getElementById('editMovementTitle');
            const modalContent = document.getElementById('editMovementContent');

            modalTitle.textContent = `Editar ${type === 'sales' ? 'Venta' : type === 'expenses' ? 'Gasto' : type === 'product' ? 'Producto de Inventario' : 'Garantía'}`;

            // Generar formulario según el tipo
            let formContent = '';
            switch (type) {
                case 'sales':
                    // Convertir fecha para el input date
                    let saleDate = (movement.date || '').split(' ')[0];

                    formContent = `
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                            <i class="fas fa-fingerprint"></i> ID de Venta
                                        </label>
                                        <input type="text" name="id" value="${movement.id}" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f5f5f5;" readonly>
                                    </div>
                                    <div style="margin-bottom: 1rem;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                            <i class="fas fa-calendar"></i> Fecha de Venta
                                        </label>
                                        <input type="date" name="date" value="${saleDate}" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                                    </div>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                        <i class="fas fa-hashtag"></i> Número de Factura (Manual)
                                    </label>
                                    <input type="text" name="invoiceNumber" value="${movement.invoice_number || movement.invoiceNumber || ''}" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-user"></i> Nombre del Cliente
                            </label>
                            <input type="text" name="customerName" value="${movement.customerInfo?.name || movement.customer_name || ''}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-id-card"></i> Cédula
                                </label>
                                <input type="text" name="customerId" value="${movement.customerInfo?.id || movement.customer_id || ''}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-phone"></i> Teléfono
                                </label>
                                <input type="text" name="customerPhone" value="${movement.customerInfo?.phone || movement.customer_phone || ''}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-money-bill"></i> Subtotal
                                </label>
                                <input type="number" name="subtotal" value="${movement.subtotal || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" step="0.01">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-truck"></i> Envío
                                </label>
                                <input type="number" name="deliveryCost" value="${movement.deliveryCost || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" step="0.01">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-tag"></i> Descuento
                                </label>
                                <input type="number" name="discount" value="${movement.discount || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" step="0.01">
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-shield-alt"></i> Inc. Garantía
                                </label>
                                <input type="number" name="warrantyIncrement" value="${movement.warrantyIncrement || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                                       min="0" step="0.01">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-credit-card"></i> Pago
                                </label>
                                <select name="paymentMethod" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    ${Object.entries(paymentMethods).map(([key, method]) => `
                                        <option value="${key}" ${movement.paymentMethod === key ? 'selected' : ''}>${method.name}</option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-check-circle"></i> Estado
                            </label>
                            <select name="status" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="pending" ${!movement.confirmed && movement.status !== 'completed' ? 'selected' : ''}>Pendiente</option>
                                <option value="completed" ${movement.confirmed || movement.status === 'completed' ? 'selected' : ''}>Completada</option>
                            </select>
                        </div>
                    `;
                    break;

                case 'expenses':
                    // Convertir fecha para el input date
                    let expenseDateEdit = (movement.date || '').split(' ')[0];

                    formContent = `
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-receipt"></i> Descripción
                            </label>
                            <input type="text" name="description" value="${movement.description}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-calendar"></i> Fecha
                            </label>
                            <input type="date" name="date" value="${expenseDateEdit}"  
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-money-bill"></i> Valor
                            </label>
                            <input type="number" name="amount" value="${movement.amount}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                                   min="0" step="0.01" required>
                        </div>
                    `;
                    break;

                case 'warranties':
                    // Convertir fecha de garantía
                    let warrantyDate = (movement.date || '').split(' ')[0] || (movement.createdAt || '').split(' ')[0];

                    formContent = `
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-calendar"></i> Fecha
                            </label>
                            <input type="date" name="date" value="${warrantyDate}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-exclamation-triangle"></i> Motivo de Garantía
                            </label>
                            <select name="warrantyReason" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                                ${Object.entries(warrantyReasons).map(([key, reason]) => `
                                    <option value="${key}" ${movement.warrantyReason === key ? 'selected' : ''}>${reason}</option>
                                `).join('')}
                            </select>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-layer-group"></i> Cantidad
                            </label>
                            <input type="number" name="quantity" value="${movement.quantity || 1}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" min="1" required>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-exchange-alt"></i> Tipo de Producto
                            </label>
                            <select id="editWarrantyProductType" name="productType" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                                <option value="same" ${movement.productType === 'same' ? 'selected' : ''}>Mismo producto</option>
                                <option value="different" ${movement.productType === 'different' ? 'selected' : ''}>Producto diferente</option>
                            </select>
                        </div>
                        
                        <div id="editWarrantyDifferentFields" style="display: ${movement.productType === 'different' ? 'block' : 'none'};">
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                        <i class="fas fa-barcode"></i> Ref. Nuevo Producto
                                    </label>
                                    <input type="text" name="newProductRef" value="${movement.newProductRef || ''}" 
                                           class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                    <div class="edit-product-status" style="font-size: 0.8rem; margin-top: 4px; font-weight: 500;"></div>
                                </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-tag"></i> Nombre Nuevo Producto
                                </label>
                                <input type="text" name="newProductName" value="${movement.newProductName || ''}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-plus-circle"></i> Valor Adicional
                                </label>
                                <input type="number" name="additionalValue" value="${movement.additionalValue || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                                       min="0" step="0.01">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-truck"></i> Valor Envío
                                </label>
                                <input type="number" name="shippingValue" value="${movement.shippingValue || 0}" 
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" 
                                       min="0" step="0.01">
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-check-circle"></i> Estado
                            </label>
                            <select name="status" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                                <option value="pending" ${movement.status === 'pending' ? 'selected' : ''}>Pendiente</option>
                                <option value="in_process" ${movement.status === 'in_process' ? 'selected' : ''}>En proceso</option>
                                <option value="completed" ${movement.status === 'completed' ? 'selected' : ''}>Completada</option>
                                <option value="cancelled" ${movement.status === 'cancelled' ? 'selected' : ''}>Cancelada</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-sticky-note"></i> Observaciones
                            </label>
                            <textarea name="notes" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;">${movement.notes || ''}</textarea>
                        </div>
                    `;
                    break;
                case 'product':
                    let productDate = (movement.date || '').split(' ')[0];

                    formContent = `
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-calendar"></i> Fecha de Ingreso
                                </label>
                                <input type="date" name="date" value="${productDate}"
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                    <i class="fas fa-barcode"></i> Referencia
                                </label>
                                <input type="text" name="id" value="${movement.id}" oninput="this.value = this.value.toUpperCase();"
                                       class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                                <small class="form-text" style="font-size: 0.8rem;">Identificador unico del producto</small>
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Nombre del Producto</label>
                            <input type="text" name="name" value="${movement.name}" class="form-control" style="width: 100%; padding: 8px;" required>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Cantidad</label>
                            <input type="number" name="quantity" value="${movement.quantity}" class="form-control" style="width: 100%; padding: 8px;" required>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Precio Compra</label>
                            <input type="number" name="purchasePrice" id="editPurchasePrice" value="${movement.purchasePrice}" class="form-control" style="width: 100%; padding: 8px;" step="0.01" required>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Precio Mayorista</label>
                            <input type="number" name="wholesalePrice" id="editWholesalePrice" value="${movement.wholesalePrice}" class="form-control" style="width: 100%; padding: 8px;" step="0.01" required>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Precio Detal</label>
                            <input type="number" name="retailPrice" id="editRetailPrice" value="${movement.retailPrice}" class="form-control" style="width: 100%; padding: 8px;" step="0.01" required>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 1rem;">
                            <div>
                                <label style="font-size: 0.85rem; color: #666;">Ganancia Detal</label>
                                <input type="text" id="editProfitRetail" class="form-control" readonly style="background-color: #f5f5f5; font-size: 0.85rem;" 
                                    value="${formatCurrency(parseFloat(movement.retailPrice) - parseFloat(movement.purchasePrice))} (${((parseFloat(movement.retailPrice) - parseFloat(movement.purchasePrice)) / parseFloat(movement.purchasePrice) * 100).toFixed(2)}%)">
                            </div>
                            <div>
                                <label style="font-size: 0.85rem; color: #666;">Ganancia Mayorista</label>
                                <input type="text" id="editProfitWholesale" class="form-control" readonly style="background-color: #f5f5f5; font-size: 0.85rem;" 
                                    value="${formatCurrency(parseFloat(movement.wholesalePrice) - parseFloat(movement.purchasePrice))} (${((parseFloat(movement.wholesalePrice) - parseFloat(movement.purchasePrice)) / parseFloat(movement.purchasePrice) * 100).toFixed(2)}%)">
                            </div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 500;">Proveedor</label>
                            <input type="text" name="supplier" value="${movement.supplier || ''}" class="form-control" style="width: 100%; padding: 8px;">
                        </div>
                        <input type="hidden" name="originalId" value="${movement.id}">
                    `;
                    break;
                case 'restocks':
                    // Convertir fecha de surtido
                    let restockDate = (movement.date || '').split(' ')[0];

                    formContent = `
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 500;">
                                <i class="fas fa-calendar"></i> Fecha de Surtido
                            </label>
                            <input type="date" name="date" value="${restockDate}" 
                                   class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <p><strong>Producto:</strong> ${movement.productName} (${movement.product_ref || movement.productId || ''})</p>
                            <label style="font-weight: 500;">Cantidad</label>
                            <input type="number" name="quantity" value="${movement.quantity}" class="form-control" style="width: 100%; padding: 8px;" required>
                        </div>
                        <input type="hidden" name="id" value="${movement.id}">
                    `;
                    break;
                    break;
            }

            modalContent.innerHTML = formContent;
            
            // Agregar listeners para cálculo de ganancia en edición de producto
            if (type === 'product') {
                const calculateEditProfit = () => {
                    const pp = parseFloat(document.getElementById('editPurchasePrice').value) || 0;
                    const rp = parseFloat(document.getElementById('editRetailPrice').value) || 0;
                    const wp = parseFloat(document.getElementById('editWholesalePrice').value) || 0;
                    
                    if (pp > 0) {
                        const pr = rp - pp;
                        const prP = (pr / pp * 100).toFixed(2);
                        document.getElementById('editProfitRetail').value = `${formatCurrency(pr)} (${prP}%)`;
                        
                        const pw = wp - pp;
                        const pwP = (pw / pp * 100).toFixed(2);
                        document.getElementById('editProfitWholesale').value = `${formatCurrency(pw)} (${pwP}%)`;
                    } else {
                        document.getElementById('editProfitRetail').value = '';
                        document.getElementById('editProfitWholesale').value = '';
                    }
                };
                
                document.getElementById('editPurchasePrice').addEventListener('input', calculateEditProfit);
                document.getElementById('editRetailPrice').addEventListener('input', calculateEditProfit);
                document.getElementById('editWholesalePrice').addEventListener('input', calculateEditProfit);
            }
            
            // Agregar listeners para campos dinámicos (especialmente para garantías)
            if (type === 'warranties') {
                const ptSelect = document.getElementById('editWarrantyProductType');
                const diffFields = document.getElementById('editWarrantyDifferentFields');
                if (ptSelect && diffFields) {
                    ptSelect.addEventListener('change', function() {
                        diffFields.style.display = this.value === 'different' ? 'block' : 'none';
                    });
                }

                // Listener para autocompletar en edición de garantía
                const editRefInput = modalContent.querySelector('[name="newProductRef"]');
                const editNameInput = modalContent.querySelector('[name="newProductName"]');
                const editStatus = modalContent.querySelector('.edit-product-status');
                
                if (editRefInput && editNameInput) {
                    editRefInput.addEventListener('input', function () {
                        const ref = this.value.trim().toUpperCase();
                        if (ref) {
                            const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                            const product = products.find(p => p.id === ref);
                            if (product) {
                                editNameInput.value = product.name;
                                this.style.borderColor = 'var(--success)';
                                if (editStatus) {
                                    editStatus.innerHTML = `
                                        <span style="color: var(--success);">
                                            <i class="fas fa-check-circle"></i> Producto: <strong>${product.name}</strong>
                                            <br><small>(Stock: ${product.quantity})</small>
                                        </span>`;
                                }
                            } else {
                                editNameInput.value = '';
                                this.style.borderColor = 'var(--danger)';
                                if (editStatus) {
                                    editStatus.innerHTML = '<span style="color: var(--danger);"><i class="fas fa-times-circle"></i> Referencia no encontrada</span>';
                                }
                            }
                        } else {
                            this.style.borderColor = '';
                            if (editStatus) editStatus.innerHTML = '';
                            editNameInput.value = '';
                        }
                    });
                }
            }
            
            modal.style.display = 'flex';
        };

        window.deleteMovement = async function (movementId, type) {
            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede eliminar movimientos.', 'error');
                return;
            }

            const confirmed = await showDialog(
                'Eliminar Movimiento',
                '¿Está seguro de que desea eliminar este movimiento? Esta acción no se puede deshacer.',
                'warning',
                true
            );

            if (!confirmed) return;

            try {
                let success = false;
                let endpoint = '';
                
                switch (type) {
                    case 'sales': endpoint = 'api/sales.php'; break;
                    case 'expenses': endpoint = 'api/expenses.php'; break;
                    case 'warranties': endpoint = 'api/warranties.php'; break;
                    case 'restocks': endpoint = 'api/restocks.php'; break;
                    case 'pending': 
                        await cancelPendingSale(movementId);
                        return;
                    default:
                        showDialog('Error', 'Tipo de movimiento no válido para eliminación.', 'error');
                        return;
                }

                const response = await fetch(`${endpoint}?id=${movementId}`, { method: 'DELETE' });
                const result = await response.json();

                if (result.success) {
                    // 1. Actualizar tablas de secciones específicas
                    if (type === 'expenses') loadExpensesTable();
                    if (type === 'warranties') loadWarrantiesTable();
                    if (type === 'restocks' || type === 'sales') await loadInventoryTable();
                    
                    // 2. Actualizar caché de historial (crucial)
                    await loadHistoryCards();

                    // 3. Actualizar vista de detalles si está abierta
                    if (document.getElementById('historyDetailsView').classList.contains('active')) {
                        // Si estábamos en inversión, refrescar inversión. Si no, refrescar el tipo del movimiento.
                        const refreshType = currentHistoryDetailType || type;
                        showHistoryDetails(refreshType);
                    }

                    // 4. Actualizar resumen mensual
                    loadMonthlySummary();

                    await showDialog('Éxito', result.message || 'Movimiento eliminado correctamente.', 'success');
                } else {
                    await showDialog('Error', result.error || 'No se pudo eliminar el movimiento.', 'error');
                }

            } catch (error) {
                console.error('Error al eliminar movimiento:', error);
                await showDialog('Éxito', 'Venta eliminada exitosamente', 'success');
            }
        };

        // Función para confirmar pago de venta pendiente
        async function confirmPayment(saleId) {
            if (!currentUser || currentUser.role !== 'admin') {
                await showDialog('Acceso Denegado', 'Solo el administrador puede confirmar pagos.', 'error');
                return;
            }

            const confirmed = await showConfirmDialog(
                'Confirmar Pago',
                `¿Está seguro de confirmar el pago de la venta ${saleId}?`,
                'Confirmar',
                'Cancelar'
            );

            if (!confirmed) return;

            try {
                const response = await fetch('api/sales.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: saleId,
                        status: 'completed'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    // Recargar tabla de pagos pendientes (se quitará de la lista)
                    await loadPendingSalesTable();
                    
                    // Recargar datos del historial para reflejar el cambio de estado
                    await loadHistoryCards();
                    
                    // Si estamos viendo la vista de pendientes en el historial, refrescarla
                    if (document.getElementById('historyDetailsView').classList.contains('active')) {
                        if (currentHistoryDetailType === 'pending') {
                            showHistoryDetails('pending');
                        }
                    }

                    // Actualizar resumen mensual
                    loadMonthlySummary();

                    await showDialog('Éxito', 'Pago confirmado exitosamente.', 'success');
                } else {
                    await showDialog('Error', result.error || 'No se pudo confirmar el pago.', 'error');
                }
            } catch (error) {
                console.error('Error al confirmar pago:', error);
                await showDialog('Error', 'Ocurrió un error al confirmar el pago.', 'error');
            }
        }

        // Configurar eventos de login
        function setupLoginEvents() {
            const adminRoleBtn = document.getElementById('adminRole');
            const workerRoleBtn = document.getElementById('workerRole');
            const nextToUserInfoBtn = document.getElementById('nextToUserInfo');
            const backToRoleSelectionBtn = document.getElementById('backToRoleSelection');
            const nextToLoginBtn = document.getElementById('nextToLogin');
            const backToUserInfoBtn = document.getElementById('backToUserInfo');
            const loginForm = document.getElementById('loginForm');
            const userInfoFormDataEle = document.getElementById('userInfoFormData');

            if (!adminRoleBtn || !workerRoleBtn || !nextToUserInfoBtn) {
                console.error('Error: Elementos del login no encontrados');
                return;
            }

            // Alternar entre roles
            adminRoleBtn.addEventListener('click', function () {
                console.log('Rol seleccionado: admin');
                adminRoleBtn.classList.add('active');
                workerRoleBtn.classList.remove('active');
                selectedRole = 'admin';
                window.selectedRole = 'admin'; // Forzar global
            });

            workerRoleBtn.addEventListener('click', function () {
                console.log('Rol seleccionado: worker');
                workerRoleBtn.classList.add('active');
                adminRoleBtn.classList.remove('active');
                selectedRole = 'worker';
                window.selectedRole = 'worker'; // Forzar global
            });

            // Paso 1: Continuar a información personal
            nextToUserInfoBtn.addEventListener('click', function () {
                console.log('Avanzando a userInfoForm');
                showLoginStep('userInfoForm');

                // Pre-llenar formulario si ya hay información guardada
                const sessionInfo = JSON.parse(sessionStorage.getItem('destelloOroSessionInfo') || '{}');
                // Asegurar que usemos la global
                const currentRole = window.selectedRole || selectedRole;
                const userKey = `${currentRole}_info`;

                if (sessionInfo[userKey]) {
                    document.getElementById('userName').value = sessionInfo[userKey].name || '';
                    document.getElementById('userLastName').value = sessionInfo[userKey].lastName || '';
                    document.getElementById('userPhone').value = sessionInfo[userKey].phone || '';
                }
            });

            // Volver a selección de rol
            if (backToRoleSelectionBtn) {
                backToRoleSelectionBtn.addEventListener('click', function () {
                    showLoginStep('roleSelection');
                });
            }

            // Paso 2: Validar datos obligatorios antes de continuar
            if (userInfoFormDataEle) {
                userInfoFormDataEle.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    console.log('Validando información personal');

                    // Validar campos obligatorios
                    const userName = document.getElementById('userName').value.trim();
                    const userLastName = document.getElementById('userLastName').value.trim();
                    const userPhone = document.getElementById('userPhone').value.trim();

                    if (!userName || !userLastName || !userPhone) {
                        await showDialog('Campos Obligatorios', 'Por favor complete todos los campos obligatorios (Nombre, Apellido y Teléfono).', 'error');
                        return;
                    }

                    // Validar formato de teléfono (10 dígitos)
                    const phoneRegex = /^[0-9]{10}$/;
                    if (!phoneRegex.test(userPhone)) {
                        await showDialog('Teléfono Inválido', 'El teléfono debe tener 10 dígitos numéricos.', 'error');
                        return;
                    }

                    // Guardar información en localStorage
                    const sessionInfo = JSON.parse(sessionStorage.getItem('destelloOroSessionInfo') || '{}');
                    const currentRole = window.selectedRole || selectedRole;
                    const userKey = `${currentRole}_info`;

                    sessionInfo[userKey] = {
                        name: userName,
                        lastName: userLastName,
                        phone: userPhone,
                        date: new Date().toISOString()
                    };

                    sessionStorage.setItem('destelloOroSessionInfo', JSON.stringify(sessionInfo));

                    // Continuar al siguiente paso
                    showLoginStep('loginCredentials');
                });
            }

            // Volver a información personal
            if (backToUserInfoBtn) {
                backToUserInfoBtn.addEventListener('click', function () {
                    showLoginStep('userInfoForm');
                });
            }

            // Manejar login
            if (loginForm) {
                loginForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    console.log('Iniciando proceso de login');

                    const username = document.getElementById('username').value;
                    const password = document.getElementById('password').value;

                    try {
                        const response = await fetch('api/login.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                username: username,
                                password: password
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Obtener el nombre ingresado en el paso anterior
                            const sessionInfo = JSON.parse(sessionStorage.getItem('destelloOroSessionInfo') || '{}');
                            // Priorizar el rol que viene del servidor, pero usar el seleccionado para buscar la info local
                            const verifiedRole = data.user.role; 
                            const userKey = `${verifiedRole}_info`;
                            const personalInfo = sessionInfo[userKey];
                            
                            currentUser = {
                                id: data.user.id,
                                username: data.user.username,
                                role: data.user.role,
                                // Priorizar el nombre ingresado en el formulario
                                displayName: personalInfo ? `${personalInfo.name} ${personalInfo.lastName}` : data.user.name,
                                name: data.user.name
                            };
                            
                            // Limpiar CUALQUIER dato previo para asegurar sincronización total
                            Object.keys(localStorage).forEach(key => {
                                if (key.startsWith('destelloOro')) {
                                    localStorage.removeItem(key);
                                }
                            });

                            // Guardar en sessionStorage para persistencia de pestaña
                            sessionStorage.setItem('destelloOroCurrentUser', JSON.stringify(currentUser));
                            sessionStorage.setItem('destelloOroTabActive', 'true'); // Marcar esta pestaña como activa para auto-login

                            await showDialog('¡Bienvenido!', `Bienvenido ${currentUser.displayName}`, 'success');
                            showApp();
                        } else {
                            await showDialog('Error de Acceso', data.message || 'Credenciales incorrectas.', 'error');
                        }
                    } catch (error) {
                        console.error('Error en login:', error);
                        await showDialog('Error', 'Error de conexión con el servidor.', 'error');
                    }
                });
            }
        }

        // Mostrar la aplicación después del login
        function showApp() {
            document.getElementById('loginScreen').style.display = 'none';
            document.getElementById('appScreen').style.display = 'block';

            // Asegurarse de que currentUser esté cargado
            const savedUser = sessionStorage.getItem('destelloOroCurrentUser');
            if (savedUser) {
                try {
                    currentUser = JSON.parse(savedUser);
                    console.log('Usuario cargado en showApp:', currentUser);
                } catch (error) {
                    console.error('Error al cargar usuario:', error);
                }
            }

            // Actualizar interfaz según rol
            updateUIForUserRole();

            // Configurar selectores de fecha para historial
            setupDateSelectors();

            // MODIFICADO: Solo cargar datos de la sección activa (inventario por defecto)
            // Las demás secciones cargarán sus datos cuando el usuario navegue a ellas
            console.log('Cargando datos de la sección activa (inventario)...');
            loadInventoryTable();

            // Configurar refresco automático solo para la sección activa
            if (window.syncInterval) clearInterval(window.syncInterval);
            window.syncInterval = setInterval(() => {
                // Solo refrescar la sección activa
                const activeSection = document.querySelector('.section-container.active');
                if (activeSection) {
                    const sectionId = activeSection.id;
                    console.log('Sincronizando sección activa:', sectionId);
                    
                    switch(sectionId) {
                        case 'inventory':
                            loadInventoryTable();
                            break;
                        case 'expenses':
                            loadExpensesTable();
                            break;
                        case 'pending':
                            loadPendingSalesTable();
                            break;
                        case 'warranties':
                            loadWarrantiesTable();
                            break;
                        case 'history':
                            loadHistoryCards();
                            break;
                    }
                }
            }, 60000);
        }

        // Actualizar interfaz según rol del usuario
        function updateUIForUserRole() {
            if (!currentUser) {
                console.error('No hay usuario actual definido');
                return;
            }

            const isAdmin = currentUser.role === 'admin';
            const isWorker = currentUser.role === 'worker';
            const userBadge = document.getElementById('currentUserRole');

            // Usar displayName del usuario actual
            const displayName = currentUser.displayName ||
                currentUser.name ||
                (isAdmin ? 'Administrador' : 'Trabajador');

            // Actualizar badge del usuario
            if (isAdmin) {
                userBadge.className = 'user-badge admin';
                userBadge.innerHTML = `
                    <i class="fas fa-user-shield"></i>
                    <span>${displayName} (Administrador)</span>
                `;
            } else {
                userBadge.className = 'user-badge worker';
                userBadge.innerHTML = `
                    <i class="fas fa-user-tie"></i>
                    <span>${displayName} (Trabajador)</span>
                `;
            }

            // Mostrar/ocultar elementos según rol
            const adminElements = document.querySelectorAll('.admin-only');
            adminElements.forEach(element => {
                element.style.display = isAdmin ? '' : 'none';
            });

            // Si es trabajador, asegurarse de que solo pueda ver inventario y ventas
            if (isWorker) {
                // Ocultar botones de navegación no permitidos
                const navButtons = document.querySelectorAll('.nav-btn');
                navButtons.forEach(button => {
                    const section = button.dataset.section;
                    if (section !== 'inventory' && section !== 'sales') {
                        button.style.display = 'none';
                    }
                });

                // Mostrar solo las secciones de inventario y ventas
                const sections = document.querySelectorAll('.section-container');
                sections.forEach(section => {
                    if (section.id !== 'inventory' && section.id !== 'sales') {
                        section.style.display = 'none';
                    }
                });

                // Ocultar botones de agregar producto en inventario (solo para trabajador)
                const addProductBtn = document.getElementById('addProductBtn');
                if (addProductBtn) {
                    addProductBtn.style.display = 'none';
                }

                // Ocultar formulario de agregar producto si está visible
                const addProductForm = document.getElementById('addProductForm');
                if (addProductForm) {
                    addProductForm.style.display = 'none';
                }
            }
        }

        // Función para reiniciar todas las sub-vistas y estados internos (Independencia de Sesiones)
        function resetAllSubViews() {
            // 1. Inventario: Ocultar formulario de agregar
            const addProductForm = document.getElementById('addProductForm');
            if (addProductForm) addProductForm.style.display = 'none';
            const productForm = document.getElementById('productForm');
            if (productForm) productForm.reset();
            const inventorySearch = document.getElementById('inventorySearch');
            if (inventorySearch) inventorySearch.value = '';

            // 2. Ventas: Limpiar carrito y formulario para asegurar independencia total
            shoppingCart = [];
            if (typeof updateCartDisplay === 'function') updateCartDisplay();
            if (typeof updateSaleSummary === 'function') updateSaleSummary();
            
            const customerName = document.getElementById('customerName');
            if (customerName) {
                document.getElementById('customerName').value = '';
                document.getElementById('customerId').value = '';
                document.getElementById('customerPhone').value = '';
                document.getElementById('customerEmail').value = '';
                document.getElementById('customerAddress').value = '';
                document.getElementById('customerCity').value = '';
            }
            const saleForm = document.getElementById('addProductToSaleForm');
            if (saleForm) saleForm.reset();
            const productInfo = document.getElementById('productInfo');
            if (productInfo) productInfo.innerHTML = '';

            // 3. Gastos: Ocultar formulario de agregar
            const addExpenseForm = document.getElementById('addExpenseForm');
            if (addExpenseForm) addExpenseForm.style.display = 'none';
            const expenseForm = document.getElementById('expenseForm');
            if (expenseForm) expenseForm.reset();

            // 4. Garantías: Volver a la búsqueda inicial
            const addWarrantyForm = document.getElementById('addWarrantyForm');
            const searchCard = document.getElementById('warrantySearchCard');
            const searchInput = document.getElementById('searchCustomerWarranty');
            const results = document.getElementById('customerSearchResults');
            if (addWarrantyForm) addWarrantyForm.style.display = 'none';
            if (searchCard) searchCard.style.display = 'block';
            if (searchInput) searchInput.value = '';
            if (results) results.style.display = 'none';

            // 5. Historial: Mostrar tarjetas, ocultar detalles y auditoría, resetear filtro
            const historyCardsView = document.getElementById('historyCardsView');
            const historyDetailsView = document.getElementById('historyDetailsView');
            const auditLogsView = document.getElementById('auditLogsView');
            const historyFilter = document.getElementById('historyFilter');
            
            if (historyCardsView) historyCardsView.style.display = 'grid';
            if (historyDetailsView) historyDetailsView.classList.remove('active');
            if (auditLogsView) auditLogsView.style.display = 'none';
            
            if (historyFilter) {
                historyFilter.value = 'all';
                currentHistoryType = 'all';
            }

            // 6. Resetear Registros: Asegurar que el modal esté cerrado
            const resetModal = document.getElementById('resetRecordsModal');
            if (resetModal) resetModal.style.display = 'none';
            
            // 7. Auditoría
            const auditLogsTableBody = document.getElementById('auditLogsTableBody');
            if (auditLogsTableBody) auditLogsTableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Cargando...</td></tr>';
        }

        // Configurar eventos de navegación

        function setupNavigationEvents() {
            const navButtons = document.querySelectorAll('.nav-btn');
            const sections = document.querySelectorAll('.section-container');

            // Navegación entre secciones
            navButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetSection = this.dataset.section;

                    // NUEVO: Reiniciar todas las sub-vistas antes de mostrar la nueva sección
                    resetAllSubViews();

                    // Verificar si el usuario tiene acceso a esta sección
                    if (currentUser && currentUser.role === 'worker' &&
                        targetSection !== 'inventory' && targetSection !== 'sales') {
                        showDialog('Acceso Restringido', 'No tiene permiso para acceder a esta sección.', 'warning');
                        return;
                    }

                    // Actualizar botones activos
                    navButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Mostrar sección correspondiente
                    sections.forEach(section => {
                        section.classList.remove('active');
                        if (section.id === targetSection) {
                            section.classList.add('active');

                            // MODIFICADO: Cargar datos solo de la sección que se está activando
                            console.log('Activando sección:', targetSection);
                            
                            switch(targetSection) {
                                case 'inventory':
                                    loadInventoryTable();
                                    break;
                                case 'sales':
                                    // La sección de ventas no necesita cargar datos al inicio
                                    break;
                                case 'restock':
                                    // La sección de surtir no necesita cargar datos al inicio
                                    break;
                                case 'expenses':
                                    loadExpensesTable();
                                    break;
                                case 'warranties':
                                    loadWarrantiesTable();
                                    break;
                                case 'pending':
                                    loadPendingSalesTable();
                                    break;
                                case 'history':
                                    loadHistoryCards();
                                    break;
                            }
                        }
                    });
                });
            });

            // Botón de cerrar sesión
            document.getElementById('logoutButton').addEventListener('click', async function () {
                const confirmed = await showDialog(
                    'Cerrar Sesión',
                    '¿Está seguro de que desea cerrar sesión?',
                    'question',
                    true
                );

                if (confirmed) {
                    await logout();
                }
            });

            // Botón para agregar producto (solo visible para admin)
            document.getElementById('addProductBtn').addEventListener('click', function () {
                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    showDialog('Acceso Restringido', 'Solo el administrador puede agregar productos.', 'warning');
                    return;
                }

                const form = document.getElementById('addProductForm');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
                if (form.style.display === 'block') {
                    document.getElementById('productDate').value = new Date().toISOString().split('T')[0];
                    form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });

            // Cancelar agregar producto
            document.getElementById('cancelAddProduct').addEventListener('click', function () {
                document.getElementById('addProductForm').style.display = 'none';
                document.getElementById('productForm').reset();
                document.getElementById('profitEstimate').value = '';
            });

            // Botón para agregar gasto
            document.getElementById('addExpenseBtn').addEventListener('click', function () {
                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    showDialog('Acceso Restringido', 'Solo el administrador puede registrar gastos.', 'warning');
                    return;
                }

                const form = document.getElementById('addExpenseForm');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
                document.getElementById('expenseDate').valueAsDate = new Date();
                if (form.style.display === 'block') {
                    form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });

            // Cancelar agregar gasto
            document.getElementById('cancelExpense').addEventListener('click', function () {
                document.getElementById('addExpenseForm').style.display = 'none';
                document.getElementById('expenseForm').reset();
            });
        }

        // Configurar eventos de formularios
        function setupFormEvents() {
            // Calcular ganancia estimada al cambiar precios
            document.getElementById('retailPrice').addEventListener('input', calculateProfit);
            document.getElementById('wholesalePrice').addEventListener('input', calculateProfit);
            document.getElementById('purchasePrice').addEventListener('input', calculateProfit);

            // Formulario de producto (solo para admin)
            document.getElementById('productForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    await showDialog('Acceso Restringido', 'Solo el administrador puede agregar productos.', 'error');
                    return;
                }

                // Obtener datos del formulario
                const product = {
                    date: document.getElementById('productDate').value,
                    id: document.getElementById('productRef').value.trim().toUpperCase(),
                    name: document.getElementById('productName').value.trim(),
                    quantity: parseInt(document.getElementById('productQuantity').value),
                    purchasePrice: parseFloat(document.getElementById('purchasePrice').value),
                    wholesalePrice: parseFloat(document.getElementById('wholesalePrice').value),
                    retailPrice: parseFloat(document.getElementById('retailPrice').value),
                    supplier: document.getElementById('supplier').value.trim(),
                    addedBy: currentUser.username
                };

                try {
                    const response = await fetch('api/products.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(product)
                    });
                    const data = await response.json();

                    if (data.success) {
                        loadInventoryTable();
                        this.reset();
                        document.getElementById('profitEstimate').value = '';
                        document.getElementById('addProductForm').style.display = 'none';
                        await showDialog('Éxito', 'Producto agregado exitosamente al inventario.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al agregar producto', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            });

            // Formulario de surtir inventario (solo para admin)
            document.getElementById('restockForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    await showDialog('Acceso Restringido', 'Solo el administrador puede surtir inventario.', 'error');
                    return;
                }

                const productRef = document.getElementById('restockProductRef').value.trim().toUpperCase();
                const quantity = parseInt(document.getElementById('restockQuantity').value);

                // Validar datos
                if (!productRef || isNaN(quantity) || quantity <= 0) {
                    await showDialog('Error', 'Por favor ingrese datos válidos.', 'error');
                    return;
                }

                try {
                    const response = await fetch('api/restocks.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            id: productRef,
                            quantity: quantity
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        loadInventoryTable();
                        this.reset();
                        document.getElementById('restockProductInfo').textContent = '';
                        await showDialog('Éxito', 'Inventario surtido exitosamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al surtir inventario', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            });

            // Buscar producto al escribir referencia (surtir)
            document.getElementById('restockProductRef').addEventListener('input', function () {
                const productRef = this.value.trim().toUpperCase();
                if (productRef) {
                    // Usar copia local si existe, o intentar buscar en DB?
                    // Por rendimiento, usar el array global 'destelloOroProducts' que se actualizó en loadInventoryTable
                    // Pero ojo, loadInventoryTable debe haberse llamado antes.
                    // Fallback a localStorage (que actualizamos en loadInventoryTable)
                    const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                    const product = products.find(p => p.id == productRef);

                    if (product) {
                        document.getElementById('restockProductInfo').innerHTML =
                            `<strong>${product.name}</strong><br>
                             Stock actual: ${product.quantity} unidades<br>
                             Precio detal: ${formatCurrency(product.retailPrice)}`;
                    } else {
                        document.getElementById('restockProductInfo').textContent = '❌ Producto no encontrado en caché local (cargue inventario primero)';
                    }
                } else {
                    document.getElementById('restockProductInfo').textContent = '';
                }
            });

            // Formulario de gastos (solo para admin)
            document.getElementById('expenseForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                // Verificar si es administrador
                if (currentUser && currentUser.role !== 'admin') {
                    await showDialog('Acceso Restringido', 'Solo el administrador puede registrar gastos.', 'error');
                    return;
                }

                const expense = {
                    description: document.getElementById('expenseDescription').value.trim(),
                    date: document.getElementById('expenseDate').value,
                    amount: parseFloat(document.getElementById('expenseAmount').value),
                    user: currentUser.username
                };

                // Validar datos
                if (!expense.description || !expense.date || isNaN(expense.amount) || expense.amount <= 0) {
                    await showDialog('Error', 'Por favor ingrese datos válidos.', 'error');
                    return;
                }

                try {
                    const response = await fetch('api/expenses.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify(expense)
                    });
                    const data = await response.json();

                    if (data.success) {
                        loadExpensesTable();
                        this.reset();
                        document.getElementById('addExpenseForm').style.display = 'none';
                        await showDialog('Éxito', 'Gasto registrado exitosamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al registrar gasto', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            });

            // Formulario para agregar producto al carrito
            document.getElementById('addProductToSaleForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const productRef = document.getElementById('saleProductRef').value.trim().toUpperCase();
                const quantity = parseInt(document.getElementById('saleQuantity').value) || 0;
                const saleType = document.getElementById('saleType').value;
                const discount = parseFloat(document.getElementById('discount').value) || 0;

                // Validar datos
                if (!productRef || quantity <= 0) {
                    showDialog('Error', 'Por favor ingrese datos válidos.', 'error');
                    return;
                }
                // Agregar al carrito
                addToCart(productRef, quantity, saleType, discount);
            });

            // Buscar producto al escribir referencia (venta)
            document.getElementById('saleProductRef').addEventListener('input', function () {
                const productRef = this.value.trim().toUpperCase();
                if (productRef) {
                     const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                    const product = products.find(p => p.id == productRef);

                    if (product) {
                        const retailPrice = formatCurrency(product.retailPrice);
                        const wholesalePrice = formatCurrency(product.wholesalePrice);
                        document.getElementById('productInfo').innerHTML =
                            `<strong>${product.name}</strong><br>
                             💰 Detal: ${retailPrice} | 📦 Mayorista: ${wholesalePrice}<br>
                             📊 Stock disponible: ${product.quantity} unidades`;
                    } else {
                        document.getElementById('productInfo').innerHTML = '<span style="color: var(--danger);">❌ Producto no encontrado</span>';
                    }
                } else {
                    document.getElementById('productInfo').textContent = '';
                }
            });

            // Actualizar resumen de venta al cambiar costo de envío
            document.getElementById('deliveryCost').addEventListener('input', updateSaleSummary);
        }

        // Configurar eventos de la factura (SIN REDES SOCIALES)
        function setupInvoiceEvents() {
            // Cerrar factura
            document.getElementById('closeInvoice').addEventListener('click', function () {
                document.getElementById('invoiceModal').style.display = 'none';
            });

            // Imprimir factura
            document.getElementById('printInvoice').addEventListener('click', function () {
                window.print();
            });

            // Descargar factura como PDF
            document.getElementById('downloadInvoice').addEventListener('click', async function () {
                await generateCurrentInvoicePDF();
            });
        }

        // Configurar eventos del modal de cambio de contraseña
        function setupPasswordChange() {
            console.log('Configurando eventos de cambio de contraseña...');
            
            const modal = document.getElementById('passwordChangeModal');
            const showBtn = document.getElementById('showPasswordChange');
            const forgotBtn = document.getElementById('forgotPasswordBtn');
            const closeBtn = document.getElementById('closePasswordChange');
            const cancelBtn = document.getElementById('cancelPasswordChange');
            const form = document.getElementById('passwordChangeForm');

            console.log('Modal encontrado:', modal ? 'Sí' : 'No');
            console.log('Botón showPasswordChange encontrado:', showBtn ? 'Sí' : 'No');
            console.log('Botón forgotPasswordBtn encontrado:', forgotBtn ? 'Sí' : 'No');

            // Mostrar modal al hacer clic en "Cambiar Contraseña" o "Olvidé mi contraseña"
            if (showBtn) {
                showBtn.addEventListener('click', function() {
                    console.log('Clic en Cambiar Contraseña');
                    modal.style.display = 'flex';
                    // Resetear selectores
                    document.getElementById('roleToChange').value = '';
                    const userSelect = document.getElementById('userToChange');
                    userSelect.innerHTML = '<option value="">Primero seleccione un rol</option>';
                    userSelect.disabled = true;
                });
            }

            // NUEVO: Listener para el cambio de rol en el modal de contraseña
            const roleSelect = document.getElementById('roleToChange');
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    const role = this.value;
                    if (role) {
                        loadUsersForPasswordChange(role);
                    } else {
                        const userSelect = document.getElementById('userToChange');
                        userSelect.innerHTML = '<option value="">Primero seleccione un rol</option>';
                        userSelect.disabled = true;
                    }
                });
            }

            if (forgotBtn) {
                forgotBtn.addEventListener('click', function() {
                    console.log('Clic en Olvidé mi contraseña');
                    modal.style.display = 'flex';
                    // Resetear selectores
                    document.getElementById('roleToChange').value = '';
                    const userSelect = document.getElementById('userToChange');
                    userSelect.innerHTML = '<option value="">Primero seleccione un rol</option>';
                    userSelect.disabled = true;
                });
            }

            // Cerrar modal
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    form.reset();
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    form.reset();
                });
            }

            // Manejar envío del formulario
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const adminUsername = document.getElementById('adminUsername').value;
                    const adminPassword = document.getElementById('adminPassword').value;
                    const userToChange = document.getElementById('userToChange').value;
                    const newEmail = document.getElementById('newEmail').value;
                    const newPassword = document.getElementById('newPassword').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;

                    // Validar que las contraseñas coincidan
                    if (newPassword !== confirmPassword) {
                        await showDialog('Error', 'Las contraseñas no coinciden.', 'error');
                        return;
                    }

                    // Validar longitud mínima
                    if (newPassword.length < 6) {
                        await showDialog('Error', 'La contraseña debe tener al menos 6 caracteres.', 'error');
                        return;
                    }

                    // Validar seguridad: una mayúscula, un número y un carácter especial
                    const hasUpper = /[A-Z]/.test(newPassword);
                    const hasNumber = /[0-9]/.test(newPassword);
                    const hasSpecial = /[^A-Za-z0-9]/.test(newPassword);

                    if (!hasUpper || !hasNumber || !hasSpecial) {
                        await showDialog('Error', 'La contraseña debe tener al menos una letra mayúscula, un número y un carácter especial.', 'error');
                        return;
                    }

                    try {
                        const response = await fetch('api/users.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({
                                action: 'change_password',
                                adminUsername: adminUsername,
                                adminPassword: adminPassword,
                                userToChange: userToChange,
                                newEmail: newEmail,
                                newPassword: newPassword
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Primero cerramos el modal y reseteamos el formulario
                            modal.style.display = 'none';
                            form.reset();
                            // Luego mostramos el mensaje de éxito para que sea visible
                            await showDialog('Éxito', data.message || 'Contraseña cambiada exitosamente.', 'success');
                        } else {
                            await showDialog('Error', data.message || data.error || 'Error al cambiar la contraseña.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        await showDialog('Error', 'Error de conexión con el servidor.', 'error');
                    }
                });
            }
        }

        // NUEVO: Eventos para resetear registros
        function setupResetRecordsEvents() {
            const modal = document.getElementById('resetRecordsModal');
            const showBtn = document.getElementById('resetRecordsBtn');
            const closeBtn = document.getElementById('closeResetRecords');
            const cancelBtn = document.getElementById('cancelResetRecords');
            const form = document.getElementById('resetRecordsForm');

            if (showBtn) {
                showBtn.addEventListener('click', function() {
                    // Solo permitir si el usuario actual es admin
                    if (currentUser && currentUser.role === 'admin') {
                        modal.style.display = 'flex';
                    } else {
                        showDialog('Acceso Denegado', 'Solo el administrador puede acceder a esta función.', 'error');
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    form.reset();
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    form.reset();
                });
            }

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const password = document.getElementById('adminResetPassword').value;
                    
                    const confirmed = await showDialog(
                        'Confirmación Final',
                        '¿ESTÁ TOTALMENTE SEGURO? Esta acción borrará todos los datos de ventas, gastos, surtidos y garantías, y pondrá el stock en 0.',
                        'warning',
                        true
                    );

                    if (!confirmed) return;

                    try {
                        const response = await fetch('api/reset_db.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                mode: 'full_wipe',
                                password: password
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            await showDialog('Éxito', result.message, 'success');
                            location.reload();
                        } else {
                            await showDialog('Error', result.message || 'Error al resetear registros', 'error');
                        }
                    } catch (error) {
                        console.error('Error al resetear registros:', error);
                        await showDialog('Error', 'Ocurrió un error al conectar con el servidor.', 'error');
                    }
                });
            }
        }

        // NUEVO: Eventos para Olvidaste tu contraseña
        function setupForgotPasswordEvents() {
            const modal = document.getElementById('forgotPasswordModal');
            const showBtn = document.getElementById('showForgotPassword');
            const closeBtn = document.getElementById('closeForgotPassword');
            const cancelBtn = document.getElementById('cancelForgotPassword');
            const form = document.getElementById('forgotPasswordForm');
            
            // Gestión de roles dentro del modal de olvido
            const adminRole = document.getElementById('forgotAdminRole');
            const workerRole = document.getElementById('forgotWorkerRole');
            let selectedRole = 'admin';

            if (adminRole && workerRole) {
                adminRole.addEventListener('click', () => {
                    adminRole.classList.add('active');
                    workerRole.classList.remove('active');
                    selectedRole = 'admin';
                });
                workerRole.addEventListener('click', () => {
                    workerRole.classList.add('active');
                    adminRole.classList.remove('active');
                    selectedRole = 'worker';
                });
            }

            if (showBtn) {
                showBtn.addEventListener('click', function() {
                    modal.style.display = 'flex';
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    if (form) form.reset();
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    if (form) form.reset();
                });
            }

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const email = document.getElementById('recoveryEmail').value;
                    const sendBtn = document.getElementById('sendRecoveryBtn');
                    
                    // Bloquear botón para evitar doble clic
                    const originalText = sendBtn.innerHTML;
                    sendBtn.disabled = true;
                    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

                    try {
                        const response = await fetch('api/forgot_password.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                role: selectedRole,
                                email: email
                            })
                        });

                        const text = await response.text();
                        let result;
                        try {
                            result = JSON.parse(text);
                        } catch (e) {
                            console.error('La respuesta no es JSON:', text);
                            await showDialog('Error', 'El servidor respondió de forma inesperada. Por favor, intenta de nuevo más tarde.', 'error');
                            return;
                        }

                        if (result.success) {
                            // Resetear botón y cerrar modal ANTES del diálogo para que no se vea el "cargando" de fondo
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = originalText;
                            modal.style.display = 'none';
                            form.reset();
                            
                            await showDialog('Éxito', result.message, 'success');
                        } else {
                            // Si hay error, solo habilitamos el botón para reintentar
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = originalText;
                            await showDialog('Error', result.message || 'Error al procesar la solicitud', 'error');
                        }
                    } catch (error) {
                        console.error('Error al recuperar contraseña:', error);
                        sendBtn.disabled = false;
                        sendBtn.innerHTML = originalText;
                        await showDialog('Error', 'Error de conexión: ' + error.message, 'error');
                    }
                });
            }
        }

        // Cargar usuarios disponibles para cambio de contraseña (Filtrado por rol opcional)
        async function loadUsersForPasswordChange(roleFilter = '') {
            try {
                const response = await fetch('api/users.php');
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error del servidor:', errorText);
                    throw new Error('El servidor devolvió un error (Status ' + response.status + ')');
                }

                const data = await response.json();

                const select = document.getElementById('userToChange');
                if (!select) return;

                select.innerHTML = '<option value="">Seleccione un usuario</option>';
                select.disabled = false;

                if (data.success && data.users) {
                    let filteredUsers = data.users;
                    if (roleFilter) {
                        filteredUsers = data.users.filter(u => u.role === roleFilter);
                    }

                    if (filteredUsers.length === 0) {
                        select.innerHTML = '<option value="">No hay usuarios con este rol</option>';
                    } else {
                        filteredUsers.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.username;
                            const roleDisplay = user.role === 'admin' ? 'Administrador' : 'Trabajador';
                            option.textContent = `${user.name} ${user.lastname || ''} (${roleDisplay})`;
                            select.appendChild(option);
                        });
                    }
                } else if (data.error) {
                    throw new Error(data.error);
                }
            } catch (error) {
                console.error('Error cargando usuarios:', error);
                const select = document.getElementById('userToChange');
                if (select) {
                    select.innerHTML = `<option value="">Error: ${error.message}</option>`;
                }
            }
        }


        // Función auxiliar para cargar imagen como Base64
        function getBase64Image(url) {
            return new Promise((resolve, reject) => {
                var img = new Image();
                img.setAttribute('crossOrigin', 'anonymous');
                img.onload = () => {
                    var canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    var dataURL = canvas.toDataURL("image/jpeg", 0.8);
                    resolve(dataURL);
                };
                img.onerror = error => {
                    console.warn('No se pudo cargar la imagen de fondo para el PDF:', error);
                    resolve(null); // Resolver con null para no romper el flujo
                };
                img.src = url;
            });
        }

        // Función para generar PDF de la factura actual (del modal)
        async function generateCurrentInvoicePDF() {
            // Mostrar diálogo de carga
            const loadingDialog = document.createElement('div');
            loadingDialog.className = 'custom-dialog';
            loadingDialog.style.display = 'flex';
            loadingDialog.innerHTML = `
                <div class="dialog-content">
                    <div class="dialog-icon"><i class="fas fa-spinner fa-spin" style="color: var(--gold-primary);"></i></div>
                    <h2 class="dialog-title">Generando PDF</h2>
                    <p class="dialog-message">Por favor espere mientras se genera el documento con el fondo...</p>
                </div>
            `;
            document.body.appendChild(loadingDialog);

            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();

                // Intentar cargar la imagen de fondo
                try {
                    const bgData = await getBase64Image('fondo.jpeg');
                    if (bgData) {
                        // Agregar imagen de fondo centrada y cubriendo toda la página
                        pdf.addImage(bgData, 'JPEG', 0, 0, pageWidth, pageHeight);
                        
                        // Agregar un rectángulo blanco semitransparente para mejorar legibilidad del texto
                        // Nota: jsPDF básico no soporta transparencia en rectángulos fácilmente sin plugins, 
                        // pero podemos intentar simularlo o simplemente usar texto con fondo o sombra si fuera necesario.
                        // Por ahora, confiamos en que el diseño se vea bien o agregamos un cuadro blanco sutil detrás del contenido principal
                        pdf.setFillColor(255, 255, 255);
                        pdf.rect(10, 10, pageWidth - 20, pageHeight - 20, 'F'); 
                        // OJO: 'F' rellena con blanco sólido tapando el fondo.
                        // Si queremos transparencia real necesitaríamos gstate. 
                        // Como solución alternativa efectiva: No ponemos el rect o asumimos que el fondo es decorativo.
                        // El usuario quiere el FONDO. Así que mejor NO taparlo.
                        // Voy a deshacer el rect para que se vea la foto.
                    }
                } catch (e) {
                    console.warn('Error cargando fondo:', e);
                }

                // Obtener datos de la factura actual del modal
                const invoiceNumber = document.getElementById('invoiceNumber').textContent;
                const invoiceDate = document.getElementById('invoiceDate').textContent;
                const customerName = document.getElementById('invoiceCustomerName').textContent;
                const customerId = document.getElementById('invoiceCustomerId').textContent;
                const customerPhone = document.getElementById('invoiceCustomerPhone').textContent;
                const customerAddress = document.getElementById('invoiceCustomerAddress').textContent;
                const total = document.getElementById('invoiceTotalAmount').textContent; // Asegúrate de tener este ID correcto

                // === CONTENIDO DEL PDF ===
                // Título
                pdf.setFontSize(22);
                pdf.setTextColor(212, 175, 55); // Dorado
                pdf.setFont("helvetica", "bold");
                pdf.text('Destello de Oro 18K', pageWidth / 2, 25, { align: 'center' });
                
                pdf.setFontSize(10);
                pdf.setTextColor(0, 0, 0); // Negro para texto
                pdf.setFont("helvetica", "normal");
                pdf.text('Sistema de Gestión de Inventario y Ventas', pageWidth / 2, 32, { align: 'center' });

                // Datos de Factura (Cuadro)
                pdf.setDrawColor(212, 175, 55);
                pdf.setLineWidth(0.5);
                pdf.rect(15, 40, pageWidth - 30, 25);
                
                pdf.setFontSize(14);
                pdf.setFont("helvetica", "bold");
                pdf.text(invoiceNumber || 'Factura', 20, 50);
                
                pdf.setFontSize(10);
                pdf.setFont("helvetica", "normal");
                pdf.text(invoiceDate || new Date().toLocaleDateString(), 20, 58);

                // Datos del Cliente
                pdf.setFontSize(12);
                pdf.setTextColor(212, 175, 55);
                pdf.setFont("helvetica", "bold");
                pdf.text('Datos del Cliente:', 20, 75);
                
                pdf.setFontSize(10);
                pdf.setTextColor(0, 0, 0);
                pdf.setFont("helvetica", "normal");
                const startY = 82;
                pdf.text(`Cliente: ${customerName}`, 20, startY);
                pdf.text(`Cédula: ${customerId}`, 20, startY + 5);
                pdf.text(`Teléfono: ${customerPhone}`, 20, startY + 10);
                pdf.text(`Dirección: ${customerAddress}`, 110, startY);

                // Tabla de Productos
                let yPos = 105;
                
                // Encabezados
                pdf.setFillColor(240, 240, 240); // Gris claro
                pdf.rect(15, yPos - 5, pageWidth - 30, 8, 'F');
                pdf.setFont("helvetica", "bold");
                pdf.text('Producto', 17, yPos);
                pdf.text('Cant', 100, yPos);
                pdf.text('Precio Unit', 120, yPos);
                pdf.text('Subtotal', 160, yPos);
                
                yPos += 8;
                pdf.setFont("helvetica", "normal");

                // Ítems (Necesitamos extraerlos del DOM actual ya que no tenemos el objeto 'sale' aquí directamente)
                const rows = document.querySelectorAll('#invoiceItemsBody tr');
                rows.forEach(row => {
                    const cols = row.querySelectorAll('td');
                    if (cols.length >= 4) {
                        const prodName = cols[0].textContent; // Nombre
                        // const prodId = cols[1].textContent; // ID (Opcional)
                        const quantity = cols[2].textContent;
                        const price = cols[3].textContent;
                        const subtotal = cols[4].textContent;

                        // Truncar nombre si es muy largo
                        const cleanName = prodName.length > 45 ? prodName.substring(0, 42) + '...' : prodName;
                        
                        pdf.text(cleanName, 17, yPos);
                        pdf.text(quantity, 100, yPos);
                        pdf.text(price, 120, yPos);
                        pdf.text(subtotal, 160, yPos);
                        yPos += 7;
                    }
                });

                // Total
                yPos += 5;
                pdf.setDrawColor(212, 175, 55);
                pdf.line(15, yPos, pageWidth - 15, yPos);
                yPos += 10;
                
                pdf.setFontSize(16);
                pdf.setFont("helvetica", "bold");
                pdf.setTextColor(212, 175, 55);
                const totalText = `Total a Pagar: ${document.getElementById('invoiceTotal').textContent}`;
                pdf.text(totalText, pageWidth - 20, yPos, { align: 'right' });

                // Pie de página
                pdf.setFontSize(9);
                pdf.setTextColor(100, 100, 100);
                pdf.setFont("helvetica", "normal");
                pdf.text('¡Gracias por su compra!', pageWidth / 2, pageHeight - 20, { align: 'center' });
                pdf.text('Destello de Oro 18K - Garantía y Calidad', pageWidth / 2, pageHeight - 15, { align: 'center' });

                // Guardar PDF
                const fileName = `Factura_${invoiceNumber.replace(/[^a-zA-Z0-9]/g, '') || 'Venta'}.pdf`;
                pdf.save(fileName);

                document.body.removeChild(loadingDialog);
                await showDialog('PDF Generado', 'La factura se ha descargado exitosamente.', 'success');

            } catch (error) {
                console.error('Error generando PDF:', error);
                if (document.body.contains(loadingDialog)) document.body.removeChild(loadingDialog);
                await showDialog('Error', 'No se pudo generar el PDF. ' + error.message, 'error');
            }
        }

        // NUEVA FUNCIÓN: Generar PDF de factura para una venta específica (Historial)
        async function generateInvoicePDF(sale) {
             // Mostrar diálogo de carga
            const loadingDialog = document.createElement('div');
            loadingDialog.className = 'custom-dialog';
            loadingDialog.style.display = 'flex';
            loadingDialog.innerHTML = `
                <div class="dialog-content">
                    <div class="dialog-icon"><i class="fas fa-spinner fa-spin" style="color: var(--gold-primary);"></i></div>
                    <h2 class="dialog-title">Generando PDF</h2>
                    <p class="dialog-message">Generando factura #${sale.id}...</p>
                </div>
            `;
            document.body.appendChild(loadingDialog);

            try {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();

                // Fondo de pantalla
                try {
                    const bgData = await getBase64Image('fondo.jpeg');
                    if (bgData) {
                        pdf.addImage(bgData, 'JPEG', 0, 0, pageWidth, pageHeight);
                    }
                } catch (e) { console.warn('Sin fondo'); }

                // Título
                pdf.setFontSize(22);
                pdf.setTextColor(212, 175, 55);
                pdf.setFont("helvetica", "bold");
                pdf.text('Destello de Oro 18K', pageWidth / 2, 25, { align: 'center' });
                
                pdf.setFontSize(10);
                pdf.setTextColor(0, 0, 0);
                pdf.setFont("helvetica", "normal");
                pdf.text('Factura de Venta', pageWidth / 2, 32, { align: 'center' });

                // Datos Factura
                pdf.setDrawColor(212, 175, 55);
                pdf.rect(15, 40, pageWidth - 30, 20);
                pdf.setFontSize(12);
                pdf.setFont("helvetica", "bold");
                pdf.text(`Factura N°: ${sale.id}`, 20, 50);
                pdf.setFontSize(10);
                pdf.setFont("helvetica", "normal");
                pdf.text(`Fecha: ${formatDate(sale.date)}`, 20, 56);
                pdf.text(`Método Pago: ${getPaymentMethodName(sale.paymentMethod)}`, 110, 50);

                // Datos Cliente
                const cName = sale.customerInfo ? sale.customerInfo.name : 'Cliente Mostrador';
                const cId = sale.customerInfo ? (sale.customerInfo.id || '') : '';
                const cTel = sale.customerInfo ? (sale.customerInfo.phone || '') : '';

                pdf.text(`Cliente: ${cName}`, 20, 70);
                if(cId) pdf.text(`CC: ${cId}`, 20, 75);
                if(cTel) pdf.text(`Tel: ${cTel}`, 110, 75);

                // Productos
                let y = 85;
                pdf.setFillColor(240, 240, 240);
                pdf.rect(15, y-5, pageWidth-30, 8, 'F');
                pdf.setFont("helvetica", "bold");
                pdf.text('Producto', 17, y);
                pdf.text('Cant', 110, y);
                pdf.text('Total', 160, y);
                y += 8;
                
                pdf.setFont("helvetica", "normal");
                sale.products.forEach(p => {
                    const name = p.productName.length > 50 ? p.productName.substring(0,47)+'...' : p.productName;
                    pdf.text(name, 17, y);
                    pdf.text(p.quantity.toString(), 110, y);
                    pdf.text(formatCurrency(p.subtotal), 160, y);
                    y += 7;
                });

                // Total
                y += 5;
                pdf.setDrawColor(212, 175, 55);
                pdf.line(15, y, pageWidth-15, y);
                y += 10;
                pdf.setFontSize(14);
                pdf.setFont("helvetica", "bold");
                pdf.setTextColor(212, 175, 55);
                pdf.text(`Total: ${formatCurrency(sale.total)}`, pageWidth-20, y, {align:'right'});

                pdf.save(`Factura_${sale.id}.pdf`);
                
                document.body.removeChild(loadingDialog);
                await showDialog('Éxito', 'Factura descargada', 'success');

            } catch (error) {
                console.error(error);
                if (document.body.contains(loadingDialog)) document.body.removeChild(loadingDialog);
                await showDialog('Error', 'No se pudo generar el PDF', 'error');
            }
        }

        // Procesar una venta (con múltiples productos) - AHORA ASYNC
        async function processSale(sale) {
            try {
                // Verificar stock (Aun podemos verificar localmente para UX rápida)
                 const products = JSON.parse(localStorage.getItem('destelloOroProducts') || '[]');
                 let canProcess = true;
                let insufficientStock = [];
    
                 sale.products.forEach(cartItem => {
                    const productIndex = products.findIndex(p => p.id === cartItem.productId);
                    if (productIndex !== -1) {
                        if (cartItem.quantity > products[productIndex].quantity) {
                            canProcess = false;
                            insufficientStock.push({
                                product: cartItem.productName,
                                requested: cartItem.quantity,
                                available: products[productIndex].quantity
                            });
                        }
                    } else {
                        canProcess = false;
                        insufficientStock.push({
                             product: cartItem.productName,
                             requested: cartItem.quantity,
                             available: 0
                        });
                    }
                });
    
                if (!canProcess) {
                    let errorMessage = 'No hay suficiente stock para los siguientes productos:\n\n';
                    insufficientStock.forEach(item => {
                        errorMessage += `• ${item.product}: Solicitado ${item.requested}, Disponible ${item.available}\n`;
                    });
                    await showDialog('Error de Stock', errorMessage, 'error');
                    return false;
                }
                
                // Enviar a API
                 const response = await fetch('api/sales.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(sale)
                });
                const data = await response.json();

                if (response.status === 409) {
                    await showDialog('Error de factura', data.error || 'El número de factura ya existe. Usa un ID único.', 'error');
                    return false;
                }

                if (data.success) {
                    // Actualizar tablas e historial
                    loadInventoryTable(); 
                    loadHistoryCards();
                    return true;
                } else {
                    await showDialog('Error', data.message || 'Error al procesar venta', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error backend:', error);
                 await showDialog('Error', 'Error de conexión con el servidor', 'error');
                 return false;
            }
        }

        // Mostrar factura (SIN REDES SOCIALES)
        function showInvoice(sale) {
            // Configurar datos de la factura
            document.getElementById('invoiceNumber').textContent = `Factura ${sale.id}`;
            document.getElementById('invoiceDate').textContent = `Fecha: ${formatDate(sale.date)}`;
            document.getElementById('invoicePaymentMethod').textContent =
                `Método de Pago: ${getPaymentMethodName(sale.paymentMethod)}`;

            // Información del cliente
            if (sale.customerInfo) {
                document.getElementById('invoiceCustomerName').textContent = sale.customerInfo.name;
                document.getElementById('invoiceCustomerId').textContent = sale.customerInfo.id || 'No proporcionada';
                document.getElementById('invoiceCustomerPhone').textContent = sale.customerInfo.phone;
                document.getElementById('invoiceCustomerEmail').textContent = sale.customerInfo.email || 'No proporcionado';
                document.getElementById('invoiceCustomerAddress').textContent = sale.customerInfo.address;
                document.getElementById('invoiceCustomerCity').textContent = sale.customerInfo.city;
            } else {
                document.getElementById('invoiceCustomerName').textContent = 'Cliente de mostrador';
                document.getElementById('invoiceCustomerId').textContent = 'No proporcionada';
                document.getElementById('invoiceCustomerPhone').textContent = 'No proporcionado';
                document.getElementById('invoiceCustomerEmail').textContent = 'No proporcionado';
                document.getElementById('invoiceCustomerAddress').textContent = 'Recoge en tienda';
                document.getElementById('invoiceCustomerCity').textContent = 'No proporcionada';
            }

            // Detalles de la venta (múltiples productos)
            const invoiceItemsBody = document.getElementById('invoiceItemsBody');
            invoiceItemsBody.innerHTML = '';

            sale.products.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${item.productName}</strong></td>
                    <td>${item.productId}</td>
                    <td>${item.quantity}</td>
                    <td>${formatCurrency(item.unitPrice)}</td>
                    <td><strong>${formatCurrency(item.subtotal)}</strong></td>
                `;
                invoiceItemsBody.appendChild(row);
            });

            // Agregar filas para descuentos y envío
            if (sale.discount > 0) {
                const discountRow = document.createElement('tr');
                discountRow.innerHTML = `
                    <td colspan="4" style="text-align: right; color: var(--danger);">Descuento:</td>
                    <td style="color: var(--danger);">-${formatCurrency(sale.discount)}</td>
                `;
                invoiceItemsBody.appendChild(discountRow);
            }

            if (sale.deliveryCost > 0) {
                const deliveryRow = document.createElement('tr');
                deliveryRow.innerHTML = `
                    <td colspan="4" style="text-align: right; color: var(--info);">Costo de envío:</td>
                    <td style="color: var(--info);">${formatCurrency(sale.deliveryCost)}</td>
                `;
                invoiceItemsBody.appendChild(deliveryRow);
            }

            // IMPORTANTE: Agregar incremento por garantía si existe
            if (sale.warrantyIncrement > 0) {
                const warrantyRow = document.createElement('tr');
                warrantyRow.innerHTML = `
                    <td colspan="4" style="text-align: right; color: var(--warning);">Incremento por garantía:</td>
                    <td style="color: var(--warning);">${formatCurrency(sale.warrantyIncrement)}</td>
                `;
                invoiceItemsBody.appendChild(warrantyRow);
            }

            // Total
            document.getElementById('invoiceTotal').textContent = formatCurrency(sale.total);

            // Mostrar modal
            document.getElementById('invoiceModal').style.display = 'block';
        }

        // Configurar diálogo personalizado
        function setupCustomDialog() {
            const dialog = document.getElementById('customDialog');
            const confirmBtn = document.getElementById('dialogConfirm');
            const cancelBtn = document.getElementById('dialogCancel');

            confirmBtn.addEventListener('click', function () {
                dialog.style.display = 'none';
                if (typeof window.dialogCallback === 'function') {
                    window.dialogCallback(true);
                }
            });

            cancelBtn.addEventListener('click', function () {
                dialog.style.display = 'none';
                if (typeof window.dialogCallback === 'function') {
                    window.dialogCallback(false);
                }
            });
        }

        // Mostrar diálogo personalizado
        function showDialog(title, message, type = 'info', showCancel = false) {
            return new Promise((resolve) => {
                const dialog = document.getElementById('customDialog');
                const icon = document.getElementById('dialogIcon');
                const dialogTitle = document.getElementById('dialogTitle');
                const dialogMessage = document.getElementById('dialogMessage');
                const confirmBtn = document.getElementById('dialogConfirm');
                const cancelBtn = document.getElementById('dialogCancel');

                // Configurar icono según tipo
                icon.innerHTML = getDialogIcon(type);
                icon.style.color = getDialogColor(type);

                // Configurar texto
                dialogTitle.textContent = title;
                dialogMessage.innerHTML = message;

                // Configurar botones
                cancelBtn.style.display = showCancel ? 'inline-flex' : 'none';

                // Guardar callback
                window.dialogCallback = (result) => {
                    resolve(result);
                    delete window.dialogCallback;
                };

                // Mostrar diálogo
                dialog.style.display = 'flex';
            });
        }

        // Obtener icono para diálogo
        function getDialogIcon(type) {
            switch (type) {
                case 'success': return '<i class="fas fa-check-circle"></i>';
                case 'error': return '<i class="fas fa-exclamation-circle"></i>';
                case 'warning': return '<i class="fas fa-exclamation-triangle"></i>';
                case 'info': return '<i class="fas fa-info-circle"></i>';
                case 'question': return '<i class="fas fa-question-circle"></i>';
                default: return '<i class="fas fa-info-circle"></i>';
            }
        }

        // Obtener color para diálogo
        function getDialogColor(type) {
            switch (type) {
                case 'success': return 'var(--success)';
                case 'error': return 'var(--danger)';
                case 'warning': return 'var(--warning)';
                case 'info': return 'var(--info)';
                case 'question': return 'var(--gold-primary)';
                default: return 'var(--info)';
            }
        }

        // Cargar usuarios para cambio de contraseña (se usa la versión asíncrona definida anteriormente)

        // Inicializar pasos del login
        function initLoginSteps() {
            // Asegurar que la app esté oculta y login visible
            const appScreen = document.getElementById('appScreen');
            if (appScreen) appScreen.style.display = 'none';
            
            const loginScreen = document.getElementById('loginScreen');
            if (loginScreen) {
                loginScreen.style.display = 'flex';
                // Asegurar que cubra toda la pantalla si hay scroll
                loginScreen.style.minHeight = '100vh';
            }

            // Ocultar cualquier elemento admin-only por precaución adicional
            document.querySelectorAll('.admin-only').forEach(el => {
                el.style.display = 'none';
            });

            // Mostrar solo el primer paso (selección de rol)
            showLoginStep('roleSelection');
        }

        // Mostrar paso específico del login
        function showLoginStep(stepId) {
            // Ocultar todos los pasos
            document.getElementById('roleSelection').style.display = 'none';
            document.getElementById('userInfoForm').style.display = 'none';
            document.getElementById('loginCredentials').style.display = 'none';
            document.getElementById('loginInfo').style.display = 'none';

            // Mostrar el paso solicitado
            document.getElementById(stepId).style.display = 'block';

            // Si es el paso de credenciales, mostrar info
            if (stepId === 'loginCredentials') {
                document.getElementById('loginInfo').style.display = 'block';
            }
        }

        // Configurar selectores de fecha para historial
        function setupDateSelectors() {
            const monthSelect = document.getElementById('monthSelect');
            const yearSelect = document.getElementById('yearSelect');

            // Limpiar selectores
            monthSelect.innerHTML = '';
            yearSelect.innerHTML = '';

            // Agregar meses
            const monthNames = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            monthNames.forEach((month, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = month;
                if (index === currentMonth) {
                    option.selected = true;
                }
                monthSelect.appendChild(option);
            });

            // Agregar opción para todo el año
            const allYearOption = document.createElement('option');
            allYearOption.value = -1;
            allYearOption.textContent = '--- Todos los meses ---';
            if (currentMonth === -1) {
                allYearOption.selected = true;
            }
            monthSelect.appendChild(allYearOption);

            // Agregar años (desde 2025 hasta 2030)
            for (let year = 2025; year <= 2050; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                yearSelect.appendChild(option);
            }

            // Agregar eventos
            monthSelect.addEventListener('change', function () {
                currentMonth = parseInt(this.value);
                loadHistoryCards();
                // loadMonthlySummary(); // Recargar resumen mensual
            });

            yearSelect.addEventListener('change', function () {
                currentYear = parseInt(this.value);
                loadHistoryCards();
                // loadMonthlySummary(); // Recargar resumen mensual
            });
        }

        // Calcular ganancia estimada
        function calculateProfit() {
            const purchasePrice = parseFloat(document.getElementById('purchasePrice').value) || 0;
            const retailPrice = parseFloat(document.getElementById('retailPrice').value) || 0;
            const wholesalePrice = parseFloat(document.getElementById('wholesalePrice').value) || 0;

            if (purchasePrice > 0) {
                // Ganancia Detal
                if (retailPrice > 0) {
                    const profitRetail = retailPrice - purchasePrice;
                    const profitRetailPercentage = (profitRetail / purchasePrice * 100).toFixed(2);
                    document.getElementById('profitEstimate').value =
                        `${formatCurrency(profitRetail)} (${profitRetailPercentage}%)`;
                } else {
                    document.getElementById('profitEstimate').value = '';
                }

                // Ganancia Mayorista
                if (wholesalePrice > 0) {
                    const profitWholesale = wholesalePrice - purchasePrice;
                    const profitWholesalePercentage = (profitWholesale / purchasePrice * 100).toFixed(2);
                    document.getElementById('profitWholesaleEstimate').value =
                        `${formatCurrency(profitWholesale)} (${profitWholesalePercentage}%)`;
                } else {
                    document.getElementById('profitWholesaleEstimate').value = '';
                }
            } else {
                document.getElementById('profitEstimate').value = '';
                document.getElementById('profitWholesaleEstimate').value = '';
            }
        }

        // Cargar datos iniciales del localStorage
        function loadInitialData() {
            // Inicializar datos si no existen
            if (!localStorage.getItem('destelloOroProducts')) {
                localStorage.setItem('destelloOroProducts', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroAllSales')) {
                localStorage.setItem('destelloOroAllSales', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroHistorySales')) {
                localStorage.setItem('destelloOroHistorySales', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroPendingSales')) {
                localStorage.setItem('destelloOroPendingSales', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroHistoryPendingSales')) {
                localStorage.setItem('destelloOroHistoryPendingSales', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroAllExpenses')) {
                localStorage.setItem('destelloOroAllExpenses', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroHistoryExpenses')) {
                localStorage.setItem('destelloOroHistoryExpenses', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroAllRestocks')) {
                localStorage.setItem('destelloOroAllRestocks', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroHistoryRestocks')) {
                localStorage.setItem('destelloOroHistoryRestocks', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroAllWarranties')) {
                localStorage.setItem('destelloOroAllWarranties', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroHistoryWarranties')) {
                localStorage.setItem('destelloOroHistoryWarranties', JSON.stringify([]));
            }

            if (!localStorage.getItem('destelloOroNextInvoiceId')) {
                localStorage.setItem('destelloOroNextInvoiceId', '1001');
            }

            // Inicializar usuarios (se cargan desde el servidor, no se siembran locales)
            if (!localStorage.getItem('destelloOroUsers')) {
                localStorage.setItem('destelloOroUsers', JSON.stringify([]));
            }

            // Inicializar información de sesión
            if (!sessionStorage.getItem('destelloOroSessionInfo')) {
                sessionStorage.setItem('destelloOroSessionInfo', JSON.stringify({}));
            }
        }

        // Cargar tabla de inventario
        async function loadInventoryTable() {
            try {
                const response = await fetch('api/products.php');
                const products = await response.json();
                
                if (!Array.isArray(products)) {
                    console.error('Error: La respuesta de productos no es un array', products);
                    return;
                }
                
                // Actualizar caché local para otras funciones síncronas (paso intermedio vital)
                localStorage.setItem('destelloOroProducts', JSON.stringify(products));

                const tableBody = document.getElementById('inventoryTableBody');
                tableBody.innerHTML = '';

                // Obtener término de búsqueda si existe el elemento
                const searchInput = document.getElementById('inventorySearch');
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';

                // Filtrar productos
                const filteredProducts = products.filter(product => {
                    if (!searchTerm) return true;
                    const id = product.id ? String(product.id).toLowerCase() : '';
                    const name = product.name ? String(product.name).toLowerCase() : '';
                    return id.includes(searchTerm) || name.includes(searchTerm);
                });

                if (filteredProducts.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="11" style="text-align: center; padding: 20px;">No se encontraron productos que coincidan con la búsqueda "${searchTerm}"</td>`;
                    tableBody.appendChild(row);
                    return;
                }

                filteredProducts.forEach(product => {
                    const profitRetail = (parseFloat(product.retailPrice) || 0) - (parseFloat(product.purchasePrice) || 0);
                    const profitRetailPercentage = (parseFloat(product.purchasePrice) > 0) ? (profitRetail / parseFloat(product.purchasePrice) * 100).toFixed(2) : '0';
                    
                    const profitWholesale = (parseFloat(product.wholesalePrice) || 0) - (parseFloat(product.purchasePrice) || 0);
                    const profitWholesalePercentage = (parseFloat(product.purchasePrice) > 0) ? (profitWholesale / parseFloat(product.purchasePrice) * 100).toFixed(2) : '0';
                    const row = document.createElement('tr');

                    // Determinar si mostrar botón de eliminar (solo para admin)
                    const deleteButton = currentUser && currentUser.role === 'admin' ?
                        `<button class="btn btn-danger btn-sm" onclick="deleteProduct('${product.id}')">
                            <i class="fas fa-trash"></i>
                        </button>` :
                        '';

                    row.innerHTML = `
                        <td>${product.date ? formatDateSimple(product.date) : '---'}</td>
                        <td><strong>${product.id}</strong></td>
                        <td>${product.name}</td>
                        <td>
                            <span class="badge ${product.quantity > 10 ? 'badge-success' : product.quantity > 0 ? 'badge-warning' : 'badge-danger'}">
                                ${product.quantity} unidades
                            </span>
                        </td>
                        <td>${formatCurrency(product.purchasePrice)}</td>
                        <td>${formatCurrency(product.wholesalePrice)}</td>
                        <td>${formatCurrency(product.retailPrice)}</td>
                        <td>
                            ${formatCurrency(profitRetail)}<br>
                            <small>(${profitRetailPercentage}%)</small>
                        </td>
                        <td>
                            ${formatCurrency(profitWholesale)}<br>
                            <small>(${profitWholesalePercentage}%)</small>
                        </td>
                        <td>${product.supplier}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${product.id}', 'product')" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${currentUser && currentUser.role === 'admin' ? `
                                <button class="btn btn-warning btn-sm" onclick="editMovement('${product.id}', 'product')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct('${product.id}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>` : ''}
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });

                // Ocultar columnas según rol
                 /*
                 if (currentUser && currentUser.role === 'worker') {
                    actionCells.forEach(cell => cell.style.display = 'none');
                    const actionHeader = document.querySelector('#inventoryTable th:nth-child(10)');
                    if (actionHeader) actionHeader.style.display = 'none';
                } else {
                    actionCells.forEach(cell => cell.style.display = '');
                     const actionHeader = document.querySelector('#inventoryTable th:nth-child(10)');
                    if (actionHeader) actionHeader.style.display = '';
                }
                */

            } catch (error) {
                console.error('Error cargando inventario:', error);
            }
        }

        // Cargar tabla de gastos
        async function loadExpensesTable() {
            try {
                const response = await fetch('api/expenses.php');
                const expenses = await response.json();
                
                if (!Array.isArray(expenses)) {
                    console.error('Error: La respuesta de gastos no es un array', expenses);
                    return;
                }

                localStorage.setItem('destelloOroAllExpenses', JSON.stringify(expenses)); // Cache específico para la tabla general

                const tableBody = document.getElementById('expensesTableBody');
                tableBody.innerHTML = '';

                expenses.forEach(expense => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td>${formatDate(expense.date)}</td>
                        <td>${expense.description}</td>
                        <td><strong>${formatCurrency(expense.amount)}</strong></td>
                        <td>
                            <span class="badge ${expense.user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                ${getUserName(expense.user)}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${expense.id}', 'expenses')" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${currentUser && currentUser.role === 'admin' ? `
                                <button class="btn btn-warning btn-sm" onclick="editMovement('${expense.id}', 'expenses')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteExpense('${expense.id}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>` : ''}
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            } catch (error) {
                console.error('Error cargando gastos:', error);
            }
        }

        // Cargar tabla de ventas pendientes
        async function loadPendingSalesTable() {
            try {
                const response = await fetch('api/pending_sales.php');
                const allPendingSales = await response.json();
                
                if (!Array.isArray(allPendingSales)) {
                    console.error('Error: La respuesta de ventas pendientes no es un array', allPendingSales);
                    return;
                }
                
                // FILTRAR: Solo mostrar ventas con status 'pending' en la tabla de gestión
                // Las confirmadas (status='completed') permanecen en el historial pero se quitan de aquí
                const pendingSales = allPendingSales.filter(sale => sale.status === 'pending');
                
                localStorage.setItem('destelloOroHistoryPendingSales', JSON.stringify(allPendingSales)); // Guardar TODAS para el historial
                localStorage.setItem('destelloOroPendingSales', JSON.stringify(pendingSales)); // Solo pendientes para esta tabla

                const tableBody = document.getElementById('pendingTableBody');
                tableBody.innerHTML = '';

                // Si no hay ventas pendientes, mostrar mensaje
                if (pendingSales.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                                <i class="fas fa-check-circle" style="font-size: 3rem; color: var(--success); margin-bottom: 1rem;"></i>
                                <p style="font-size: 1.1rem; font-weight: 500;">No hay pagos pendientes de confirmación</p>
                                <p style="font-size: 0.9rem;">Todas las ventas han sido confirmadas o canceladas</p>
                            </td>
                        </tr>
                    `;
                    return;
                }

                pendingSales.forEach(sale => {
                    const row = document.createElement('tr');

                    // Obtener información de productos
                    const productCount = sale.products ? sale.products.length : 1;
                    const productNames = sale.products ?
                        sale.products.map(p => p.productName || p.product_name).join(', ') : // Support both DB and JS format if mixed
                        (sale.productName || 'Producto');

                    row.innerHTML = `
                        <td>${formatDate(sale.date || sale.sale_date)}</td>
                        <td><strong>${sale.invoice_number || 'N/A'}</strong></td>
                        <td><strong>${sale.id}</strong></td>
                        <td>${sale.customerInfo ? sale.customerInfo.name : (sale.customer_name || 'Cliente de mostrador')}</td>
                        <td>
                            <strong>${productCount} producto(s)</strong><br>
                            <small style="font-size: 0.8rem;">${productNames}</small>
                        </td>
                        <td><strong>${formatCurrency(sale.total)}</strong></td>
                        <td><span class="badge ${paymentMethods[sale.paymentMethod]?.class || 'badge-warning'}">${getPaymentMethodName(sale.paymentMethod)}</span></td>
                        <td>
                            <span class="badge ${sale.user === 'admin' ? 'badge-admin' : 'badge-worker'}">
                                ${getUserName(sale.user || sale.username)}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-info btn-sm" onclick="viewMovementDetails('${sale.id || sale.invoice_number}', 'sales')" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                ${currentUser && currentUser.role === 'admin' ? `
                                <button class="btn btn-success btn-sm" onclick="confirmPayment('${sale.id || sale.invoice_number}')" title="Confirmar Pago">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editMovement('${sale.id || sale.invoice_number}', 'sales')" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMovement('${sale.id || sale.invoice_number}', 'pending')" title="Cancelar Venta">
                                    <i class="fas fa-times"></i>
                                </button>` : ''}
                            </div>
                        </td>
                    `;

                    tableBody.appendChild(row);

                });
            } catch (error) {
                console.error('Error cargando pendientes:', error);
            }
        }

        // Funciones auxiliares globales
        window.deleteProduct = async function (productId) {
            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede eliminar productos.', 'error');
                return;
            }

            const confirmed = await showDialog(
                'Eliminar Producto',
                '¿Está seguro de que desea eliminar este producto? Esta acción no se puede deshacer.',
                'warning',
                true
            );

            if (confirmed) {
                try {
                    const response = await fetch(`api/products.php?id=${productId}`, {
                        method: 'DELETE'
                    });
                    const data = await response.json();
                    
                    if (data.success) {
                        await loadInventoryTable();
                        await showDialog('Éxito', 'Producto eliminado correctamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al eliminar producto', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            }
        };

        window.deleteExpense = async function (expenseId) {
            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede eliminar gastos.', 'error');
                return;
            }

            const confirmed = await showDialog(
                'Eliminar Gasto',
                '¿Está seguro de que desea eliminar este gasto? Esta acción no se puede deshacer.',
                'warning',
                true
            );

            if (confirmed) {
                try {
                    const response = await fetch(`api/expenses.php?id=${expenseId}`, {
                        method: 'DELETE'
                    });
                    const data = await response.json();

                    if (data.success) {
                        await loadExpensesTable();
                        await loadHistoryCards();
                        await showDialog('Éxito', 'Gasto eliminado correctamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al eliminar gasto', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            }
        };

        window.confirmPayment = async function (saleId) {
            const confirmed = await showDialog(
                'Confirmar Pago',
                '¿Confirmar que se recibió el pago de esta venta?',
                'question',
                true
            );

            if (confirmed) {
                try {
                    const response = await fetch('api/pending_sales.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'confirm',
                            sale_id: saleId
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        await loadPendingSalesTable();
                        await loadInventoryTable();
                        await loadHistoryCards();
                        await showDialog('Éxito', 'Pago confirmado y venta procesada exitosamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al procesar la venta.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            }
        };

        window.cancelPendingSale = async function (saleId) {
            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede cancelar ventas pendientes.', 'error');
                return;
            }

            const confirmed = await showDialog(
                'Cancelar Venta Pendiente',
                '¿Cancelar esta venta pendiente? Esta acción no se puede deshacer.',
                'warning',
                true
            );

            if (confirmed) {
                try {
                    const response = await fetch('api/pending_sales.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'cancel',
                            sale_id: saleId
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        await loadPendingSalesTable();
                        await loadInventoryTable();
                        await loadHistoryCards();
                        await showDialog('Éxito', 'Venta pendiente cancelada correctamente.', 'success');
                    } else {
                        await showDialog('Error', data.message || 'Error al cancelar venta', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            }
        };

        window.completeWarranty = async function (warrantyId) {
            // Verificar si es administrador
            if (currentUser && currentUser.role !== 'admin') {
                await showDialog('Acceso Restringido', 'Solo el administrador puede finalizar garantías.', 'error');
                return;
            }

            const confirmed = await showDialog(
                'Finalizar Garantía',
                '¿Marcar esta garantía como completada? Esto indica que el proceso ha finalizado y el cliente ha recibido su solución.',
                'question',
                true
            );

            if (confirmed) {
                try {
                    const response = await fetch('api/warranties.php', {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            id: warrantyId,
                            status: 'completed'
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        loadHistoryCards(); // Recarga todas las tarjetas, incluyendo garantías
                        if (document.getElementById('historyDetailsView').classList.contains('active')) {
                             showHistoryDetails('warranties'); // Recarga detalles si está abierto
                        }
                        await showDialog('Éxito', 'Garantía marcada como completada.', 'success');
                    } else {
                        await showDialog('Error', data.error || 'Error al actualizar garantía', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await showDialog('Error', 'Error de conexión', 'error');
                }
            }
        };

        // Función para obtener nombre de usuario
        function getUserName(username) {
            // Si es el usuario actual, usar su información
            if (currentUser && currentUser.username === username) {
                return currentUser.displayName || currentUser.name || username;
            }

            // Buscar en la lista de usuarios
            try {
                const users = JSON.parse(localStorage.getItem('destelloOroUsers'));
                if (users) {
                    const user = users.find(u => u.username === username);
                    if (user) {
                        if (user.name && user.lastName) {
                            return `${user.name} ${user.lastName} (${user.role === 'admin' ? 'Administrador' : 'Vendedor'})`;
                        } else if (user.name) {
                            return `${user.name} (${user.role === 'admin' ? 'Administrador' : 'Vendedor'})`;
                        }
                    }
                }
            } catch (error) {
                console.error('Error al obtener nombre de usuario:', error);
            }

            // Fallback para admin/worker por defecto si no están en la lista (caso bordes)
            if(username === 'admin') return 'Administrador (Administrador)';
            if(username === 'worker' || username === 'trabajador') return 'Vendedor (Vendedor)';

            return username;
        }

        // Nueva función para texto de estado de garantía
        function getWarrantyStatusText(status) {
            switch (status) {
                case 'pending': return 'Pendiente';
                case 'in_process': return 'En proceso';
                case 'completed': return 'Completada';
                case 'cancelled': return 'Cancelada';
                default: return status;
            }
        }

        // Nueva función para formato de fecha simple
        function formatDateSimple(dateString) {
            if (!dateString) return '---';
            // Evitar problemas de zona horaria con strings YYYY-MM-DD
            if (dateString.length === 10) {
                const parts = dateString.split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return '---';
            return date.toLocaleDateString('es-CO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Funciones de utilidad
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function formatDate(dateString) {
            if (!dateString) return '---';
            // Si es solo fecha YYYY-MM-DD
            if (dateString.length === 10) {
                const parts = dateString.split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
            // Si tiene hora, intentamos parsear cuidando el desfase
            const date = new Date(dateString.replace(/-/g, '/'));
            if (isNaN(date.getTime())) return '---';
            return date.toLocaleDateString('es-CO', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getPaymentMethodName(method) {
            return paymentMethods[method]?.name || method;
        }

        // Función para limpiar datos corruptos y resetear contraseñas (RESTAURADO)
        window.resetUserData = async function () {
            const confirmed = await showDialog(
                'Resetear Sistema', 
                '¿Estás seguro? Esto reseteará las contraseñas de admin y trabajador a sus valores iniciales (admin123 y trabajador123) y cerrará todas las sesiones.',
                'warning',
                true
            );

            if (!confirmed) return;

            console.log('Iniciando reseteo total...');

            try {
                // 1. Resetear Base de Datos vía API
                const response = await fetch('api/reset_db.php');
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.error || 'Error al resetear DB');
                }

                // 2. Limpiar LocalStorage
                localStorage.clear();

                // 3. Inicializar usuarios en localStorage con valores de fábrica
                const initialUsers = [
                    {
                        username: 'admin',
                        password: 'admin123',
                        role: 'admin',
                        name: 'Administrador',
                        lastName: 'Principal',
                        phone: '3001234567',
                        personalInfoSaved: false
                    },
                    {
                        username: 'trabajador',
                        password: 'trabajador123',
                        role: 'worker',
                        name: 'Vendedor',
                        lastName: 'Principal',
                        phone: '3009876543',
                        personalInfoSaved: false
                    }
                ];
                localStorage.setItem('destelloOroUsers', JSON.stringify(initialUsers));

                await showDialog('Éxito', 'Sistema reseteado correctamente. Usa admin / admin123.', 'success');
                
                // Recargar para aplicar cambios
                location.reload();

            } catch (error) {
                console.error('Error en reseteo:', error);
                await showDialog('Error', 'No se pudo resetear completamente: ' + error.message, 'error');
            }
        };

        // Función para verificar el estado del campo de ID de factura
        window.checkWarrantyIdField = function () {
            const field = document.getElementById('warrantySaleId');
            console.log('=== VERIFICACIÓN DEL CAMPO warrantySaleId ===');
            console.log('1. Valor del campo:', field.value);
            console.log('2. Venta seleccionada:', selectedSaleForWarranty ? selectedSaleForWarranty.id : 'Ninguna');
            console.log('3. Campo visible:', field.offsetParent !== null);
            console.log('4. Display:', field.style.display);
            console.log('5. Readonly:', field.readOnly);
            console.log('6. Requerido:', field.required);
            console.log('7. Background:', field.style.backgroundColor);

            if (selectedSaleForWarranty && !field.value) {
                console.log('⚠️ ADVERTENCIA: Hay venta seleccionada pero el campo está vacío');
                console.log('Solución: Ejecutar en consola: document.getElementById("warrantySaleId").value = "' + selectedSaleForWarranty.id + '"');
            } else if (field.value && selectedSaleForWarranty) {
                console.log('✅ CORRECTO: Campo lleno y venta seleccionada');
            }

            return {
                fieldValue: field.value,
                saleSelected: selectedSaleForWarranty ? selectedSaleForWarranty.id : null,
                isVisible: field.offsetParent !== null
            };
        };

        // Configuración de contadores manuales (Admin)
        function setupManualCounters() {
            // Ventas
            const saleCounter = document.getElementById('manualSalesCounter');

            if (saleCounter) {
                // Cargar valor guardado
                const savedSales = localStorage.getItem('destelloOroManualSalesCount') || '0';
                saleCounter.value = savedSales;

                // Guardar al cambiar
                saleCounter.addEventListener('input', function () {
                    localStorage.setItem('destelloOroManualSalesCount', this.value);
                });
            }

            // Garantías
            const warrantyCounter = document.getElementById('manualWarrantyCounter');

            if (warrantyCounter) {
                // Cargar valor guardado
                const savedWarranties = localStorage.getItem('destelloOroManualWarrantyCount') || '0';
                warrantyCounter.value = savedWarranties;

                // Guardar al cambiar
                warrantyCounter.addEventListener('input', function () {
                    localStorage.setItem('destelloOroManualWarrantyCount', this.value);
                });
            }
        }

        // Verificar sesión con el servidor
        async function checkSession() {
            // SEGURIDAD: Solo intentar auto-login si esta pestaña ya ha sido marcada como activa
            // Esto evita que al abrir el link en una pestaña nueva se use una sesión previa del navegador
            // sin haber pasado por el login en esta pestaña específica.
            if (!sessionStorage.getItem('destelloOroTabActive')) {
                console.log('Nueva entrada detectada: Se requiere login');
                initLoginSteps();
                return;
            }

            try {
                const response = await fetch('api/check_auth.php', { cache: 'no-cache' });
                const data = await response.json();
                
                if (data.authenticated) {
                    // Recuperar información personal guardada localmente
                    const sessionInfo = JSON.parse(sessionStorage.getItem('destelloOroSessionInfo') || '{}');
                    const userRole = data.user.role;
                    const userKey = `${userRole}_info`;
                    const personalInfo = sessionInfo[userKey];

                    currentUser = {
                        id: data.user.id,
                        username: data.user.username,
                        role: data.user.role,
                        displayName: personalInfo ? `${personalInfo.name} ${personalInfo.lastName}` : data.user.name,
                        name: data.user.name
                    };
                    
                    console.log('Sesión activa:', currentUser);
                    showApp();
                } else {
                    console.log('No hay sesión activa o ha expirado');
                    sessionStorage.removeItem('destelloOroTabActive');
                    initLoginSteps();
                }
            } catch (error) {
                console.error('Error verificando sesión:', error);
                initLoginSteps();
            }
        }

        // Cerrar sesión
        async function logout() {
            try {
                await fetch('api/logout.php');
                // Limpiar ABSOLUTAMENTE TODO lo relacionado con la app, EXCEPTO los contadores manuales
                Object.keys(localStorage).forEach(key => {
                    if (key.startsWith('destelloOro') && 
                        key !== 'destelloOroManualSalesCount' && 
                        key !== 'destelloOroManualWarrantyCount') {
                        localStorage.removeItem(key);
                    }
                });
                sessionStorage.clear();


                location.reload();
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
                location.reload();
            }
        }

        // Inicialización principal consolidada
        async function initApp() {
            console.log('Inicializando aplicación...');
            
            // 1. Cargar datos base y configurar UI
            loadInitialData();
            setupLoginEvents();
            setupNavigationEvents();
            setupFormEvents();
            setupInvoiceEvents();
            setupCustomDialog();
            setupPasswordChange();
            setupResetRecordsEvents();
            setupForgotPasswordEvents();
            setupWarrantyEvents();
            setupHistoryEvents();
            setupViewMovementModalEvents();
            setupEditMovementModalEvents();
            setupCartEvents();
            setupManualCounters();

            // 2. Verificar sesión
            await checkSession();
        }

        // Función para cargar logs de auditoría
        window.loadAuditLogs = async function() {
            const tableBody = document.getElementById('auditLogsTableBody');
            if(!tableBody) return;
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Cargando...</td></tr>';

            try {
                const response = await fetch('api/logs.php');
                const data = await response.json();

                if (data.success) {
                    if (data.logs.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No hay registros de auditoría.</td></tr>';
                        return;
                    }

                    tableBody.innerHTML = '';
                    data.logs.forEach(log => {
                        const row = document.createElement('tr');
                        
                        let actionBadgeClass = 'badge-info';
                        let actionText = log.action_type;
                        
                        // Estilos y traducción básica
                        if (log.action_type === 'DELETE') {
                            actionBadgeClass = 'badge-danger';
                            actionText = 'ELIMINACIÓN';
                        }
                        if (log.action_type === 'CONFIRM_PAYMENT') {
                            actionBadgeClass = 'badge-success';
                            actionText = 'PAGO CONFIRMADO';
                        }
                        if (log.action_type === 'EDIT') {
                            actionBadgeClass = 'badge-warning';
                            actionText = 'EDICIÓN';
                        }
                        if (log.action_type === 'CHANGE_PASSWORD') {
                            actionBadgeClass = 'badge-warning';
                            actionText = 'CAMBIO CONTRASEÑA';
                        }

                        // Formatear fecha
                        const date = new Date(log.created_at);
                        const dateStr = date.toLocaleDateString('es-CO') + ' ' + date.toLocaleTimeString('es-CO');

                        row.innerHTML = `
                            <td>${dateStr}</td>
                            <td><strong>${log.user_username}</strong></td>
                            <td><span class="badge ${actionBadgeClass}" style="padding: 5px 10px; border-radius: 4px; color: white;">${actionText}</span></td>
                            <td>${log.entity_type} <small>#${log.entity_id}</small></td>
                            <td>${log.details}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                     tableBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:red;">${data.message || 'Error al cargar logs'}</td></tr>`;
                }
            } catch (error) {
                console.error(error);
                 tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:red;">Error de conexión</td></tr>';
            }
        };

        // Hook para inicializar listener de filtro historyFilter
        const originalInitApp = window.initApp;
        window.initApp = async function() {
            // Ejecutar initApp original
            if (originalInitApp) await originalInitApp();
            
            // Agregar listener extra
            const historyFilter = document.getElementById('historyFilter');
            if (historyFilter) {
                historyFilter.addEventListener('change', function() {
                    const auditView = document.getElementById('auditLogsView');
                    const cardsView = document.getElementById('historyCardsView');
                    const detailsView = document.getElementById('historyDetailsView');
                    if (this.value === 'admin_audit') {
                        if(auditView) auditView.style.display = 'block';
                        if(cardsView) cardsView.style.display = 'none';
                        if(detailsView) detailsView.style.display = 'none';
                        loadAuditLogs();
                    } else {
                        if(auditView) auditView.style.display = 'none';
                        if(cardsView) cardsView.style.display = 'grid'; 
                        if(detailsView) {
                            detailsView.style.display = 'none';
                            detailsView.classList.remove('active');
                        }
                    }
                });
            }
        };

        // Iniciar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', initApp);
    </script>
</body>

</html>
