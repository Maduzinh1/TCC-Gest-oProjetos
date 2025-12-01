<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Anotacao {
        private $id;
        private $titulo;
        private $conteudo;
        private $link;
        private $idPasta;

        public function __construct($id, $titulo, $conteudo, $link, $idPasta) {
            $this->id = $id;
            $this->titulo = $titulo;
            $this->conteudo = $conteudo;
            $this->link = $link;
            $this->idPasta = $idPasta;
        }

        public function getId() { return $this->id; }
        public function getTitulo() { return $this->titulo; }
        public function getConteudo() { return $this->conteudo; }
        public function getLink() { return $this->link; }
        public function getIdPasta() { return $this->idPasta; }

        public function inserir() {
            $sql = "INSERT INTO Anotacao 
                        (titulo, conteudo, link, idPasta) 
                        VALUES (:titulo, :conteudo, :link, :idPasta)";
            $parametros = array(':titulo' => $this->getTitulo(),
                                ':conteudo' => $this->getConteudo(),
                                ':link' => $this->getLink(),
                                ':idPasta' => $this->getIdPasta());
            list($conexao, $comando) = Database::executar($sql, $parametros);
            if ($comando) {
                $this->id = $conexao->lastInsertId();
                return true;
            }
            return false;
        }

        public static function listar($tipo=0, $info=''): Array {
            $sql = "SELECT * FROM Anotacao";
            switch ($tipo){
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id;"; break; // filtro por ID
                case 2: $sql .= " WHERE titulo like :info ORDER BY titulo;"; $info = '%'.$info.'%'; break; // filtro por titulo
                case 3: $sql .= " WHERE idPasta = :info ORDER BY idPasta;"; break; // filtro por pasta
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $anotacoes = [];
            while ($registro = $comando->fetch()) {
                $anotacao = new Anotacao($registro['id'], $registro['titulo'], $registro['conteudo'], $registro['link'], $registro['idPasta']);
                array_push($anotacoes,$anotacao);
            }
            return $anotacoes;
        }
    }
?>