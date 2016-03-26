<?php

namespace Article\InterfaceSegregation\Step3;

class SearchController
{
    function search(VariableMap $variableMap, DataSource $dataSource)
    {
        $searchTermsString = $variableMap->getVariable('searchTerms');
        $searchTermsArray = explode(',', $searchTermsString);
        $searchOptions = new SearchOptions($searchTermsArray, 50);

        return $dataSource->searchForItems($searchOptions);
    }
}
