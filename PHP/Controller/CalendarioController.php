<?php
    require_once (__DIR__ . "/../Config/Database.class.php");
    require_once (__DIR__ . "/../Model/Calendario.class.php");

    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

    switch ($acao) {
        case 'buscar':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                echo json_encode(['erro' => 'ID inválido']);
                exit;
            }

            $itens = Calendario::listar(1, $id);
            if (empty($itens)) {
                echo json_encode(['erro' => 'Item não encontrado']);
                exit;
            }

            $item = $itens[0];
            echo json_encode([
                'id' => $item->getId(),
                'nome' => $item->getNome(),
                'descricao' => $item->getDescricao(),
                'data_inicio' => $item->getDataInicio(),
                'data_fim' => $item->getDataFim(),
                'status' => $item->getStatus(),
                'urgencia' => $item->getUrgencia()
            ]);
            break;
        
        case 'adicionar':
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $data_inicio = $_POST['data_inicio'] ?? '';
            $data_fim = $_POST['data_fim'] ?? '';
            $status = $_POST['status'] ?? '';
            $urgencia = $_POST['urgencia'] ?? '';
            $tag_id = $_POST['tag_id'] ?? null;

            $item = new Calendario(null, $nome, $descricao, $data_inicio, $data_fim, $status, $urgencia);
            $resultado = $item->inserir();

            if ($resultado) {
                // Pegue o ID do item inserido
                $item_id = $item->getId();
                if ($tag_id) {
                    error_log("ID do calendário inserido: " . $item_id);
                    $sql = "INSERT INTO Calendario_Tag (idCalendario, idTag) VALUES (:idCalendario, :idTag);";
                    Database::executar($sql, [':idCalendario' => $item_id, ':idTag' => $tag_id]);
                }
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=4');
                exit;
            }
            break;

        case 'alterar':
            $id = $_POST['id'] ?? 0;
            $nome = $_POST['nome'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $data_inicio = $_POST['data_inicio'] ?? '';
            $data_fim = $_POST['data_fim'] ?? '';
            $status = $_POST['status'] ?? '';
            $urgencia = $_POST['urgencia'] ?? '';

            $item = new Calendario($id, $nome, $descricao, $data_inicio, $data_fim, $status, $urgencia);
            $resultado = $item->alterar();

            if ($resultado) {
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=6');
                exit;
            }
            break;

        case 'excluir':
            $id = $_GET['id'] ?? 0;
            if ($id <= 0) {
                header('Location: ../View/index.php');
                exit;
            }

            $itens = Calendario::listar(1, $id);
            if (empty($itens)) {
                header('Location: ../View/index.php?erro=7');
                exit;
            }

            $item = $itens[0];
            $resultado = $item->excluir();

            if ($resultado) {
                header('Location: ../View/index.php');
                exit;
            } else {
                header('Location: ../View/index.php?erro=8');
                exit;
            }
            break;

        case 'itens-do-dia':
            $data = $_GET['data'] ?? '';
            if (!$data) {
                echo "<p>Data inválida.</p>";
                exit;
            }
            $items = Calendario::listar(3, $data);
            list($ano, $mes, $dia) = explode('-', $data);

            $html = "
                <div class='popup-header-dia'>
                    <span class='popup-dia'>{$dia}/{$mes}/{$ano}</span>
                    <span class='close' onclick='fecharPopupItemsDoDia()'>
                        <i class='fa-solid fa-xmark'></i>
                    </span>
                </div>
            ";
            if (empty($items)) {
                $html .= "<p>Nenhum item neste dia.</p>";
            } else {
                $html .= "
                    <table class='items-dia'>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Urgência</th>
                                <th>Alterar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                ";
                foreach ($items as $i) {
                    $html .= "
                        <tr>
                            <td class='item-calendario-nome'>".htmlspecialchars($i->getNome())."</td>
                            <td class='item-calendario-descricao'>".htmlspecialchars($i->getDescricao())."</td>
                            <td>".htmlspecialchars($i->getStatus())."</td>
                            <td>".htmlspecialchars($i->getUrgencia())."</td>
                            <td style='text-align:center;'>
                                <a href='index.php?id=" . $i->getId() . "' class='btn-editar'> Alterar </a>
                            </td>
                            <td style='text-align:center;'>
                                <a href='../Controller/CalendarioController.php?acao=excluir&id=" . $i->getId() . "' class='btn-excluir' onclick='return confirm(\"Tem certeza que deseja excluir este item?\");'> Excluir </a>
                            </td>
                        </tr>
                    ";
                }
                $html .= "
                        </tbody>
                    </table>
                ";
            }
            echo $html;
            break;

        default:
            header('Location: ../View/index.php?erro=9');
            exit;
    }
?>
