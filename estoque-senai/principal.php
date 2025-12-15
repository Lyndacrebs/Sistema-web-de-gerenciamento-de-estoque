<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('includes/conexao.php');
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal - Sistema de Gestão de Estoque</title>
    <style>
        body {
            background-color: #f5f7fa;
            display: flex;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(to bottom, #1a2a6c, #2a5298);
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }

        .sidebar-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .nav-link {
            display: block;
            padding: 14px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s, color 0.3s;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
        }

        .logout-btn {
            margin-top: auto;
            padding: 12px 20px;
            background-color: #d32f2f;
            color: white;
            border: none;
            width: calc(100% - 40px);
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            text-align: left;
            margin-left: 20px;
        }

        .logout-btn:hover {
            background-color: #b71c1c;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2a5298;
            font-size: 28px;
        }

        .user-info {
            background: #e3f2fd;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            color: #1a2a6c;
        }

        .welcome-message {
            font-size: 18px;
            color: #444;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Estoque</h3>
        </div>
        <a href="cadastroProduto.php" class="nav-link">Cadastro de Produto</a>
        <a href="gestaoEstoque.php" class="nav-link">Gestão de Estoque</a>
        <a href="logout.php" class="nav-link">Sair</a>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Painel Principal</h1>
            <div class="user-info">Usuário: <?php echo htmlspecialchars($user_name); ?></div>
        </div>

        <div class="welcome-message">
            <p>Bem-vindo ao sistema de gestão de estoque do SENAI.</p>
            <p>Utilize o menu ao lado para cadastrar novos produtos ou gerenciar o estoque existente.</p>
        </div>
    </main>
</body>
</html>