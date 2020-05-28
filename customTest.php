<?php
//including every used class
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);

$text_1 = array();
$handle = fopen("Test_files\script_2.js", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        array_push($text_1, $line);
    }

    fclose($handle);
} else {
    // error opening the file.
}

$text_2 = array();
$handle = fopen("Test_files\script_2 - Copy.js", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        array_push($text_2, $line);
    }

    fclose($handle);
} else {
    // error opening the file.
}

echo "File 1 length: ".count($text_1).PHP_EOL;
echo "File 2 length: ".count($text_2).PHP_EOL;
echo PHP_EOL;

$comparer = new CustomComparison();

$comparer->detectThreshold($text_1, $text_2);

$result = $comparer->compare($text_1, $text_2);

echo PHP_EOL;
echo "Dupe: ".($result[0] ? "Yes" : "No").PHP_EOL;
echo "Percentage: ".$result[1].PHP_EOL;