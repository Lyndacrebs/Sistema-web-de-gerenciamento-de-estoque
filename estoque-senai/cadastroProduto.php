<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('includes/conexao.php');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $tipo = trim($_POST['tipo']);
    $codigo = trim($_POST['codigo']);
    $estoque_minimo = (int)$_POST['estoque_minimo'];
    $estoque_atual = (int)$_POST['estoque_atual'];

    if (empty($nome) || empty($tipo) || empty($codigo)) {
        $message = "Todos os campos obrigatórios devem ser preenchidos.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, tipo, codigo, estoque_minimo, estoque_atual) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $tipo, $codigo, $estoque_minimo, $estoque_atual]);
            $message = "Produto cadastrado com sucesso!";
        } catch (PDOException $e) {
            $message = "Erro ao cadastrar produto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto - Sistema de Gestão de Estoque</title>
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

        .breadcrumb {
            font-size: 14px;
            color: #666;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            max-width: 700px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #1a2a6c;
            font-size: 22px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group.full {
            flex: 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2a5298;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary {
            background-color: #2a5298;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1a2a6c;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .alert {
            padding: 12px;
            margin-top: 20px;
            border-radius: 6px;
            display: none;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Estoque</h3>
        </div>
        <a href="principal.php" class="nav-link">Painel Principal</a>
        <a href="gestaoEstoque.php" class="nav-link">Gestão de Estoque</a>
        <a href="logout.php" class="nav-link">Sair</a>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Cadastro de Produto</h1>
            <div class="breadcrumb">Painel Principal &gt; Cadastro de Produto</div>
        </div>

        <div class="form-container">
            <h2>Informações do Produto</h2>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="Martelo" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Martelo') ? 'selected' : ''; ?>>Martelo</option>
                            <option value="Chave de Fenda" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Chave de Fenda') ? 'selected' : ''; ?>>Chave de Fenda</option>
                            <option value="Alicate" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Alicate') ? 'selected' : ''; ?>>Alicate</option>
                            <option value="Chave Inglesa" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Chave Inglesa') ? 'selected' : ''; ?>>Chave Inglesa</option>
                            <option value="Serrote" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Serrote') ? 'selected' : ''; ?>>Serrote</option>
                            <option value="Lima" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Lima') ? 'selected' : ''; ?>>Lima</option>
                            <option value="Ferramenta de Corte" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Ferramenta de Corte') ? 'selected' : ''; ?>>Ferramenta de Corte</option>
                            <option value="Chave Allen" <?php echo (isset($_POST['tipo']) && $_POST['tipo'] == 'Chave Allen') ? 'selected' : ''; ?>>Chave Allen</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full">
                        <label for="codigo">Código *</label>
                        <input type="text" id="codigo" name="codigo" value="<?php echo isset($_POST['codigo']) ? htmlspecialchars($_POST['codigo']) : ''; ?>" placeholder="Ex: MAR-BOR-500" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="estoque_minimo">Estoque Mínimo *</label>
                        <input type="number" id="estoque_minimo" name="estoque_minimo" min="0" value="<?php echo isset($_POST['estoque_minimo']) ? htmlspecialchars($_POST['estoque_minimo']) : '0'; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="estoque_atual">Estoque Atual *</label>
                        <input type="number" id="estoque_atual" name="estoque_atual" min="0" value="<?php echo isset($_POST['estoque_atual']) ? htmlspecialchars($_POST['estoque_atual']) : '0'; ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                    <a href="principal.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

            <?php if ($message): ?>
                <div class="alert <?php echo strpos($message, 'Erro') !== false ? 'alert-error' : 'alert-success'; ?>" style="display: block;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>