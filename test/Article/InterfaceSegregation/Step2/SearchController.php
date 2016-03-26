<?php

namespace Article\InterfaceSegregation\Step2;

class SearchController
{
    function search(VariableMap $variableMap, DataSource $dataSource)
    {
        $searchTerms = $variableMap->getVariable('searchTerms');
        $searchOptions = [];
        $searchOptions['keywords'] = explode(',', $searchTerms);

        return $dataSource->searchForItems($searchOptions);
    }
}
