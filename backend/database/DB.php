<?php


class DB
{
    private $pdo = NULL;
    private $sQuery = NULL;
    private $succes = NULL;
    private $bConnected = false;
    private $parameters = NULL;

    private function Connect()
    {
        $dsn = "mysql:dbname=" . SQL_DB . ";host=" . SQL_SERVER;
        $this->pdo = new PDO($dsn, SQL_USER, SQL_PASS);
        $this->pdo->exec("SET NAMES utf8");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->bConnected = true;
    }

    private function Init($query, $parameters = "")
    {
        if (!$this->bConnected) {
            $this->Connect();
        }
        try {
            if (stripos($query, "SELECT") === 0)
            {
                $this->sQuery = $this->pdo->prepare($query);
                $this->bindMore($parameters);
                if (!empty($this->parameters)) {
                    foreach ($this->parameters as $param) {
                        $parameters = explode("", $param);
                        $this->sQuery->bindParam($parameters[0], $parameters[1]);
                    }
                }
                $this->succes = $this->sQuery->execute();
            }
            else
            {
                $qusery = $this->pdo->prepare($query);
                $insert = $qusery->execute($parameters);
                if ( $insert )
                {
                    return true;
                }
                else{
                    return false;
                }
            }
        } catch (PDOException $e) {
            $data["custom"] = array("time" => time());
            $data["ses"] = $_COOKIE;
            $data["post"] = $_POST;
            $data["serv"] = $_SERVER;
            $data["err"] = $this->ExceptionLog($e->getMessage());
            $data["query"] = $query;
            $data["params"] = $parameters;
            echo $data["query"] . "<br/>" . $data["err"];
            echo "<br/>";
            print_r($parameters);
            file_put_contents("error_log.txt", var_export($data, true) . "\r\n\r\n", FILE_APPEND);
            return false;
        }
        $this->parameters = array();
    }
    public function bind($para, $value)
    {
        $this->parameters[sizeof($this->parameters)] = ":" . $para . "" . $value;
    }
    public function bindMore($parray)
    {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }
    public function query($query, $params = NULL, $fetchmode = PDO::FETCH_ASSOC)
    {
        $query = trim($query);
        $response = $this->Init($query, $params);
        if (stripos($query, "SELECT") === 0) {
            return $this->sQuery->fetchAll($fetchmode);
        }
        else
        {
            return $response;
        }
    }
    public function starttransaction()
    {
        $this->pdo->beginTransaction();
    }
    public function commitq()
    {
        $this->pdo->commit();
    }
    public function queryNumRow($query, $params = NULL)
    {
        $query = trim($query);
        $this->Init($query, $params);
        return $this->sQuery->rowCount();
    }
    public function column($query, $params = NULL)
    {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);
        $column = NULL;
        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }
        return $column;
    }
    public function row($query, $params = NULL, $fetchmode = PDO::FETCH_ASSOC)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetch($fetchmode);
    }
    public function single($query, $params = NULL)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetchColumn();
    }
    public function get_last_id()
    {
        return $this->pdo->lastInsertId();
    }
    private function ExceptionLog($message, $sql = "")
    {
        $exception = "Unhandled Exception. <br />";
        $exception .= $message;
        $exception .= "<br /> DEATH FOR YOU.";
        if (!empty($sql)) {
            $exception .= "\r\nRaw SQL : " . $sql;
        }
        return $exception;
    }
}