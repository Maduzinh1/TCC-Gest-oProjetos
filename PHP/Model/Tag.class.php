<?php
    require_once (__DIR__ . "/../Config/Database.class.php");
    
    class Tag {
        private $id;
        private $nome;
        private $cor;
        private $idUsuario;

        // construtor da classe
        public function __construct($id, $nome, $cor, $idUsuario) {
            $this->setId($id);
            $this->setNome($nome);
            $this->setCor($cor);
            $this->setIdUsuario($idUsuario);
        }

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getNome() {
            return $this->nome;
        }

        public function setNome($nome) {
            if (strlen($nome) < 3) {
                throw new Exception('Erro. O nome deve ter pelo menos 3 caracteres');
            } else {
                $this->nome = $nome;
            }
        }

        public function getCor() {
            return $this->cor;
        }

        public function setCor($cor) {
            $this->cor = $cor;
        }

        public function getIdUsuario() {
            return $this->idUsuario;
        }

        public function setIdUsuario($idUsuario) {
            $this->idUsuario = $idUsuario;
        }

        // método mágico para imprimir uma tag
        public function __toString(): String {
            $str = "Tag: ".$this->getId()." - ".$this->getNome()." - ".$this->getCor()." - ".$this->getIdUsuario();
            return $str;
        }

        // insere uma tag no banco 
        public function inserir(): Bool {
            $sql = "INSERT INTO Tag 
                        (nome, cor, idUsuario)
                        VALUES(:nome, :cor, :idUsuario)";
            $parametros = array(':nome'=>$this->getNome(),
                                ':cor'=>$this->getCor(),
                                ':idUsuario'=>$this->getIdUsuario());
            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info=''): Array {
            $sql = "SELECT * FROM Tag";
            switch ($tipo) {
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
                case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // filtro por nome
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $tags = [];
            while ($registro = $comando->fetch()) {
                $tag = new Tag($registro['id'], $registro['nome'], $registro['cor'], $registro['idUsuario']);
                array_push($tags,$tag);
            }
            return $tags;
        }

        public function alterar(): Bool {       
        $sql = "UPDATE Tag
                    SET nome = :nome, 
                        cor = :cor
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                                ':nome'=>$this->getNome(),
                                ':cor'=>$this->getCor());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir(): Bool {
            $sql = "DELETE FROM Tag
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>