<?php


namespace Intahwebz\Interlock;

class Interlock {

    public $keyname;

    public $interlockKey;

    function __construct($keyname, $interlockKey) {
        $this->keyname = $keyname;
        $this->interlockKey = $interlockKey;
    }

    function isValid(){
        $validInterlockID = $this->getCurrentInterlockKey();

        if($validInterlockID == $this->interlockKey){
            return $validInterlockID;
        }

        return false;
    }

    function getCurrentInterlockKey() {

        $success = false;
        $interlockKey = apc_fetch($this->keyname, $success);

        if ($success == false) {
            //No key was stored
            return false;
        }

        return $interlockKey;
    }
}