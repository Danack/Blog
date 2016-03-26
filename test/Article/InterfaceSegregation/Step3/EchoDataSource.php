<?php

namespace Article\InterfaceSegregation\Step3;

/**
 * Class EchoDataSource
 * Returns the keywords of the search term.
 * @package Article\InterfaceSegregation\Step2
 */
class EchoDataSource implements DataSource
{
    public function searchForItems(SearchOptions $searchOptions)
    {
        return $searchOptions->keywords;
    }
}
