<?php
    require_once (__DIR__ . "/../Model/Pastas.class.php");
    session_start();

    $idUsuario = $_SESSION['usuario_id'];

    // Liste as pastas do usuário logado
    $pastas = Pastas::listar(1, $idUsuario);
    $html = "";
    foreach ($pastas as $pasta) {
        $imagem = $pasta->getImagem() ?: './../../img/pasta-default.png';
        $html .= "
            <div class='Pasta'>" .
                /*<a href='./pasta.php?id={$pasta->getId()}' style='text-decoration:none;color:inherit;display:block;height:100%;'>*/
                    "<div class='ImageSide'>
                        <img src='{$imagem}' alt='Imagem da Pasta'>
                    </div>
                    <div class='DescSide'>
                        <h3>" . htmlspecialchars($pasta->getNome()) . "</h3>
                        <p>" . htmlspecialchars($pasta->getDescricao()) . "</p>
                    </div>
                "/*</a>*/ . "
            </div>
        ";
    }
    echo $html;
?>