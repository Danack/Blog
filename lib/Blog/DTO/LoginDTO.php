<?php

namespace Blog\DTO;

class LoginDTO {
    public $loginID;
    public $login;
    public $hash;

    public function __construct($loginID = null, $login = null, $hash = null) {
        $this->loginID = $loginID;
        $this->login = $login;
        $this->hash = $hash;
    } 
    function setLoginID($loginID) { 
        $this->loginID = $loginID;
    }

    function setLogin($login) { 
        $this->login = $login;
    }

    function setHash($hash) { 
        $this->hash = $hash;
    }



    /**
     * @param $query \Intahwebz\TableMap\SQLQuery
     * @param $login \Blog\DB\LoginTable
     * @return int
     */
    function insertInto(\Intahwebz\TableMap\SQLQuery $query, \Blog\DB\LoginTable $login){

        $data = convertObjectToArray($this);
        $insertID = $query->insertIntoMappedTable($login, $data);
    $this->loginID = $insertID;

        return $insertID;
    }
}


