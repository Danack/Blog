<?php


namespace Blog\Repository\SQL;

use Blog\Repository\LoginRepo;
use Intahwebz\TableMap\SQLQueryFactory;
use Blog\DB\LoginTable;

class LoginSQLRepo implements LoginRepo
{
    /**
     * @var \Blog\DB\LoginTable
     */
    public $loginTable;

    public function __construct(SQLQueryFactory $sqlQueryFactory, LoginTable $loginTable)
    {
        $this->sqlQueryFactory = $sqlQueryFactory;
        $this->loginTable = $loginTable;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     */
    public function isLoginValid($username, $password)
    {
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->table($this->loginTable)->whereColumn('login', $username);
        $contentArray = $sqlQuery->fetch();
  
        if (count($contentArray) > 0) {
            $content = $contentArray[0];
            if (password_verify($password, $content['login.hash'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     */
    public function createUserLogin($username, $password)
    {
        $data = array(
            'login' => $username,
            'hash' => $password,
        );

        $this->sqlQueryFactory->insertIntoMappedTable($this->loginTable, $data);
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->table($this->loginTable)->whereColumn('login', $username);
        $contentArray = $sqlQuery->fetch();

        return $contentArray;
    }
}
