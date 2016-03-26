<?php

namespace Article\InterfaceSegregation\Step2;

interface DataSource
{
    public function searchForItems(array $searchOptions);
}
