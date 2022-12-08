<?php

require __DIR__ . "/../vendor/autoload.php";

use Auryn\Injector;

$input_text = <<< TEXT

blah blah blah

{syntaxHighlighterFile lang='js' file='example_php-fpm.conf'}


blah blah blah

TEXT;

function syntax_highlighter_file($lang, $file)
{
    echo "Ho ho ho $lang, $file \n";
}


$lines = explode("\n", $input_text);

$callbacks = [
  "#{syntaxHighlighterFile lang='(?P<lang>.+)' file='(?P<file>.+)'}#iu" => 'syntax_highlighter_file',
];


$injector = new Injector();


foreach ($lines as $line) {
    foreach ($callbacks as $pattern => $callback) {
        $result = preg_match($pattern, $line, $matches);

        if ($result === 0) {
            continue;
        }

        $params = [];

        // var_dump($result);
        // var_dump($matches);
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[':' . $key] = $value;
            }
        }
        // echo "need to call $callback with " . var_export($params, true) . "\n";
        $injector->execute($callback, $params);
    }
}





