<?php


namespace Blog;

use Danack\Console\Application;
use Danack\Console\Command\Command;
use Danack\Console\Input\InputArgument;

class ConsoleApplication extends Application
{
    /**
     * Creates a console application with all of the commands attached.
     * @return ConsoleApplication
     */
    public function __construct()
    {
        parent::__construct("Blog", "1.0.0");
        
        $this->addEnvCommand();
        $this->addUpgradeCommand();
        $this->addDebugCommand();
    }

    private function addEnvCommand()
    {
        $envWriteCommand = new Command('genEnvSettings', 'Blog\Config\EnvConfWriter::writeEnvFile');
        $envWriteCommand->setDescription("Write an env setting bash script.");
        $envWriteCommand->addArgument(
            'env',
            InputArgument::REQUIRED,
            'Which environment the settings should be generated for.'
        );
        $envWriteCommand->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'The file name that the env settings should be written to.'
        );
        $this->add($envWriteCommand);
    }

    public function addUpgradeCommand()
    {
        $upgradeCommand = new Command('upgrade', ['Blog\Tool\Upgrade', 'main']);
        $upgradeCommand->setDescription('Upgrade the database to the latest defined schema');
        $this->add($upgradeCommand);
    }
    
    public function addDebugCommand()
    {
        $fn = function() {
            echo "Yes, CLI is working.\n";
        };
        
        $debugCommand = new Command('debug', $fn);
        $debugCommand->setDescription('Check if the CLI app is working.');
        $this->add($debugCommand);
    }
}
