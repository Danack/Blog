<?php

namespace Intahwebz\CSSGenerator;

function strcount($string, $char) {

    $count = 0;

    $letters = mb_str_split($string);

    if ($letters !== FALSE) {
        foreach ($letters as $letter) {
            if ($char == $letter) {
                $count += 1;
            }
        }
    }

    return $count;
}

class CSSVarExtractor {

    private $currentMode = FALSE;

    private $selectorsFound = array();
    private $attributesAndVariables = array();

    private $infoArray = array();


    private static $FINDING_VARS = 'FINDING_VARS';
    private static $FINDING_SELECTORS = 'FINDING_SELECTORS';

    function __construct() {
        $this->setMode(self::$FINDING_SELECTORS); //CSSVarExtractor::FINDING_SELECTORS);
    }

    function saveCurrentInfo() {
        if (count($this->selectorsFound) > 0 &&
            count($this->attributesAndVariables) > 0) {
            $info = array();
            $info['selectors'] = $this->selectorsFound;
            $info['attrsAndVariables'] = $this->attributesAndVariables;

            $this->infoArray[] = $info;
        }
    }

    function setMode($newMode) {
        switch ($newMode) {
            case(self::$FINDING_VARS):{
                $this->attributesAndVariables = array();
                break;
            }

            case(self::$FINDING_SELECTORS):{
                $this->selectorsFound = array();
            }
        }

        $this->currentMode = $newMode;
    }

    function analyzeFile($filename){

        $text = @file_get_contents($filename);

        if ($text === false) {
            throw new \InvalidArgumentException("Could not open file $filename to analyze.");
        }

        $text = preg_replace('!/\*.*?\*/!s', '', $text);

        $text = str_replace(';', "\n", $text);

        $lines = explode("\n", $text);

        foreach($lines as $line){
            $this->analyzeLine($line);
        }
    }

    function analyzeLine($line) {

        switch ($this->currentMode) {

            case(self::$FINDING_VARS):{
                $this->analyzeForVars($line);
                break;
            }

            case(self::$FINDING_SELECTORS):{
                $this->analyzeForSelectors($line);
            }
        }
    }

    function analyzeForSelectors($line) {

        $numberOfLBrackets = strcount($line, '{');
//		$numberOfRBrackets = strcount($line, '}');

        if ($numberOfLBrackets == 1) {
            $this->matchSelectors($line);
            $this->setMode(self::$FINDING_VARS);
        }
        else {
            $this->matchSelectors($line);
        }
    }

    function matchSelectors($line) {
        $line = trim($line);

        $result = preg_match_all("/([^,{]+)[,{]*/i", $line, $matches); //, PREG_SET_ORDER
        if ($result == false) {
            return;
        }
        $matches = $matches[1];

        foreach ($matches as $match) {
            $this->selectorsFound[] = trim($match);
        }
    }

    function analyzeForVars($line) {

        $numberOfLBrackets = strcount($line, '{');
        $numberOfRBrackets = strcount($line, '}');

        if ($numberOfRBrackets == 0) {
            //nothing of interest on this line
        }
        else {
            if ($numberOfRBrackets == 1 && $numberOfLBrackets == 0) {
                //it's the end of a block - change back to finding selectors mode
                $this->saveCurrentInfo();
                $this->setMode(self::$FINDING_SELECTORS);
            }
            else {
                //match all '{.*}' and match '.*:.*' - call the first lot vars, the second lot attributes
                $this->matchAttributesAndVariables($line);
            }
        }
    }

    function matchAttributesAndVariables($line) {
        $lineParts = explode(':', $line);

        if (count($lineParts) != 2) {
            throw new \UnexpectedValueException("Could not split [$line] into property and values.");
        }

        $attribute = trim($lineParts[0]);

        $count = preg_match_all("/\{([^}]*)\}/i", $lineParts[1], $matches); //, PREG_SET_ORDER

        if ($count != false) {
            $matches = $matches[1];
            if (count($matches) > 0) {
                $this->attributesAndVariables[] = array($attribute, $lineParts[1]);
            }
        }
    }

    function writeInfo($filepath) {

        $handle = @fopen($filepath, 'w+');

        if ($handle == FALSE) {
            throw new \InvalidArgumentException("Failed to open file $filepath for writing.");
        }

        $output = "\n\nvar cssAttrVarMap = [\r\n";
        fwrite($handle, $output);

        foreach ($this->infoArray as $info) {

            $selectors = $info['selectors'];

            foreach ($selectors as $selector) {
                $attrsAndVariables = $info['attrsAndVariables'];

                foreach ($attrsAndVariables as $attrsAndVariable) {

                    $attr = $attrsAndVariable[0];
                    $css = $attrsAndVariable[1];

                    $css = str_replace('\'', '\\\'', $css);

                    $output = "";

                    $output .= "'" . $css . "', ";
                    $output .= "'" . $selector . "', ";
                    $output .= "'" . $attr . "', ";

                    $output .= "\r\n";

                    fwrite($handle, $output);
                }
            }
        }

        $output = "];\r\n";
        fwrite($handle, $output);
        fclose($handle);
    }
}

