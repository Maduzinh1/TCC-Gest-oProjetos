<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Projeto {
        private $id;
        private $nome;
        private $descricao;
        private $idUsuario;

        public function __construct($id, $nome, $descricao, $idUsuario) {
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->idUsuario = $idUsuario;
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
            $this->nome = $nome; 
        }

        public function getDescricao() { 
            return $this->descricao; 
        }

        public function setDescricao($descricao) { 
            $this->descricao = $descricao; 
        }

        public function getIdUsuario() { 
            return $this->idUsuario; 
        }

        public function setIdUsuario($idUsuario) { 
            $this->idUsuario = $idUsuario; 
        }

        public function inserir() {
            $sql = "INSERT INTO Projeto 
                        (nome, descricao, idUsuario)
                        VALUES (:nome, :descricao, :idUsuario)";
            $parametros = array(':nome'=>$this->getNome(),
                                ':descricao'=>$this->getDescricao(),
                                ':idUsuario'=>$this->getIdUsuario());
            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info='') {
            $sql = "SELECT * FROM Projeto";
            switch ($tipo) {
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
                case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // filtro por nome
                case 3: $sql .= " WHERE idUsuario = :info ORDER BY id"; break; // filtro por idUsuario
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $projetos = [];
            while ($registro = $comando->fetch()) {
                $projeto = new Projeto($registro['id'], $registro['nome'], $registro['descricao'], $registro['idUsuario']);
                array_push($projetos,$projeto);
            }
            return $projetos;
        }

        public function alterar() {       
            $sql = "UPDATE Projeto
                    SET nome = :nome, 
                        descricao = :descricao
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                                ':nome'=>$this->getNome(),
                                ':descricao'=>$this->getDescricao());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir() {
            $sql = "DELETE FROM Projeto
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>