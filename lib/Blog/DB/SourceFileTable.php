<?php

namespace Blog\DB;

use Intahwebz\TableMap\SQLTableMap;

class SourceFileTable extends SQLTableMap
{
    public function getTableDefinition()
    {
        $tableDefinition = array(
            'schema' => 'basereality',
            'tableName' => 'sourceFile',
            'columns' => array(
                array('sourceFileID', 'primary' => true, 'autoInc' => true ),
                array('filename'),
                array('text', 'type' => 'MEDIUMTEXT'),
            ),
        );

        return $tableDefinition;
    }
}

