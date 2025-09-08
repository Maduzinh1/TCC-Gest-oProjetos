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
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($erro && $erro === '1') { ?>
        <p style="color:red;"><?php echo "Usuário ou senha inválidos!" ?></p>
    <?php } ?>
    <form method="post" action="../Controller/UsuarioController.php">

        <div>
            <label for="email-usuario">Email:</label>
            <input type="email" id="email-usuario" name="email" required>
        </div>

        <div>
            <label for="senha-usuario">Senha:</label>
            <input type="password" id="senha-usuario" name="senha" required>
        </div>

        <button id="btn-login" type="submit" name="acao" value="logar">Entrar</button>
    </form>
    <p>Não tem conta? <a href="cadastro.php">Faça cadastro</a></p>
</body>
</html>