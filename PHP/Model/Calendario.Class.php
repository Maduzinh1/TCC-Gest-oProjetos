<?php
    require_once (__DIR__ . "/../Config/Database.class.php");

    class Calendario {
        private $id;
        private $nome;
        private $descricao;
        private $data_inicio;
        private $data_fim;
        private $status;
        private $urgencia;
        private $idUsuario;

        // construtor da classe
        public function __construct($id, $nome, $descricao, $data_inicio, $data_fim, $status, $urgencia, $idUsuario) {
            $this->setId($id);
            $this->setNome($nome);
            $this->setDescricao($descricao);
            $this->setDataInicio($data_inicio);
            $this->setDataFim($data_fim);
            $this->setStatus($status);
            $this->setUrgencia($urgencia);
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

        public function getDescricao() {
            return $this->descricao;
        }

        public function setDescricao($descricao) {
            if (strlen($descricao) < 5) {
                throw new Exception('Erro. A descrição deve ter pelo menos 5 caracteres');
            } else {
                $this->descricao = $descricao;
            }
        }

        public function getDataInicio() {
            return $this->data_inicio;
        }

        public function setDataInicio($data_inicio) {
            $this->data_inicio = $data_inicio;
        }

        public function getDataFim() {
            return $this->data_fim;
        }

        public function setDataFim($data_fim) {
            $this->data_fim = $data_fim;
        }

        public function getStatus() {
            return $this->status;
        }

        public function setStatus($status) {
            $validStatuses = ['A fazer', 'Fazendo', 'Concluído'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception('Erro. Status inválido. Valores válidos: ' . implode(', ', $validStatuses));
            } else {
                $this->status = $status;
            }
        }

        public function getUrgencia() {
            return $this->urgencia;
        }

        public function setUrgencia($urgencia) {
            $validUrgencies = ['Baixa', 'Média', 'Alta'];
            if (!in_array($urgencia, $validUrgencies)) {
                throw new Exception('Erro. Urgência inválida. Valores válidos: ' . implode(', ', $validUrgencies));
            } else {
                $this->urgencia = $urgencia;
            }
        }

        public function getIdUsuario() {
            return $this->idUsuario;
        }

        public function setIdUsuario($idUsuario) {
            $this->idUsuario = $idUsuario;
        }

        // método mágico para imprimir uma atividade
        public function __toString(): String {
            $str = "Item: ".$this->getId()." - ".$this->getNome()." - ".$this->getDescricao()." - ".$this->getDataInicio()." - ".$this->getDataFim()." - ".$this->getStatus()." - ".$this->getUrgencia()." - ".$this->getIdUsuario();
            return $str;
        }

        // insere uma atividade no banco 
        public function inserir(): Bool {
            $sql = "INSERT INTO Calendario 
                        (nome, descricao, data_inicio, data_fim, status, urgencia, idUsuario)
                        VALUES(:nome, :descricao, :data_inicio, :data_fim, :status, :urgencia, :idUsuario)";
            $parametros = array(':nome'=>$this->getNome(),
                                ':descricao'=>$this->getDescricao(),
                                ':data_inicio'=>$this->getDataInicio(),
                                ':data_fim'=>$this->getDataFim(),
                                ':status'=>$this->getStatus(),
                                ':urgencia'=>$this->getUrgencia(),
                                ':idUsuario'=>$this->getIdUsuario());
            list($conexao, $comando) = Database::executar($sql, $parametros);
            if ($comando) {
                // Pega o último ID inserido e salva no objeto
                $this->id = $conexao->lastInsertId();
                error_log("Calendario inserido com ID: " . $this->id);
                return true;
            }
            return false;
        }

        public static function listar($tipo=0, $info=''): Array {
            $sql = "SELECT * FROM Calendario";
            switch ($tipo) {
                case 0: break;
                case 1: $sql .= " WHERE id = :info ORDER BY id"; break; // filtro por ID
                case 2: $sql .= " WHERE nome like :info ORDER BY nome"; $info = '%'.$info.'%'; break; // filtro por nome
                case 3: $sql .= " WHERE data_inicio = :info ORDER BY data_inicio"; break; // filtro por data de início
            }
            $parametros = array();
            if ($tipo > 0) {
                $parametros = [':info'=>$info];
            }
            list($conexao, $comando) = Database::executar($sql, $parametros);
            $items = [];
            while ($registro = $comando->fetch()) {
                $item = new Calendario($registro['id'], $registro['nome'], $registro['descricao'], $registro['data_inicio'], $registro['data_fim'], $registro['status'], $registro['urgencia'], $registro['idUsuario']);
                array_push($items,$item);
            }
            return $items;
        }

        public function alterar(): Bool {       
            $sql = "UPDATE Calendario
                    SET nome = :nome, 
                        descricao = :descricao,
                        data_inicio = :data_inicio,
                        data_fim = :data_fim,
                        status = :status,
                        urgencia = :urgencia
                    WHERE id = :id";
            $parametros = array(':id'=>$this->getId(),
                            ':nome'=>$this->getNome(),
                            ':descricao'=>$this->getDescricao(),
                            ':data_inicio'=>$this->getDataInicio(),
                            ':data_fim'=>$this->getDataFim(),
                            ':status'=>$this->getStatus(),
                            ':urgencia'=>$this->getUrgencia());
            return Database::executar($sql, $parametros) == true;
        }

        public function excluir(): Bool {
            $sql = "DELETE FROM Calendario
                        WHERE id = :id";
            $parametros = array(':id'=>$this->getId());
            return Database::executar($sql, $parametros) == true;
        }
    }
?>