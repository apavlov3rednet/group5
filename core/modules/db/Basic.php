<?php

namespace Core\Db;

use Core\Main\Settings;
use PDO;
use PDOException;

class Basic
{
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $dbHost;
    private $conn;

    public function __construct(string $dbName = 'default') {
        $this->dbName = $dbName;
        $arSettings = Settings::getDbParams($dbName);
        $this->dbHost = $arSettings['host'];
        $this->dbUser = $arSettings['login'];
        $this->dbPassword = $arSettings['password'];

        $this->connect();
    }

    public function connect() : bool
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->dbHost;dbname=$this->dbName", 
                $this->dbUser, 
                $this->dbPassword
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }   
        catch(PDOException $e) {
            \Core\Main\Logs::add2Log($e->getMessage(), 'message');
            $this->conn = false;
            return false;
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
        $select = join(', ', $params['select']) ?? '*';
        $sql .= $select . ' FROM ' . $table;

        //Фильтр
        if(is_array($params['filter']) && !empty($params['filter'])) {
            foreach($params['filter'] as $key => $value) {
                $filter[] = $key . ' = ?';
                $execute[] = $value;
            }
        }

        if(!empty($filter)) {
            $sql .= ' WHERE '. join(', ', $filter);
        }

        //Сортировка
        if(!empty($params['order'])) {
            $key = array_key_first($params['order']);
            $sql .= ' ORDER BY ' . $key . ' ' . $params['order'][$key];
        }

        //Применение лимитов и стартовой позиции выборки
        if(!empty($params['limit'])) {
            $limit = $params['limit']['rows'] > 0 ? $params['limit']['rows'] : $limit;
            $offset = $params['limit']['offset'] > 0? $params['limit']['offset'] : $offset;

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
            \Core\Main\Logs::add2Log($e->getMessage());
        }
        

        return $result;
    }

}