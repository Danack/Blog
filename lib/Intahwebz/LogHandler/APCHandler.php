<?php


namespace Intahwebz\LogHandler;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;

use Monolog\Handler\AbstractProcessingHandler;



class APCHandler extends AbstractProcessingHandler
{
    private $prefix;

    # redis instance, key to use
    public function __construct($prefix, $level = Logger::DEBUG, $bubble = true)
    {
        $this->prefix = $prefix;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        $index = apc_inc($this->prefix, 1, $success);
        if ($success == false) {
            apc_store($this->prefix, 0);
            $index = 0;
        }
        $pid = getmypid();
        $key = $this->prefix .'_'.time()."_".$index.'_'.$pid;
        apc_store($key, $record["formatted"]);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new LineFormatter();
    }
}
