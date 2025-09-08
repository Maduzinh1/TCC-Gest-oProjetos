<?php
    session_start();
    if (isset($_SESSION['usuario_id'])) {
        header('Location: index.php');
        exit;
    }
    $erro = isset($_GET['erro']) ? $_GET['erro'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Conta</title>
</head>
<body>
    <h2>Cadastrar Conta</h2>
    <?php if ($erro && $erro === '2') { ?>
        <p style="color:red;"><?php echo "Email já cadastrado!" ?></p>
    <?php } else if ($erro && $erro === '3') { ?>
        <p style="color:red;"><?php echo "Erro ao cadastrar usuário!" ?></p>
    <?php } ?>
    <form method="post" action="../Controller/UsuarioController.php">

        <div>
            <label for="nome-usuario">Nome:</label>
            <input type="text" id="nome-usuario" name="nome" required>
        </div>

        <div>
            <label for="email-usuario">Email:</label>
            <input type="email" id="email-usuario" name="email" required>
        </div>

        <div>
            <label for="senha-usuario">Senha:</label>
            <input type="password" id="senha-usuario" name="senha" required>
        </div>

        <button id="btn-login" type="submit" name="acao" value="cadastrar">Cadastrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>