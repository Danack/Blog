
So this works.

<!-- end_preview -->

{% set code_to_highlight %}
const ✓ = true;
const ✕ = false;

function ≠($left, $right) {
    return $left != $right;
}

function ≅($left, $right) {
    return ($left > $right - 0.0001) && ($left < $right + 0.0001);
}

function ≡($left, $right) {
    return $left === $right;
}

function ≢($left, $right) {
    return $left !== $right;
}

$a = 1;

$b = 2 - 1;

echo ≡($a, $b).\"\\n\";

echo ≅($a, $a + 0.000001).\"\\n\";

{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}


And the code below:


{% set code_to_highlight %}
$fileHandle = fopen("lolwut.php", 'w');

echo "\xE2\x80\x8B";

fwrite($fileHandle, "<?php\n");
fwrite($fileHandle, "\n");

fwrite($fileHandle, "    $\xE2\x80\x8B = 'magix';\n");
fwrite($fileHandle, "    echo $\xE2\x80\x8B;\n");
fwrite($fileHandle, "\n");

fwrite($fileHandle, "    function fo\xE2\x80\x8Bo(){\n");
fwrite($fileHandle, "        echo 'bar';\n");
fwrite($fileHandle, "    }\n");
fwrite($fileHandle, "\n");

fwrite($fileHandle, "    foo();\n");
fwrite($fileHandle, "?>");
fclose($fileHandle);

{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}


Generates some code that looks like this:


{% set code_to_highlight %}
<?php

    $​ = 'magix';
    echo $​;

    function fo​o() {
        echo 'bar';
    }

    foo();
?>

{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}


Which outputs the following when run

<pre>magix
Fatal error: Call to undefined function foo() </pre>

*Obviously*.
