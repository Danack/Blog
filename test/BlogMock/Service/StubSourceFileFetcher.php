<?php

namespace BlogMock\Service;

use Blog\Service\SourceFileFetcher;


class StubSourceFileFetcher implements SourceFileFetcher {

    /**
     * @param $srcFile
     * @return string
     */
    function fetch($srcFile)
    {
        // TODO: Implement fetch() method.
        return __DIR__."/../../fixtures/sourceFile.php.text";
    }
}

