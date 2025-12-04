<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Pasta {
        private $id;
        private $nome;
        private $descricao;
        private $imagem;
        private $idUsuario;

        public function __construct($id, $nome, $descricao, $imagem, $idUsuario) {
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->imagem = $imagem;
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

        public function getImagem() { 
            return $this->imagem; 
        }

        public function setImagem($imagem) { 
            $this->imagem = $imagem; 
        }

        public function getIdUsuario() { 
            return $this->idUsuario; 
        }

        public function setIdUsuario($idUsuario) { 
            $this->idUsuario = $idUsuario; 
        }

        public function inserir() {
            $sql = "INSERT INTO Pastas 
                        (nome, descricao, imagem, idUsuario) 
                        VALUES (:nome, :descricao, :imagem, :idUsuario)";
            $parametros = array(':nome' => $this->getNome(),
                                ':descricao' => $this->getDescricao(),
                                ':imagem' => $this->getImagem(),
                                ':idUsuario' => $this->getIdUsuario());
            list($conexao, $comando) = Database::executar($sql, $parametros);
            if ($comando) {
                $this->id = $conexao->lastInsertId();
                return true;
            }
            return false;
        }

        public static function listar($tipo=0, $info=''): Array {
            $sql = "SELECT * FROM Pastas";
            switch ($tipo){
                case 0: break;
                case 1: $sql .= " WHERE idUsuario = :info ORDER BY idUsuario;"; break; // filtro por ID
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $pastas = [];
            while ($registro = $comando->fetch()) {
                $pasta = new Pasta($registro['id'], $registro['nome'], $registro['descricao'], $registro['imagem'], $registro['idUsuario']);
                array_push($pastas,$pasta);
            }
            return $pastas;
        }

        public function alterar(): Bool {       
        $sql = "UPDATE Pastas
                    SET nome = :nome, 
                        descricao = :descricao,
                        imagem = :imagem
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                                ':nome'=>$this->getNome(),
                                ':descricao'=>$this->getDescricao(),
                                ':imagem'=>$this->getImagem());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir(): Bool {
            $sql = "DELETE FROM Pastas
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>