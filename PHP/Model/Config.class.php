<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Config {
        private $idUsuario;
        private $tema;
        private $banner;

        public function __construct($idUsuario, $tema, $banner) {
            $this->setIdUsuario($idUsuario);
            $this->setTema($tema);
            $this->setBanner($banner);
        }

        public function getIdUsuario() {
            return $this->idUsuario;
        }

        public function setIdUsuario($idUsuario) {
            $this->idUsuario = $idUsuario;
        }

        public function getTema() {
            return $this->tema;
        }

        public function setTema($tema) {
            $this->tema = $tema;
        }

        public function getBanner() {
            return $this->banner;
        }

        public function setBanner($banner) {
            $this->banner = $banner;
        }

        public function inserir(): Bool {
            $sql = "INSERT INTO Config 
                        (idUsuario, tema, banner)
                        VALUES (:idUsuario, :tema, :banner);";
            $parametros = array(':idUsuario'=>$this->getIdUsuario(),
                                ':tema'=>$this->getTema(),
                                ':banner'=>$this->getBanner());
            return Database::executar($sql, $parametros) == true;
        }

        public static function listar($tipo=0, $info=''): Array {
            $sql = "SELECT * FROM Config";
            switch ($tipo){
                case 0: break;
                case 1: $sql .= " WHERE idUsuario = :info ORDER BY idUsuario;"; break; // filtro por ID
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $configs = [];
            while ($registro = $comando->fetch()) {
                $config = new Config($registro['idUsuario'], $registro['tema'], $registro['banner']);
                array_push($configs,$config);
            }
            return $configs;
        }

        // Salvar/atualizar banner
        public function salvarBanner() {
            $sql = "UPDATE Config 
                    SET banner = :banner 
                    WHERE idUsuario = :idUsuario";
            $params = array(':banner' => $this->getBanner(),
                            ':idUsuario' => $this->getIdUsuario());
            return Database::executar($sql, $params) == true;
        }

        public function salvarTema() {
            $sql = "UPDATE Config 
                    SET tema = :tema 
                    WHERE idUsuario = :idUsuario";
            $params = array(':tema' => $this->getTema(),
                            ':idUsuario' => $this->getIdUsuario());
            return Database::executar($sql, $params) == true;
        }
    }
?>