<?php

namespace Article\InterfaceSegregation\Step1;

use Psr\Http\Message\ServerRequestInterface as Request;


//Example SearchController_before
class SearchController
{
    function search(Request $request, DataSource $dataSource)
    {
        $queryParams = $request->getQueryParams();
        if (!array_key_exists('searchTerms', $queryParams)) {
            throw new ParamsMissingException("Parameter [searchTerms] is not available");
        }
        $searchTerms = $queryParams['searchTerms'];

        $searchOptions = [];
        $searchOptions['keywords'] = explode(',', $searchTerms);
        return $dataSource->searchForItems($searchOptions);
    }
}
//Example end
