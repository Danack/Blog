<?php

namespace Blog\DB;

use Intahwebz\TableMap\SQLTableMap;

class LoginTable extends SQLTableMap
{
    public function getTableDefinition()
    {
        $tableDefinition = array(
            'schema' => 'basereality',
            'tableName' => 'login',
            'columns' => array(
                array('loginID', 'primary' => true, 'autoInc' => true ),
                array('login'), //TODO - needs a unique
                array('hash', 'type' => 'hash'),
            ),
        );

        return $tableDefinition;
    }
}
