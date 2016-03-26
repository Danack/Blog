<?php

namespace Article\InterfaceSegregation\Step2;

/**
 * Class EchoDataSource
 * Returns the keywords of the search term.
 * @package Article\InterfaceSegregation\Step2
 */
class EchoDataSource implements DataSource
{
    public function searchForItems(array $searchOptions)
    {
        return $searchOptions['keywords'];
    }
}
