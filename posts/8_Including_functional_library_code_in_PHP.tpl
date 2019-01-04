
PHP has a pretty good system for including class based code that uses the class namespace and name to define the possible file names that should be searched to auto(matically)-load the class. However PHP has a really crappy system for including functional code, the [require/include](http://php.net/manual/en/function.require-once.php) system.

<!-- end_preview -->

It's crappy because:

- It always causes a file hit. Neither APC or OPCache can intercept it's calls and so every require causes a hit to the file system.

- It requires hard coding of paths to find the exact file.

- The syntax just sucks*.


There is an alternative solution to including functional code in PHP (which also sucks just not quite as much).

1. Define a class in a namespace for the file you want to be able to include.

2. Give that class a static function that does nothing.

3. Put the functional code into a 2nd namespace block, which is set to the global namespace.

3. Call the classes static function to require the functional code.

An example is a library file for adding extra multi-byte strings which haven't been implemented in core PHP:



{% set code_to_highlight %}

namespace Intahwebz\\MBExtra{
    class Functions{
        public static function load(){
        }
    }
}

namespace {

    function mb_ucwords($string){
        return mb_convert_case($string, MB_CASE_TITLE);
    }

    function mb_lcfirst($str) {
        return mb_strtolower(mb_substr($str,0,1)).mb_substr($str,1);
    }

    function mb_strcasecmp($str1, $str2, $encoding = null) {
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }

        return strcmp(mb_strtoupper($str1, $encoding), mb_strtoupper($str2, $encoding));
    }
    //And all the other functions are available at https://github.com/Danack/mb_extra
}
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}



It is now possible to pull the functions defined in the library in to any other file by calling:

```
\\Intahwebz\\MBExtra\\Functions::load();
```

Which will load the PHP file that defines that class into the current process, and all the functions defined in the global namespace will be available. Don't get me wrong, *this still sucks*, but it does have the advantages that:

- The syntax for including functional code is now the same as including class based code, so there's one less thing to think about.

- Composer (or any other autoloader you might use) will fixup all the dependencies for the file paths.

- Because you're just calling a class function both APC and OPCache are able to cache the file avoiding both the file system access and compiling the code again.

Until someone suggests a better way of being able to manage functional code dependencies between projects, I'll be using the above hack to manage those depdendencies.


## * Example of the syntax sucking

I have a library of extra multi-byte versions of string functions which gets installed by Composer into:

$PROJECT_DIR/vendor/intahwebz/mb_extra/src/Intahwebz/MBExtra/Functions.php

which I then want to include in another library file which Composer installs in:

$PROJECT_DIR/vendor/danack/PHPTemplate/src/Intahwebz/PHPTemplate/Converter/TemplateParser.php

The require line I would need to use the mbextra library file in the PHPTemplate library would be:

```require_once __DIR__."/../../../../../../intahwebz/mb_extra/src/Intahwebz/MBExtra/Functions.php"```

which just *suuuuuuucks*.


