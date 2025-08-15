<?php
    require_once (__DIR__."/Database.class.php");
    class Tag{
        private $id;
        private $nome;

        // construtor da classe
        public function __construct($id, $nome){
            $this->setId($id);
            $this->setNome($nome);
        }

        public function getId() {
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            if (strlen($nome) < 3)
                throw new Exception('Erro. O nome deve ter pelo menos 3 caracteres');
            else
                $this->nome = $nome;
        }

        // método mágico para imprimir uma tag
        public function __toString():String{
            $str = "Tag: ".$this->getId()." - ".$this->getNome();
            return $str;
        }

        // insere uma tag no banco 
        public function inserir():Bool{
            // montar o sql/ query
            $sql = "INSERT INTO Tag 
                        (nome)
                        VALUES(:nome)";

            $parametros = array(':nome'=>$this->getNome());

            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info=''):Array{
            $sql = "SELECT * FROM Tag";
            switch ($tipo){
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
                case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // filtro por nome
            }
            $parametros = array();
            if ($tipo > 0)
                $parametros = [':info'=>$info];

            $comando = Database::executar($sql, $parametros);
            $items = [];
            while ($registro = $comando->fetch()){
                $item = new Tag($registro['id'], $registro['nome']);
                array_push($items,$item);
            }
            return $items;
        }

        public function alterar():Bool{       
        $sql = "UPDATE Tag
                    SET nome = :nome
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                            ':nome'=>$this->getNome());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir():Bool{
            $sql = "DELETE FROM Tag
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>