<?php

namespace Article\InterfaceSegregation\Step1;

interface DataSource
{
    public function searchForItems(array $searchOptions);
}
