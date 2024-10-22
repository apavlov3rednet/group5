<?php

namespace Db;

use Main\Settings;
use PDO;
use PDOException;

class Basic
{
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $dbHost;
    private $conn;
    private $settings;
    private $database;

    public function __construct(string $dbName = 'default') {
        $this->settings = new Settings();
        $arSettings = $this->settings->getDbParams($dbName);

        $this->dbName = $dbName;
        $this->dbHost = $arSettings['host'];
        $this->dbUser = $arSettings['login'];
        $this->dbPassword = $arSettings['password'];
        $this->database = $arSettings['database'];

        $this->connect();
    }

    public function connect() : bool
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->dbHost;dbname=$this->database", 
                $this->dbUser, 
                $this->dbPassword
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }   
        catch(PDOException $e) {
            \Main\Logs::add2Log($e->getMessage(), 'message');
            $this->conn = false;
            return false;
        }     
    }

    private function prepareFilter($arFilter, &$sql, &$filter, &$execute): void   
    {
        if(!empty($arFilter)) {
            foreach($arFilter as $key => $value) {
                $filter[] = $key . ' = ?';
                $execute[] = $value;
            }
        }

        if(!empty($filter)) {
            $sql .= ' WHERE '. join(', ', $filter);
        }
    }

    /**
     * Summary of getList
     * @param string $table
     * @param array $params = [
     *  'select' => ['*', 'NAME', 'PASSWORD'], // имена полей которые мы будем выбирать
     *  'filter' => ['GROUP' => 5, 'AGE' => 10], //фильтр ключ-значение
     *  'order' => [ 'SORT' => 'ASC' ], //сортировка ASC - по возрастанию, DESC - по убыванию
     *  'limit' => [
     *      'offset' => 1, //Текущая позиция, с которой начинается выборка
     *      'rows' => 5 //Количество элементов которые мы будем выбирать
     *  ]
     * ]
     * 
     * 
     * @return array
     */
    public function getList(string $table, array $params = []) {
        if(!$this->conn)
            return [];       

        //Значения по умолчанию
        $filter = [];   //Подготовленный фильтр для запроса в БД
        $execute = [];  //Параметры фильтра
        $limit = 100;   //Количество одновременно выбираемых записей
        $offset = 0;    //С какой записи начинаем выборку
        $result = [];   //Результирующий массив

        //Основная выборка из таблицы
        $sql = 'SELECT ';
        $select = (!empty($params['select'])) ? join(', ', $params['select']) : '*';
        $sql .= $select . ' FROM ' . $table;

        //Фильтр
        if(!empty($params['filter']))
            $this->prepareFilter($params['filter'], $sql, $filter, $execute);
        
        //Сортировка
        if(!empty($params['order'])) {
            $key = array_key_first($params['order']);
            $sql .= ' ORDER BY ' . $key . ' ' . $params['order'][$key];
        }

        //Применение лимитов и стартовой позиции выборки
        if(!empty($params['limit'])) {
            $limit = (!empty($params['limit']['rows'])) ? $params['limit']['rows'] : $limit;
            $offset = (!empty($params['limit']['offset'])) ? $params['limit']['offset'] : $offset;

            $sql .= ' LIMIT ' . $limit;
            $sql .= ' OFFSET ' . $offset;
        }

        try {
            $request = $this->conn->prepare($sql);
            $request->execute($execute);
    
            $response = $request->fetchAll(PDO::FETCH_ASSOC);
    
            foreach($response as $row) {
                $result[] = $row;   
            }
        }
        catch(PDOException $e) {
            \Main\Logs::add2Log('List: ' .$e->getMessage());
        }
        

        return $result;
    }

    /**
     * Summary of add
     * @param string $table
     * @param array $arFields = [
     *  'KEY' => 'VALUE',
     *  'KEY2' => 'VALUE2', ....
     * ]
     * @return mixed
     */
    public function add(string $table, array $arFields)
    {
        try {
            //INSERT INTO `users` (`ID`, `LOGIN`, `PASSWORD`) VALUES (:ID, :LOGIN, :PASSWORD)
            $fields = join(', ', array_keys($arFields)); //ID, LOGIN, PASSWORD
            $prepValues = ':' . join(', :', array_keys($arFields)); // :ID, :LOGIN, :PASSWORD

            $sql = 'INSERT INTO ' . $table . '(' . $fields . ') VALUES ('. $prepValues .')';
            //INSERT INTO `users` (`ID`, `LOGIN`, `PASSWORD`) VALUES (:ID, :LOGIN, :PASSWORD)

            $request = $this->conn->prepare($sql);

            foreach($arFields as $key => $value) {
                $request->bindValue(':' . $key, $value);
            }

            if($request->execute()) {
                return $this->conn->lastInsertId('ID');
            }
            else {
                \Main\Logs::add2Log('Add fail');
                return false;
            }
        }
        catch(PDOException $e) {
            \Main\Logs::add2Log('Add: ' . $e->getMessage());
            return false;
        }
    }

    public function delete(string $table, array $where) {
        try {
            $filter = [];
            $execute = [];
            $sql = 'DELETE FROM ' . $table;
            $this->prepareFilter($where, $sql, $filter, $execute);
            //DELETE FROM table WHERE value = ?

            $request = $this->conn->prepare($sql);
            if($request->execute($execute)) {
                return true;
            }
            else {
                \Main\Logs::add2Log('Error delete');
                return false;
            }
        }
        catch(PDOException $e) {
            \Main\Logs::add2Log('Deelte: '. $e->getMessage());
            return false;
        }
    }

    public function update(string $table, int $id, array $arFileds) {
        try{
            $filter = [];
            $execute = [];
            $arSql = [];
            //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD WHERE `users`.`ID` = ?;

            $sql = 'UPDATE ' . $table . ' SET ';
            foreach( $arFileds as $key => $value ) {
                $arSql[] = $key . ' = :' . $key; //LOGIN = :LOGIN
            }

            if(!empty($arSql)) {
                $sql .= join(', ', $arSql); //LOGIN = :LOGIN, PASSWORD = :PASSWORD
                //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD
            }

            $this->prepareFilter(['ID' => $id], $sql, $filter, $execute);
            //UPDATE `users` SET `LOGIN` = :LOGIN, `PASSWORD` = :PASSWORD WHERE `users`.`ID` = ?;

            $request = $this->conn->prepare($sql);

            foreach($arFileds as $key => $value ) {
                $request->bindValue(':'.$key, $value);
            }

            if( $request->execute($execute) ) {
                return true;
            }
            else {
                \Main\Logs::add2Log('error update');
                return false;
            }
        }
        catch(PDOException $e) {
            \Main\Logs::add2Log('update : '. $e->getMessage());
            return false;
        }
    }
}