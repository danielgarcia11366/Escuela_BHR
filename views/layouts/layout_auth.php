<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <link rel="shortcut icon" href="<?= asset('images/LIMPIO.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Login - Escuela BHR</title>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #404040 100%);
            font-family: 'Arial', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            max-width: 120px;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
        }

        .auth-logo h2 {
            color: #2d3748;
            margin-top: 1rem;
            font-weight: 700;
        }

        .form-label {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ed8936;
            box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(237, 137, 54, 0.4);
        }

        .auth-link {
            color: #ed8936;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            color: #dd6b20;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <?php echo $contenido; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>