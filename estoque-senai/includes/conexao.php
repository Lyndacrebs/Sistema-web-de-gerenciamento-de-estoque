<?php
// includes/conexao.php

$host = 'localhost';
$dbname = 'saep_db'; // Nome do banco que você criou
$username = 'root';   // Usuário padrão do XAMPP
$password = '';       // Senha vazia por padrão no XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>