<?php
    require_once (__DIR__ . "/../Model/Anotacao.class.php");
    session_start();

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    switch ($acao) {
        case 'adicionar':
            $titulo = $_POST['titulo'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';
            $link = $_POST['link'] ?? null;
            $idPasta = $_POST['idPasta'] ?? 0;

            $anotacao = new Anotacao(0, $titulo, $conteudo, $link, $idPasta);
            if ($anotacao->inserir()) {
                header("Location: ../View/pasta.php?id=$idPasta");
                exit;
            } else {
                header("Location: ../View/pasta.php?id=$idPasta&erro=1");
                exit;
            }
            break;

        default:
            header('Location: ../View/index.php');
            exit;
    }
?>