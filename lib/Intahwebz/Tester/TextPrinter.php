<?php


namespace Intahwebz\Tester;

class TextPrinter {

    /**
     * @var URLResult[]
     */
    private $results;

    function __construct(array $results, $baseURL) {
        $this->results = $results;
        $this->baseURL = $baseURL;
    }

    function output($outputStream) {
        fwrite($outputStream, "Status".", ");
        fwrite($outputStream, "Path".", ");
        fwrite($outputStream, "Referrer".", ");
        fwrite($outputStream, "Message"."\n");

        foreach ($this->results as $result) {
            if ($result) {
                if ($result->getStatus() != 200) {
                    fwrite($outputStream, $result->getStatus());
                    fwrite($outputStream, ", ");
                    fwrite($outputStream, $result->getPath());
                    fwrite($outputStream, ", ");
                    fwrite($outputStream, $result->getReferrer());
    
                    if ($result->getStatus() != 200) {
                        fwrite($outputStream, ", ");
                        fwrite($outputStream, $result->getErrorMessage());
                    }

                    fwrite($outputStream, "\n");
                }
            }
        }

        fprintf($outputStream, "There were %d URLs scanned succesfully.", count($this->results));
    }
}
