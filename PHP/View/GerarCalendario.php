<?php
    require_once (__DIR__ . "/../Model/Calendario.class.php");
    require_once (__DIR__ . "/../Model/Tag.class.php");
    session_start();
    setlocale(LC_TIME, 'portuguese'); 
    date_default_timezone_set('America/Sao_Paulo');

    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
    $ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
    $busca = isset($_GET['busca'])?$_GET['busca']:0;
    $tipo = isset($_GET['tipo'])?$_GET['tipo']:0;
    $usuario_id = $_SESSION['usuario_id'];

    $items = Calendario::listar($tipo, $busca);
    $item_array = [];
    foreach ($items as $item) {
        if ($item->getIdUsuario() == $usuario_id) {
            $corTag = Tag::buscarCorPorCalendario($item->getId());
            $item_array[] = [
                'id' => $item->getId(),
                'nome' => $item->getNome(),
                'descricao' => $item->getDescricao(),
                'data_inicio' => $item->getDataInicio(),
                'data_fim' => $item->getDataFim(),
                'status' => $item->getStatus(),
                'urgencia' => $item->getUrgencia(),
                'cor' => $corTag
            ];
        }
    }

    function gerarCalendario($mes, $ano, $item_array) {
        $primeiroDia = date('w', strtotime("$ano-$mes-01"));
        $diasNoMes = date('t', strtotime("$ano-$mes-01"));
        $diasDaSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        $hoje = date('Y-m-d');
        $html = '<tr>' . implode('', array_map(fn($d) => "<th>$d</th>", $diasDaSemana)) . '</tr>';
        $dia = 1;

        for ($semana = 0; $semana < 6; $semana++) {
            $html .= '<tr>';
            for ($i = 0; $i < 7; $i++) {
                if ($semana === 0 && $i < $primeiroDia) {
                    $html .= '<td></td>';
                } else if ($dia > $diasNoMes) {
                    $html .= '<td></td>';
                } else {
                    $dataStr = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                    $isHoje = ($dataStr == $hoje);
                    $itemDia = array_filter($item_array, fn($p) => $p['data_inicio'] == $dataStr);
                    $cellClasses = [];
                    if ($isHoje) {
                        $cellClasses[] = 'today';
                    }
                    if (count($itemDia) > 0) {
                        $cellClasses[] = 'dia-com-item';
                    }
                    $html .= '<td class="'.implode(' ', $cellClasses).'" onclick="abrirPopupItemsDoDia(\''.$dataStr.'\', event)">';
                    $html .= "<span class='numero-dia'>$dia</span>";
                    if (count($itemDia) > 3) {
                        $html .= implode('', array_map(function($p) {
                            $cor = $p['cor'] ?? '#2196f3';
                            $corFade = (strlen($cor) === 7 ? $cor . '33' : $cor);
                            return "<div class='item-calendario' style='background:{$corFade}'>{$p['nome']}</div>";
                        }, array_slice($itemDia, 0, 2)));
                        $html .= "<div class='item-mais'>Mais....</div>";
                    } else {
                        $html .= implode('', array_map(function($p) {
                            $cor = $p['cor'] ?? '#2196f3';
                            $corFade = (strlen($cor) === 7 ? $cor . '33' : $cor);
                            return "<div class='item-calendario' style='background:{$corFade}'>{$p['nome']}</div>";
                        }, $itemDia));
                    }
                    $html .= '
                            <button class="add-btn" onclick="abrirPopupAddItem(event)" title="Adicionar evento">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </td>';
                    $dia++;
                }
            }
            $html .= '</tr>';
        }
        return $html;
    }

    echo gerarCalendario($mes + 1, $ano, $item_array);
?>