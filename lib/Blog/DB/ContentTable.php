<?php


namespace Blog\DB;

use Intahwebz\TableMap\SQLTableMap;

class ContentTable extends SQLTableMap
{
    public function getTableDefinition()
    {
        $tableDefinition = array(
            'schema' => 'basereality',
            'tableName' => 'content',
            'columns' => array(
                array('contentID', 'primary' => true, 'autoInc' => true ),
                array('datestamp', 'type' => 'd'),
            ),
        );

        return $tableDefinition;
    }
}
