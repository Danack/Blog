<?php


namespace Blog\DB;

use Intahwebz\TableMap\SQLTableMap;

class BlogPostTextTable extends SQLTableMap
{
    public function getTableDefinition()
    {
        $tableDefinition = array(
            'schema' => 'basereality',
            'tableName' => 'blogPostText',
            'columns' => array(
               array('blogPostTextID', 'primary' => true, 'autoInc' => true ),
               array('blogPostText', 'type' => 'MEDIUMTEXT'),
            )
        );

        return $tableDefinition;
    }
}
