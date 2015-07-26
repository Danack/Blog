<?php


namespace Blog\DB;


use Intahwebz\TableMap\SQLTableMap;


class BlogPostTable extends SQLTableMap  {

    function getTableDefinition() {
        $tableDefinition = array(
            'schema' => 'basereality',
            'tableName' => 'blogPost',
            'columns' => array(
                array('blogPostID', 'primary' => true, 'autoInc' => true ),
                array('contentID', 'type' => 'i', 'foreignKey' => 'content'),
                array('title'),
                array('isActive', 'type' => 'i', 'default' => 0),
                array('blogPostTextID', 'type' => 'i', 'foreignKey' => 'blogPostText'),
            ),
        );

        return $tableDefinition;
    }
}

 