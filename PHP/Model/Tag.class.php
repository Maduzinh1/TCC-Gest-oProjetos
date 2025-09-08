<?php
    require_once (__DIR__ . "/../Config/Database.class.php");
    
    class Tag{
        private $id;
        private $nome;
        private $cor;

        // construtor da classe
        public function __construct($id, $nome, $cor){
            $this->setId($id);
            $this->setNome($nome);
            $this->setCor($cor);
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

        public function getCor(){
            return $this->cor;
        }

        public function setCor($cor){
            $this->cor = $cor;
        }

        // método mágico para imprimir uma tag
        public function __toString():String{
            $str = "Tag: ".$this->getId()." - ".$this->getNome()." - ".$this->getCor();
            return $str;
        }

        // insere uma tag no banco 
        public function inserir():Bool{
            // montar o sql/ query
            $sql = "INSERT INTO Tag 
                        (nome, cor)
                        VALUES(:nome, :cor)";

            $parametros = array(':nome'=>$this->getNome(),
                                ':cor'=>$this->getCor());

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
            $tags = [];
            while ($registro = $comando->fetch()){
                $tag = new Tag($registro['id'], $registro['nome'], $registro['cor']);
                array_push($tags,$tag);
            }
            return $tags;
        }

        public function alterar():Bool{       
        $sql = "UPDATE Tag
                    SET nome = :nome, 
                        cor = :cor
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                                ':nome'=>$this->getNome(),
                                ':cor'=>$this->getCor());
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