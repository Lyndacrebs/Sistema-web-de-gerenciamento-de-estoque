<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('includes/conexao.php');

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$products = [];
$stmt = $pdo->prepare("
    SELECT p.*, 
           (SELECT COUNT(*) FROM movimentacoes m WHERE m.produto_id = p.id) as total_movimentacoes
    FROM produtos p
    WHERE p.nome LIKE ? OR p.tipo LIKE ? OR p.codigo LIKE ?
    ORDER BY p.nome ASC
");
$stmt->execute(["%$search%", "%$search%", "%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Estoque - Sistema de Gestão de Estoque</title>
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
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            color: #2a5298;
            font-size: 28px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            width: 250px;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #2a5298;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #e3f2fd;
            color: #1a2a6c;
            font-weight: 600;
        }

        tr:hover {
            background-color: #fafafa;
        }

        .stock-low {
            background-color: #ffebee;
            color: #c62828;
            font-weight: bold;
        }

        .actions button {
            margin-right: 8px;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-in {
            background-color: #4caf50;
            color: white;
        }

        .btn-out {
            background-color: #f44336;
            color: white;
        }

        .btn-in:hover {
            background-color: #388e3c;
        }

        .btn-out:hover {
            background-color: #d32f2f;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .alert-icon {
            display: inline-block;
            width: 18px;
            height: 18px;
            background-color: #d32f2f;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            margin-right: 8px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Estoque</h3>
        </div>
        <a href="principal.php" class="nav-link">Painel Principal</a>
        <a href="cadastroProduto.php" class="nav-link">Cadastro de Produto</a>
        <a href="logout.php" class="nav-link">Sair</a>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Gestão de Estoque</h1>
            <form method="GET" class="search-box">
                <input type="text" name="search" placeholder="Buscar por nome, tipo ou código..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Tipo</th>
                        <th>Código</th>
                        <th>Qtd. Atual</th>
                        <th>Min.</th>
                        <th>Mov.</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                            $isLowStock = $product['estoque_atual'] < $product['estoque_minimo'];
                            $rowClass = $isLowStock ? 'stock-low' : '';
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td><?php echo htmlspecialchars($product['nome']); ?></td>
                                <td><?php echo htmlspecialchars($product['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($product['codigo']); ?></td>
                                <td>
                                    <?php if ($isLowStock): ?>
                                        <span class="alert-icon">!</span>
                                    <?php endif; ?>
                                    <?php echo $product['estoque_atual']; ?>
                                </td>
                                <td><?php echo $product['estoque_minimo']; ?></td>
                                <td><?php echo $product['total_movimentacoes']; ?></td>
                                <td class="actions">
                                    <button class="btn-in" onclick="registerMovement(<?php echo $product['id']; ?>, 'entrada')">Entrada (+)</button>
                                    <button class="btn-out" onclick="registerMovement(<?php echo $product['id']; ?>, 'saida')">Saída (-)</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">Nenhum produto encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function registerMovement(productId, type) {
            const qty = prompt(`Quantas unidades deseja registrar para ${type} deste produto?`);
            
            if (qty === null || qty === "" || isNaN(qty) || parseInt(qty) <= 0) {
                alert("Quantidade inválida.");
                return;
            }

            const numQty = parseInt(qty);

            // Envia os dados via POST para um script PHP
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'processarMovimentacao.php';

            const inputProduct = document.createElement('input');
            inputProduct.type = 'hidden';
            inputProduct.name = 'produto_id';
            inputProduct.value = productId;

            const inputType = document.createElement('input');
            inputType.type = 'hidden';
            inputType.name = 'tipo';
            inputType.value = type;

            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = 'quantidade';
            inputQty.value = numQty;

            form.appendChild(inputProduct);
            form.appendChild(inputType);
            form.appendChild(inputQty);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>