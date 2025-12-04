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
    <link rel="stylesheet" href="./../../CSS/estilo.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Cadastrar Conta</title>
</head>
<body class="body cadastro-login">
    <h2>Cadastrar Conta</h2>
    <?php if ($erro && $erro === '2') { ?>
        <p style="color:red;"><?php echo "Email já cadastrado!" ?></p>
    <?php } else if ($erro && $erro === '3') { ?>
        <p style="color:red;"><?php echo "Erro ao cadastrar usuário!" ?></p>
    <?php } ?>
    <div class="form-container">
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
    
            <button id="btn-login" type="submit" name="acao" value="adicionar">Cadastrar</button>
        </form>
    </div>
    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</body>
</html>