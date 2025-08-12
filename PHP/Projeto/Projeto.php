<?php
require_once("../Classes/Projeto.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = isset($_POST['id'])?$_POST['id']:0;
    $nome = isset($_POST['nome'])?$_POST['nome']:"";
    $descricao = isset($_POST['descricao'])?$_POST['descricao']:"";
    $tag = isset($_POST['tag'])?$_POST['tag']:"";
    $data_inicio = isset($_POST['data_inicio'])?$_POST['data_inicio']:"";
    $data_fim = isset($_POST['data_fim'])?$_POST['data_fim']:"";
    $status = isset($_POST['status'])?$_POST['status']:"";
    $urgencia = isset($_POST['urgencia'])?$_POST['urgencia']:"";
    $acao = isset($_POST['acao'])?$_POST['acao']:"";

    $projeto = new Projeto($id, $nome, $descricao, $tag, $data_inicio, $data_fim, $status, $urgencia);
    if ($acao == 'salvar') {
        $resultado = $projeto->inserir();
    } elseif ($acao == 'alterar') {
        $resultado = $projeto->alterar();
    } elseif ($acao == 'excluir') {
        $resultado = $projeto->excluir();
    }

    if ($resultado) {
        header("Location: ../../index.php");
    } else {
        echo "Erro ao salvar dados: ". $projeto;
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = isset($_GET['id'])?$_GET['id']:0;
    $resultado = Projeto::listar(1,$id);
    if ($resultado) {
        $projeto = $resultado[0];
        $formulario = str_replace('{id}', $projeto->getId(), $formulario);
        $formulario = str_replace('{nome}', $projeto->getNome(), $formulario);
        $formulario = str_replace('{descricao}', $projeto->getDescricao(), $formulario);
        $formulario = str_replace('{tag}', $projeto->getTag(), $formulario);
        $formulario = str_replace('{data_inicio}', $projeto->getDataInicio(), $formulario);
        $formulario = str_replace('{data_fim}', $projeto->getDataFim(), $formulario);
        $formulario = str_replace('{status}', $projeto->getStatus(), $formulario);
        $formulario = str_replace('{urgencia}', $projeto->getUrgencia(), $formulario);
    } else {
        $formulario = str_replace('{id}', 0, $formulario);
        $formulario = str_replace('{nome}', '', $formulario);
        $formulario = str_replace('{descricao}', '', $formulario);
        $formulario = str_replace('{tag}', '', $formulario);
        $formulario = str_replace('{data_inicio}', '', $formulario);
        $formulario = str_replace('{data_fim}', '', $formulario);
        $formulario = str_replace('{status}', '', $formulario);
        $formulario = str_replace('{urgencia}', '', $formulario);
    }
    print($formulario);
    header("Location: ../../index.php");
}
?>