<?php
    require_once (__DIR__ . "/../Model/Tag.class.php");
    session_start();
    
    $idUsuario = $_SESSION['usuario_id'];
    $tags = Tag::listar(0, 0);
    $tagsUsuario = [];
    foreach ($tags as $tag) {
        if ($tag->getIdUsuario() == $idUsuario) {
            $tagsUsuario[] = $tag;
        }
    }
    $html = "
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cor</th>
            </tr>
    ";
    foreach ($tagsUsuario as $tag) {
        $html .= "
                <tr>
                    <td>{$tag->getId()}</td>
                    <td>{$tag->getNome()}</td>
                    <td><div style='height:20px;width:100%;background:{$tag->getCor()};'></div></td>
                </tr>
        ";
    }
    echo $html;
?>