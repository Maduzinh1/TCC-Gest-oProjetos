<?php
    session_start();
    require_once(__DIR__ . "/../Model/Usuario.class.php");

    $acao = $_POST['acao'] ?? '';

    if ($acao === 'logar') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario = Usuario::autenticarLogin($email, $senha);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            header('Location: ../View/index.php');
            exit;
        } else {
            header('Location: ../View/login.php?erro=1');
            exit;
        }
    } else if ($acao === 'cadastrar') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        try {
            $usuario = new Usuario(null, $nome, $email, $senha);

            // Verifica se já existe usuário com esse email
            $usuariosExistentes = Usuario::listar(3, $email);
            foreach ($usuariosExistentes as $u) {
                if ($u->getEmail() === $email) {
                    header('Location: ../View/cadastro.php?erro=2');
                    exit;
                }
            }

            if ($usuario->inserir()) {
                $_SESSION['usuario_id'] = $usuario->getId();
                $_SESSION['usuario_nome'] = $usuario->getNome();
                $_SESSION['usuario_email'] = $usuario->getEmail();
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/cadastro.php?erro=3');
                exit;
            }
        } catch (Exception $e) {
            header('Location: ../View/cadastro.php?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }

?>