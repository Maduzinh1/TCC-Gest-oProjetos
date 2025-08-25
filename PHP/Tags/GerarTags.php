<?php
    require_once(__DIR__."/../Classes/Tag.class.php");
    $tags = Tag::listar(0, 0);
    $html = "
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cor</th>
            </tr>
    ";
    foreach ($tags as $tag) {
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