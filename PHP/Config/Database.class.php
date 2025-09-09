<?php
    require_once (__DIR__ . "/Config.inc.php");

    class Database{
        private static function abrirConexao() {
            try {
                return new PDO(DSN, USUARIO, SENHA);
            } catch(PDOException $e) {
                echo "Erro ao conectar com o banco de dados: ".$e->getMessage();
            }
        }
        private static function preparar($sql) {
            $conexao = self::abrirConexao();
            $comando = $conexao->prepare($sql);
            return [$conexao, $comando];
        }
        private static function vincularParametros($comando,$parametros) {
            foreach($parametros as $chave=>$valor) {
                $comando->bindValue($chave,$valor);
            }
            return $comando;
        }
        public static function executar($sql, $parametros) {
            list($conexao, $comando) = self::preparar($sql);
            self::vincularParametros($comando, $parametros);
            $comando->execute();
            return [$conexao, $comando];
        }
        public static function getLastInsertId() {
            $conexao = self::abrirConexao();
            return $conexao->lastInsertId();
        }
    }
?>