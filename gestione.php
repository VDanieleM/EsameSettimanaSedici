<?php
session_start();

require_once 'db.php';
require_once 'UserDTO.php';

$dbConfig = include_once 'config.php';
$db = \db\DB_PDO::getInstance($dbConfig);
$conn = $db->getConnection();

function checkAuthentication()
{
    if (!isset($_SESSION['user_id'])) {
        echo "<script type='text/javascript'>
                alert('FAI LOGIN');
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 1000);
              </script>";
        exit();
    }
}

function getCurrentUser($conn, $userId)
{
    $query = "SELECT * FROM utenti WHERE id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return new UserDTO($user['id'], $user['nome'], $user['email'], $user['password']);
}

function getUsers($conn)
{
    $query = "SELECT * FROM utenti";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userDTOs = [];
    foreach ($users as $user) {
        $userDTOs[] = new UserDTO($user['id'], $user['nome'], $user['email'], $user['password']);
    }
    return $userDTOs;
}

function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function updateUser($conn, $userId, UserDTO $user)
{
    $query = "UPDATE utenti SET nome = :nome, email = :email WHERE id = :userId";
    $stmt = $conn->prepare($query);
    $nome = $user->getNome();
    $email = $user->getEmail();
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
}

function deleteUser($conn, $userId)
{
    $query = "DELETE FROM utenti WHERE id = :userId";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
}

// Funzione per eseguire l'autenticazione dell'utente
function authenticateUser(UserDTO $user, $conn)
{
    // Verifica se l'utente esiste
    $existingUserQuery = "SELECT * FROM utenti WHERE nome = :nome";
    $existingUserStmt = $conn->prepare($existingUserQuery);
    $nome = $user->getNome();
    $existingUserStmt->bindParam(':nome', $nome);
    $existingUserStmt->execute();
    $existingUser = $existingUserStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser && password_verify($user->getPassword(), $existingUser['password'])) {
        $_SESSION['user_id'] = $existingUser['id'];
        $_SESSION['loginMessage'] = ['status' => 'success', 'message' => "Login avvenuto con successo!"];
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['loginMessage'] = ['status' => 'danger', 'message' => "Errore durante il login. Riprova."];
    }
}

// Funzione per eseguire la registrazione di un nuovo utente
function registerUser(UserDTO $user, $conn)
{
    // Verifica se l'utente esiste già
    $nome = $user->getNome();
    $email = $user->getEmail();
    $existingUserQuery = "SELECT COUNT(*) FROM utenti WHERE nome = :nome OR email = :email";
    $existingUserStmt = $conn->prepare($existingUserQuery);
    $existingUserStmt->bindParam(':nome', $nome);
    $existingUserStmt->bindParam(':email', $email);
    $existingUserStmt->execute();
    $userExists = $existingUserStmt->fetchColumn();

    if ($userExists) {
        return ['status' => 'danger', 'message' => "Errore: L'utente con lo stesso nome o email esiste già."];
    }

    // Inserimento dell'utente
    $hashedPassword = hashPassword($user->getPassword());
    $insertUserQuery = "INSERT INTO utenti (nome, password, email) VALUES (:nome, :password, :email)";
    $insertUserStmt = $conn->prepare($insertUserQuery);
    $insertUserStmt->bindParam(':nome', $nome);
    $insertUserStmt->bindParam(':email', $email);
    $insertUserStmt->bindParam(':password', $hashedPassword);

    if ($insertUserStmt->execute()) {
        return ['status' => 'success', 'message' => "Registrazione completata con successo. Puoi effettuare il login."];
    } else {
        return ['status' => 'danger', 'message' => "Errore durante la registrazione. Riprova."];
    }
}

// Gestione delle richieste POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['login'])) {
        $nome = $_POST['nome'];
        $password = $_POST['password'];
        $user = new UserDTO($nome, $password, '');

        authenticateUser($user, $conn);
    }

    if (isset($_POST['register'])) {
        $password = $_POST['password'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $user = new UserDTO($password, $nome, $email);

        $_SESSION['registrationMessage'] = registerUser($user, $conn);
    }

    if (isset($_POST['update'])) {
        $userId = $_POST['user_id'];
        $password = $_POST['password'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $user = new UserDTO($userId, $password, $nome, $email);
        updateUser($conn, $userId, $user);
        header('Location: dashboard.php');
        exit();
    }

    if (isset($_POST['delete'])) {
        $userId = $_POST['delete_id'];

        deleteUser($conn, $userId);
        header('Location: dashboard.php');
        exit();
    }

    if (isset($_POST['create'])) {
        $password = $_POST['password'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];

        $user = new UserDTO($password, $nome, $email);

        registerUser($user, $conn);
        header('Location: dashboard.php');
        exit();
    }
}