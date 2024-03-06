<?php
require_once 'gestione.php';

checkAuthentication();

$users = getUsers($conn);
$current_user = getCurrentUser($conn, $_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2>Dashboard</h2>
        <p>Benvenuto,
            <?php echo $current_user->getNome(); ?>!
        </p>

        <!-- Bottone Crea utente -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            Crea utente
        </button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOME</th>
                    <th>EMAIL</th>
                    <th>AZIONI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php echo $user->getId(); ?>
                        </td>
                        <td>
                            <?php echo $user->getNome(); ?>
                        </td>
                        <td>
                            <?php echo $user->getEmail(); ?>
                        </td>
                        <td>
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal<?php echo $user->getId(); ?>">Modifica</button>
                        </td>
                        <td>
                            <form method="post" action="gestione.php">
                                <input type="hidden" name="delete_id" value="<?php echo $user->getId(); ?>">
                                <button type="submit" class="btn btn-danger" name="delete">Elimina</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Modale per la modifica dell'utente -->
    <?php foreach ($users as $user): ?>
        <div class="modal fade" id="editModal<?php echo $user->getId(); ?>" tabindex="-1" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifica Utente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="gestione.php">
                            <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>">
                            <div class="mb-3">
                                <label for="nome<?php echo $user->getId(); ?>" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome<?php echo $user->getId(); ?>" name="nome"
                                    value="<?php echo $user->getNome(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email<?php echo $user->getId(); ?>" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email<?php echo $user->getId(); ?>"
                                    name="email" value="<?php echo $user->getEmail(); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update">Aggiorna</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modale Nuovo User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Crea Utente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="gestione.php">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="create">Crea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>