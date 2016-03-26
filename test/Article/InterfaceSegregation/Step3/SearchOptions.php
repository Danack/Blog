<?php


namespace Article\InterfaceSegregation\Step3;

class SearchOptions
{
    public $keywords;
    public $limit;

    public function __construct(array $keywords, int $limit = 1000)
    {
        foreach ($keywords as $keyword) {
            if (is_string($keyword) === false) {
                $message = "Type ".gettype($keyword)." not allowed";
                throw new \InvalidArgumentException($message);
            }
        }
  
        $this->keywords = $keywords;
        $this->limit = $limit;
    }
}
