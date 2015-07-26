<?php

namespace Intahwebz\Content;

use Intahwebz\Utils as Utils;

use BaseReality\Content\BaseRealityConstant;

class CSSVariable{

    public $cssVariableID;

    public $name;
    public $value;
    public $type;

    public function setValue($newValue){
        $this->value = $newValue;
        $this->constrainValue();
    }

    public function constrainValue(){
        if($this->value < 0){
            $this->value = 0;
        }
    }

    public function adjustValue($delta){
        $this->value = /*value*/$this->value + $delta;
        $this->constrainValue();
    }

    public function		getValue(){

        switch($this->type){
            case (BaseRealityConstant::$CSS_VARIABLE_COLOR):{
                return str_pad($this->value, 6, '0', STR_PAD_LEFT);
            }

            case(BaseRealityConstant::$CSS_VARIABLE_SIZE):{
            }
        }

        return $this->value;
    }

    public function dirtyHack() {
        $id = "CSSVariable_".$this->cssVariableID;

        $jsonString = json_encode_object($this);
        $jsonString = addslashes($jsonString);
        $jsString = "jQuery('#".$id."').data('serialized', '".$jsonString."');";
        return $jsString;
    }

}




