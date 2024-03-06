<?php
require_once 'gestione.php';
$registrationSuccess = $_SESSION['registrationSuccess'] ?? false;
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrazione Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-header">
                <h5 class="card-title text-center">Registrazione Utente</h5>
            </div>
            <div class="card-body">

                <!-- Messaggio di errore o di approvazione -->
                <?php if (isset($_SESSION['registrationMessage'])): ?>
                    <div class="alert alert-<?= $_SESSION['registrationMessage']['status']; ?> mt-3">
                        <?= $_SESSION['registrationMessage']['message']; ?>
                        <?php
                        unset($_SESSION['registrationMessage']);
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Form di registrazione -->
                <form method="post" action="registration.php">
                    <div class="form-group">
                        <label for="nome">Username:</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="register">Registrati</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>