<?php
// reset_password.php
require_once 'config/db.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (empty($token)) {
    $error = 'Token no proporcionado.';
} else {
    // Verificar si el token es válido y no ha expirado
    try {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE reset_token = :token AND reset_token_expiry > NOW() LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'El enlace de recuperación es inválido o ha expirado.';
        }
    } catch (PDOException $e) {
        $error = 'Error en el servidor: ' . $e->getMessage();
    }
}

// Manejar el cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            // Actualizar contraseña y limpiar token
            $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
            $stmt->bindParam(':password', $new_password);
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();
            
            // Invalida cualquier sesión activa para forzar login
            session_start();
            session_destroy();

            $success = 'Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.';
        } catch (PDOException $e) {
            $error = 'Error al actualizar la contraseña: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Destello de Oro 18K</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold-primary: #D4AF37;
            --gold-dark: #B8860B;
            --white: #FFFFFF;
            --bg-dark: #1a1a1a;
            --danger: #DC143C;
            --success: #2E8B57;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('fondo.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }

        .container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            border: 2px solid var(--gold-primary);
            text-align: center;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: var(--gold-dark);
            margin-bottom: 1rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--gold-primary);
            outline: none;
        }

        .btn {
            background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-dark) 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--gold-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-key"></i> Nueva Contraseña</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php if ($error !== 'Token no proporcionado.' && $error !== 'El enlace de recuperación es inválido o ha expirado.'): ?>
                <a href="index.php" class="back-link">Volver al inicio</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
            <a href="index.php" class="btn">Ir al Login</a>
        <?php elseif (!$error): ?>
            <p style="color: #666; font-size: 0.85rem; margin-bottom: 2rem;">
                Hola <strong><?php echo htmlspecialchars($user['username']); ?></strong>, ingresa tu nueva contraseña a continuación.
            </p>

            <form method="POST">
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn">Actualizar Contraseña</button>
            </form>
        <?php endif; ?>

        <?php if ($error === 'Token no proporcionado.' || $error === 'El enlace de recuperación es inválido o ha expirado.'): ?>
            <a href="index.php" class="back-link">Ir al Inicio</a>
        <?php endif; ?>
    </div>
</body>
</html>
