<?php
    require_once("../Classes/Calendario.class.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = isset($_POST['id'])?$_POST['id']:0;
        $nome = isset($_POST['nome'])?$_POST['nome']:"";
        $descricao = isset($_POST['descricao'])?$_POST['descricao']:"";
        $data_inicio = isset($_POST['data_inicio'])?$_POST['data_inicio']:"";
        $data_fim = isset($_POST['data_fim'])?$_POST['data_fim']:"";
        $status = isset($_POST['status'])?$_POST['status']:"";
        $urgencia = isset($_POST['urgencia'])?$_POST['urgencia']:"";
        $acao = isset($_POST['acao'])?$_POST['acao']:"";

        $item = new Calendario($id, $nome, $descricao, $data_inicio, $data_fim, $status, $urgencia);
        if ($acao == 'salvar') {
            $resultado = $item->inserir();
        } elseif ($acao == 'alterar') {
            $resultado = $item->alterar();
        } elseif ($acao == 'excluir') {
            $resultado = $item->excluir();
        }

        if ($resultado) {
            header("Location: ../../index.php");
        } else {
            echo "Erro ao salvar dados: ". $item;
        }
    }elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
        $id = isset($_GET['id'])?$_GET['id']:0;
        $resultado = Calendario::listar(1,$id);
        if ($resultado) {
            $item = $resultado[0];
            $formulario = str_replace('{id}', $item->getId(), $formulario);
            $formulario = str_replace('{nome}', $item->getNome(), $formulario);
            $formulario = str_replace('{descricao}', $item->getDescricao(), $formulario);
            $formulario = str_replace('{data_inicio}', $item->getDataInicio(), $formulario);
            $formulario = str_replace('{data_fim}', $item->getDataFim(), $formulario);
            $formulario = str_replace('{status}', $item->getStatus(), $formulario);
            $formulario = str_replace('{urgencia}', $item->getUrgencia(), $formulario);
        } else {
            $formulario = str_replace('{id}', 0, $formulario);
            $formulario = str_replace('{nome}', '', $formulario);
            $formulario = str_replace('{descricao}', '', $formulario);
            $formulario = str_replace('{data_inicio}', '', $formulario);
            $formulario = str_replace('{data_fim}', '', $formulario);
            $formulario = str_replace('{status}', '', $formulario);
            $formulario = str_replace('{urgencia}', '', $formulario);
        }
        print($formulario);
        header("Location: ../../index.php");
    }
?>