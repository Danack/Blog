<?php

namespace Blog\Tool;

//\Intahwebz\Functions::load();
//\Intahwebz\MBExtra\Functions::load();


use Blog\GeneratedSourcePath;
use Intahwebz\DB\Connection;
use Intahwebz\TableMap\TableMapWriter;
use Intahwebz\DBSync\DBSync;



class Upgrade { 

    private $dbConnection;

    /**
     * @var \Intahwebz\DBSync\DBSync
     */
    private $dbSync;

    /**
     * @var $generatedSourcePath
     */
    private $generatedSourcePath;

    function __construct(
        Connection $dbConnection, 
        GeneratedSourcePath $generatedSourcePath,
        DBSync $dbSync
    ) {
        $this->dbConnection = $dbConnection;
        $this->generatedSourcePath = $generatedSourcePath->getPath();
        $this->dbSync = $dbSync;
    }

    function main()
    {
        $this->upgradeDB();
        $this->writeObjectFiles();
        
        
    }

    function  upgradeDB()
    {
        $tablesToUpgrade = $this->getKnownTables();
        //$this->dbSync->processUpgradeForSchema('basereality', $tablesToUpgrade);
    }

    function getKnownTables()
    {
        $knownTables = array(
            new \Blog\DB\BlogPostTable($this->dbConnection),
            new \Blog\DB\BlogPostTextTable($this->dbConnection),  
            new \Blog\DB\ContentTable($this->dbConnection),
            new \Blog\DB\LoginTable($this->dbConnection)
        );

        return $knownTables;
    }

    function writeObjectFiles() 
    {
        $tableMapWriter = new TableMapWriter();

        foreach($this->getKnownTables() as $knownTable){
            $tableMapWriter->generateObjectFile(
                $knownTable,
               $this->generatedSourcePath."/Blog/DTO/",
                "Blog\\DTO"
            );
        }
    }    
}


