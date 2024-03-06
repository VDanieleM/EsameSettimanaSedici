<?php
$config = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'phpesercizi',
    'port' => '3306',
    'user' => 'root',
    'password' => ''
];

try {
    $conn = new PDO(
        $config['driver'] . ":host=" . $config['host'] . "; port=" . $config['port'] . "; dbname=" . $config['database'] . ";",
        $config['user'],
        $config['password']
    );

    $query = "
        CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    ";

    $conn->exec($query);

} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}

return $config;
?>