<?php

function str_include(string $str, string $needle): bool
{
    return (strpos($str, $needle) !== false);
}

$_ = function (string $string):string {
    return $string;
};

define("DEV", str_include($_SERVER['CONTEXT_DOCUMENT_ROOT'], "htdocs") ? true : false);

class Mysql
{
    public const DB_NAME = "luxonomy";
    public const HOST = "localhost";
    public const UTF = "utf8";
    public const USER = "root";
    public const PASS = "root";

    public function __construct()
    {
        global $_;
        $dsn = "mysql:dbname={$_(self::DB_NAME)};host={$_(self::HOST)};charset={$_(self::UTF)}";
        try {
            $pdo = new PDO($dsn, self::USER, self::PASS, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$_(self::UTF)}"]);
        } catch (Exception $e) {
            echo "Connection Error";
            die();
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->dbh = $pdo;
    }

    private function isJson(?string $string):bool
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    private function toArray($array):array
    {
        return array_map(function ($row) {
            foreach ($row as $key => $val) {
                if ($this->isJson($val)) {
                    $row[$key] = json_decode($val, true);
                }
            }
            return $row;
        }, $array);
    }

    public function select(string $sql): array
    {
        $stmt = $this->dbh->query($sql);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->toArray($items);
    }

    public function prepare(string $sql, array $params): array
    {
        $stmt = $this->dbh->prepare($sql);
        $i = 1;
        foreach ($params as $param) {
            $stmt->bindValue($i, $param, PDO::PARAM_STR);
            ++$i;
        }
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->toArray($items);
    }
}
