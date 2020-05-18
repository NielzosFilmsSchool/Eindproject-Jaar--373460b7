<?php
//including every used class
spl_autoload_register(
    function ($class_name) {
        include $class_name . '.class.php';
    }
);

$text_1 = "Dit is een test!";
$text_2 = "Dit Hello World!";

$comparer = new Comparison(10);
$comparer->detectThreshold($text_1, $text_2);
echo $comparer->compare($text_1, $text_2)[0];