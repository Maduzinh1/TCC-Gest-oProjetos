<?php
    session_start();
    require_once(__DIR__ . "/../Model/Usuario.class.php");

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    switch ($acao) {
        case 'buscar':
            // Implementar se necessário
            break;

        case 'adicionar':
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
            break;

        case 'alterar':
            // Implementar se necessário
            break;

        case 'excluir':
            // Implementar se necessário
            break;

        case 'logar':
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
            break;

        case 'deslogar':
            session_destroy();
            header('Location: ../View/login.php');
            exit;

        case 'alterar_foto':
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'perfil_' . $_SESSION['usuario_id'] . '.' . $ext;
                $caminho = './../../img/' . $nomeArquivo;
                move_uploaded_file($_FILES['foto']['tmp_name'], $caminho);

                // Atualize no banco (ajuste conforme seu model)
                $usuario = Usuario::listar(1, $_SESSION['usuario_id'])[0];
                $usuario->setFotoPerfil('./../../img/' . $nomeArquivo);
                $usuario->salvarFotoPerfil(); // Implemente este método no model

                header('Location: ../View/index.php');
                exit;
            }
            break;
            
        default:
            header('Location: ../View/login.php');
            exit;
    }

?>