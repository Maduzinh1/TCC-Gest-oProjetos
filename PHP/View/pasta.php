<?php
    require_once(__DIR__ . "/../Model/Pastas.class.php");
    require_once(__DIR__ . "/../Model/Anotacao.class.php");
    session_start();

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }

    $idUsuario = $_SESSION['usuario_id'];
    $idPasta = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Verifica se a pasta existe e pertence ao usuário
    $pasta = null;
    $pastas = Pastas::listar(1, $idUsuario);
    foreach ($pastas as $p) {
        if ($p->getId() == $idPasta) {
            $pasta = $p;
            break;
        }
    }

    if (!$pasta) {
        echo "Pasta não encontrada ou você não tem permissão para acessá-la.";
        exit;
    }

    // Liste as anotações da pasta
    require_once(__DIR__ . "/../Model/Anotacao.class.php");
    $anotacoes = Anotacao::listar(3, $idPasta);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../CSS/estilo.css">
    <title><?php echo htmlspecialchars($pasta->getNome()); ?></title>
</head>
<body class="pastas">
    <div class="header">
        <h1><?php echo htmlspecialchars($pasta->getNome()); ?></h1>
        <p><?php echo htmlspecialchars($pasta->getDescricao()); ?></p>
    </div>

    <div class="conteudo">
        <h2>Anotações</h2>
        <div class="anotacoes">
            <?php foreach ($anotacoes as $anotacao) { ?>
                <div class="anotacao">
                    <h3><?php echo htmlspecialchars($anotacao->getTitulo()); ?></h3>
                    <p><?php echo htmlspecialchars($anotacao->getConteudo()); ?></p>
                    <?php if ($anotacao->getLink()) { ?>
                        <a href="<?php echo htmlspecialchars($anotacao->getLink()); ?>" target="_blank">Abrir Link</a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <h2>Nova Anotação</h2>
        <form class="pasta-anotacao-form" action="../Controller/AnotacaoController.php" method="post">
            <input type="hidden" name="acao" value="adicionar">
            <input type="hidden" name="idPasta" value="<?php echo $idPasta; ?>">
            <div>
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div>
                <label for="conteudo">Conteúdo:</label>
                <textarea id="conteudo" name="conteudo" required></textarea>
            </div>
            <div>
                <label for="link">Link (opcional):</label>
                <input type="url" id="link" name="link">
            </div>
            <button type="submit">Adicionar Anotação</button>
        </form>
    </div>
</body>
</html>