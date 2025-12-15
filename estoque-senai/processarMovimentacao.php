<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('includes/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = (int)$_POST['produto_id'];
    $tipo = $_POST['tipo'];
    $quantidade = (int)$_POST['quantidade'];

    if ($produto_id <= 0 || !in_array($tipo, ['entrada', 'saida']) || $quantidade <= 0) {
        $error = "Dados inválidos.";
    } else {
        try {
            $pdo->beginTransaction();

            // Obtém o produto
            $stmt = $pdo->prepare("SELECT estoque_atual FROM produtos WHERE id = ?");
            $stmt->execute([$produto_id]);
            $product = $stmt->fetch();

            if (!$product) {
                throw new Exception("Produto não encontrado.");
            }

            $novo_estoque = $product['estoque_atual'];
            if ($tipo === 'entrada') {
                $novo_estoque += $quantidade;
            } else { // saida
                if ($quantidade > $product['estoque_atual']) {
                    throw new Exception("Quantidade solicitada excede o estoque disponível.");
                }
                $novo_estoque -= $quantidade;
            }

            // Atualiza o estoque
            $stmt = $pdo->prepare("UPDATE produtos SET estoque_atual = ? WHERE id = ?");
            $stmt->execute([$novo_estoque, $produto_id]);

            // Registra a movimentação
            $stmt = $pdo->prepare("INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade) VALUES (?, ?, ?, ?)");
            $stmt->execute([$produto_id, $_SESSION['user_id'], $tipo, $quantidade]);

            $pdo->commit();
            $success = "Movimentação registrada com sucesso!";

        } catch (Exception $e) {
            $pdo->rollback();
            $error = "Erro: " . $e->getMessage();
        }
    }
}

// Redireciona de volta para a gestão de estoque
header('Location: gestaoEstoque.php' . (isset($error) ? '?error=' . urlencode($error) : '') . (isset($success) ? '?success=' . urlencode($success) : ''));
exit;
?>