<?php
require_once (__DIR__."/../Classes/Projeto.class.php");
setlocale(LC_TIME, 'portuguese'); 
date_default_timezone_set('America/Sao_Paulo');
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
$busca = isset($_GET['busca'])?$_GET['busca']:0;
$tipo = isset($_GET['tipo'])?$_GET['tipo']:0;
$projetos = Projeto::listar($tipo, $busca);
$projetos_array = [];
foreach ($projetos as $projeto) {
    $projetos_array[] = [
        'id' => $projeto->getId(),
        'nome' => $projeto->getNome(),
        'descricao' => $projeto->getDescricao(),
        'tag' => $projeto->getTag(),
        'data_inicio' => $projeto->getDataInicio(),
        'data_fim' => $projeto->getDataFim(),
        'status' => $projeto->getStatus(),
        'urgencia' => $projeto->getUrgencia()
    ];
}

function gerarCalendario($mes, $ano, $projetos_array) {
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
            } elseif ($dia > $diasNoMes) {
                $html .= '<td></td>';
            } else {
                $dataStr = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                $isHoje = ($dataStr == $hoje);
                $projetosDia = array_filter($projetos_array, fn($p) => $p['data_inicio'] == $dataStr);
                $cellClasses = [];
                if ($isHoje) $cellClasses[] = 'today';
                if (count($projetosDia) > 0) $cellClasses[] = 'dia-com-projeto';
                $html .= '<td class="'.implode(' ', $cellClasses).'">';
                $html .= "<span class='numero-dia'>$dia</span>";
                foreach ($projetosDia as $p) {
                    $html .= "<div class='projeto-calendario'>{$p['nome']}</div>";
                }
                $html .= '<button class="add-btn" onclick="adicionarPojetos(event); event.stopPropagation();" title="Adicionar evento"></button>';
                $html .= '</td>';
                $dia++;
            }
        }
        $html .= '</tr>';
    }
    return $html;
}

echo gerarCalendario($mes + 1, $ano, $projetos_array);
?>