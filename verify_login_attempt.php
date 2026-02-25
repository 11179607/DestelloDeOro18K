<?php
require_once 'config/db.php';

$token = $_GET['token'] ?? '';
$action = $_GET['action'] ?? '';

if (!$token || !$action) {
    die("Petición inválida.");
}

try {
    $stmt = $conn->prepare("SELECT id, username, name FROM users WHERE security_token = :token LIMIT 1");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if (!$user) {
        $message = "El enlace de verificación ha expirado o es inválido.";
        $type = "error";
    } else {
        if ($action === 'yes') {
            // Fui yo
            $upd = $conn->prepare("UPDATE users SET failed_attempts = 0, security_token = NULL, locked_until = NULL WHERE id = :id");
            $upd->execute(['id' => $user['id']]);
            $message = "Gracias por confirmar. Para tu seguridad, si no recuerdas tu contraseña, te sugerimos cambiarla.";
            $type = "success";
            $show_reset_button = true;
        } elseif ($action === 'no') {
            // No fui yo -> Bloquear por 5 minutos
            $upd = $conn->prepare("UPDATE users SET security_token = NULL, locked_until = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id = :id");
            $upd->execute(['id' => $user['id']]);
            $message = "Hemos bloqueado temporalmente el acceso a tu cuenta por 5 minutos por seguridad. Te recomendamos cambiar tu clave.";
            $type = "danger";
            $show_reset_button = true;
        } else {
            $message = "Acción desconocida.";
            $type = "error";
        }
    }
} catch (PDOException $e) {
    $message = "Error en el servidor: " . $e->getMessage();
    $type = "error";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Seguridad - Destello de Oro 18K</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gold-primary: #D4AF37;
            --gold-dark: #B8860B;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #1a1a1a;
            display: flex; /* Remove background image, keep it clean and fast for verify */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background: white;
            padding: 3rem 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 450px;
            text-align: center;
            border-top: 5px solid var(--gold-primary);
        }
        h2 {
            color: var(--gold-dark);
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .alert.error, .alert.danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 15px;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .icon.error, .icon.danger {
            color: #dc3545;
        }
        .icon.success {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($type === 'success'): ?>
            <i class="fas fa-check-circle icon success"></i>
        <?php else: ?>
            <i class="fas fa-shield-alt icon <?php echo $type; ?>"></i>
        <?php endif; ?>
        
        <h2>Verificación de Seguridad</h2>
        
        <div class="alert <?php echo $type; ?>">
            <?php echo $message; ?>
        </div>

        <?php if (isset($show_reset_button) && $show_reset_button): ?>
            <p style="font-size:0.9rem; color:#666;">Si necesitas cambiar tu contraseña, puedes hacerlo ahora:</p>
            <a href="index.php?forgot_password=true" class="btn" style="background: #333;"><i class="fas fa-key"></i> Cambiar Contraseña</a>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="index.php" class="btn"><i class="fas fa-home"></i> Ir al Inicio</a>
        </div>
    </div>
</body>
</html>
