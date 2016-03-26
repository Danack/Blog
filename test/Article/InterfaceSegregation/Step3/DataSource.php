<?php

namespace Article\InterfaceSegregation\Step3;

interface DataSource
{
    public function searchForItems(SearchOptions $searchOptions);
}
