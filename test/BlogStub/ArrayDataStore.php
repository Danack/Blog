<?php

namespace BlogStub;

use Intahwebz\Form\DataStore;



class ArrayDataStore implements DataStore {

    private $data = [];
    
    public function getData($name, $default, $clearOnRead)
    {
        if (!array_key_exists($name, $this->data)) {
            return $default;
        }

        $value = $this->data[$name];
        
        if ($clearOnRead) {
            unset($this->data[$name]);
        }
        
        return $value;
    }

    public function storeData($name, $data)
    {
        $this->data[$name] = $data;
    }
}

