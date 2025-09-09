<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Usuario {
        private $id;
        private $nome;
        private $email;
        private $senha;
        private $foto_perfil;

        public function __construct($id, $nome, $email, $senha) {
            $this->id = $id;
            $this->nome = $nome;
            $this->email = $email;
            $this->senha = $senha;
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
                throw new Exception('Erro. O nome de usuário deve ter pelo menos 3 caracteres');
            }
            else {
                $this->nome = $nome;
            }
        }

        public function getEmail() {
            return $this->email;
        }

        public function setEmail($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Erro. Email inválido');
            } else {
                $this->email = $email;
            }
        }

        public function getSenha() {
            return $this->senha;
        }

        public function setSenha($senha) {
            if (strlen($senha) < 6) {
                throw new Exception('Erro. A senha deve ter pelo menos 6 caracteres');
            } else {
                $this->senha = $senha;
            }
        }

        public function getFotoPerfil() {
            return $this->foto_perfil;
        }

        public function setFotoPerfil($foto_perfil) {
            $this->foto_perfil = $foto_perfil;
        }

        public function __toString(): String {
            return "Usuário: " . $this->getId() . " - " . $this->getNome() . " - " . $this->getEmail() . " - " . $this->getSenha();
        }

        public function inserir():Bool{
            $sql = "INSERT INTO Usuario 
                        (nome, email, senha)
                        VALUES (:nome, :email, :senha);";

            $parametros = array(':nome'=>$this->getNome(),
                                ':email'=>$this->getEmail(),
                                ':senha'=>$this->getSenha());

            $resultado = Database::executar($sql, $parametros);
            if ($resultado) {
                // Pega o último ID inserido e salva no objeto
                $this->id = $resultado->lastInsertId();
                return true;
            }
            return false;
        }

        public function alterar():Bool{       
            $sql = "UPDATE Usuario
                    SET nome = :nome, 
                        email = :email,
                        senha = :senha
                    WHERE id = :id;";

            $parametros = array(':id'=>$this->getId(),
                                ':nome'=>$this->getNome(),
                                ':email'=>$this->getEmail(),
                                ':senha'=>$this->getSenha());

            return Database::executar($sql, $parametros) == true;
        }

        public function excluir():Bool{
            $sql = "DELETE FROM Usuario
                        WHERE id = :id;";

            $parametros = array(':id'=>$this->getId());

            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info=''):Array{
            $sql = "SELECT * FROM Usuario";
            switch ($tipo){
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id;"; break; // filtro por ID
                case 2: $sql .= " WHERE nome like :info ORDER BY nome;"; $info = '%'.$info.'%'; break; // filtro por nome
                case 3: $sql .= " WHERE email like :info ORDER BY email;"; $info = '%'.$info.'%'; break; // filtro por email
            }
            $parametros = array();
            if ($tipo > 0)
                $parametros = [':info'=>$info];

            $comando = Database::consultar($sql, $parametros);
            $usuarios = [];
            while ($registro = $comando->fetch()){
                $usuario = new Usuario($registro['id'], $registro['nome'], $registro['email'], $registro['senha']);
                if (isset($registro['foto_perfil'])) {
                    $usuario->setFotoPerfil($registro['foto_perfil']);
                }
                array_push($usuarios,$usuario);
            }
            return $usuarios;
        }

        public static function autenticarLogin($email, $senha) {
            $sql = "SELECT id, nome, email 
                    FROM Usuario 
                    WHERE email = :email AND senha = :senha LIMIT 1;";
            
            $parametros = array(':email' => $email,
                                ':senha' => $senha);

            $comando = Database::consultar($sql, $parametros);
            return $comando->fetch(PDO::FETCH_ASSOC);
        }

        public function salvarFotoPerfil() {
            $sql = "UPDATE Usuario 
                    SET foto_perfil = :foto 
                    WHERE id = :id";
            $parametros = array(':foto' => $this->getFotoPerfil(),
                                ':id' => $this->getId());

            return Database::executar($sql, $parametros) == true;
        }
    }

?>