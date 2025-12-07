<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class QuadroProjeto {
        private $id;
        private $titulo;
        private $descricao;
        private $imagem;
        private $status;
        private $idProjeto;

        public function __construct($id, $titulo, $descricao, $imagem, $status, $idProjeto) {
            $this->id = $id;
            $this->titulo = $titulo;
            $this->descricao = $descricao;
            $this->imagem = $imagem;
            $this->status = $status;
            $this->idProjeto = $idProjeto;
        }

        public function getId() { 
            return $this->id; 
        }

        public function setId($id) { 
            $this->id = $id; 
        }

        public function getTitulo() { 
            return $this->titulo; 
        }

        public function setTitulo($titulo) { 
            $this->titulo = $titulo; 
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

        public function getStatus() { 
            return $this->status; 
        }

        public function setStatus($status) { 
            $this->status = $status; 
        }

        public function getIdProjeto() { 
            return $this->idProjeto; 
        }

        
        public function setIdProjeto($idProjeto) { 
            $this->idProjeto = $idProjeto; 
        }

        public function inserir() {
            $sql = "INSERT INTO QuadroProjeto 
                        (titulo, descricao, imagem, status, idProjeto)
                        VALUES (:titulo, :descricao, :imagem, :status, :idProjeto)";
            $parametros = array(':titulo'=>$this->getTitulo(),
                                ':descricao'=>$this->getDescricao(),
                                ':imagem'=>$this->getImagem(),
                                ':status'=>$this->getStatus(),
                                ':idProjeto'=>$this->getIdProjeto());
            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info='') {
            $sql = "SELECT * FROM QuadroProjeto";
            switch ($tipo) {
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
                case 2: $sql .= " WHERE titulo like :info ORDER BY titulo"; $info = '%'.$info.'%'; break; // filtro por título
                case 3: $sql .= " WHERE idProjeto = :info ORDER BY id"; break; // filtro por idProjeto
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $quadroProjetos = [];
            while ($registro = $comando->fetch()) {
                $quadroProjeto = new QuadroProjeto($registro['id'], $registro['titulo'], $registro['descricao'], $registro['imagem'], $registro['status'], $registro['idProjeto']);
                array_push($quadroProjetos,$quadroProjeto);
            }
            return $quadroProjetos;
        }

        public function alterar() {       
            $sql = "UPDATE QuadroProjeto
                    SET titulo = :titulo, 
                        descricao = :descricao, 
                        imagem = :imagem,
                        status = :status,
                        idProjeto = :idProjeto
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                                ':titulo'=>$this->getTitulo(),
                                ':descricao'=>$this->getDescricao(),
                                ':imagem'=>$this->getImagem(),
                                ':status'=>$this->getStatus(),
                                ':idProjeto'=>$this->getIdProjeto());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir() {
            $sql = "DELETE FROM QuadroProjeto
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>