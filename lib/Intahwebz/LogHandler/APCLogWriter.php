<?php

namespace Intahwebz\LogHandler;

function sortByIndex($index, $a, $b) {
    $aValue = false;
    $bValue = false;
    if (array_key_exists($index, $a)) {
        $aValue = $a[$index];
    }

    if (array_key_exists($index, $b)) {
        $bValue = $b[$index];
    }

    if ($aValue == $$bValue) {
        return 0;
    }

    return ($aValue < $bValue) ? -1 : 1;
}

class APCLogWriter {

    function main() {
        $key = 'Intahwebz\LogHandler\APCHandler';

        $fileHandle = fopen("Log.log.log", "a");

        $loops = 0;

        while ($loops < 10000) {

            $iterator = new \APCIterator(
                'user',
                '/^'.preg_quote($key, '/').'_.*/',
                APC_ITER_ALL, //which fields to populate in the returned data.
                $chunk_size = 1000
            );

            $logEntries = array();

            foreach ($iterator as $logInfo) {
                $logEntries[] = $logInfo;
            }

            if (count($logEntries)) {
                var_dump($logEntries);
            }
            
            usort($logEntries, function($a, $b) {
                    return sortByIndex('ctime', $a, $b);
                });

            $entriesWritten = 0;

            foreach ($logEntries as $logEntry) {
                $written = fwrite($fileHandle, $logEntry['value']);

                $key = $logEntry['key'];
                if ($written) {
                    //  echo "deleting key $key <br/>";
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $deleted = apc_delete($key);
                    //TODO - if deleted == false? do something?
                    fwrite($fileHandle, "Failed to delete log entry $key\n");
                }
                $entriesWritten++;
            }

            if ($entriesWritten) {
                echo "Wrote $entriesWritten log entries.\n";
            }

            usleep(10000); //1/10th of a second
            $loops++;
        }

        fclose($fileHandle);
    }
}

 