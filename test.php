<?php
//including every used class
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);

$text_1 = file_get_contents(
    "Test_files\script_2.js"
);

$text_2 = file_get_contents(
    "Test_files\script_2 - Copy.js"
);

echo "File 1 length: ".strlen($text_1).PHP_EOL;
echo "File 2 length: ".strlen($text_2).PHP_EOL;
echo PHP_EOL;

$comparer = new Comparison();

//Automatically detect threshold
$comparer->detectThreshold($text_1, $text_2);

//Calculate dupe percentage
$result = $comparer->compare($text_1, $text_2);

echo PHP_EOL;
echo "Dupe: ".($result[0] ? "Yes" : "No").PHP_EOL;
echo "Percentage: ".$result[1].PHP_EOL;