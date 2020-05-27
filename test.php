<?php
//including every used class
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);

//Variable 1 with code inside
$text_1 = file_get_contents(
    "Test_files\script_2.js"
);

//Variable 2 with code inside
$text_2 = file_get_contents(
    "Test_files\script_2 - Copy.js"
);

//Showing the length of the 2 variables
echo "File 1 length: ".strlen($text_1).PHP_EOL;
echo "File 2 length: ".strlen($text_2).PHP_EOL;
echo PHP_EOL;

//New instance of the comparison class
$comparer = new Comparison();

//Automatically detect threshold
$comparer->detectThreshold($text_1, $text_2);

//Calculate dupe percentage and saving into variable
$result = $comparer->compare($text_1, $text_2);

//Showing the result
echo PHP_EOL;
echo "Dupe: ".($result[0] ? "Yes" : "No").PHP_EOL;
echo "Percentage: ".$result[1].PHP_EOL;