<?php
    require_once(__DIR__."/../Classes/Tag.class.php");
    $tags = Tag::listar(0, 0);
    $html = "
            <tr>
                <th>ID</th>
                <th>Nome</th>
            </tr>
    ";
    foreach ($tags as $tag) {
        $html .= "
                <tr>
                    <td>{$tag->getId()}</td>
                    <td>{$tag->getNome()}</td>
                </tr>
        ";
    }
    echo $html;
?>