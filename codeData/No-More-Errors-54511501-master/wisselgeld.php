<?php
$bedrag = $argv[1];
$euroeenheden = array(
    500,
    200,
    100,
    50,
    20,
    10,
    5,
    2,
    1,
    );

$centeenheden = array(
    50,
    20,
    10,
    5,  
    );
function euroBerekenaar($euroeenheden, $restbedrag) {
    foreach($euroeenheden as $value) {
        if ($restbedrag >= $value) {
            $aantalKeer = floor($restbedrag / $value);
            $restbedrag = fmod($restbedrag, $value);
            echo "$aantalKeer X $value euro" . PHP_EOL;
        }
    }
    return $restbedrag;
}

function roundUpToAny($restbedrag) {
    $restbedrag = round(($restbedrag * 100) / 5) * 5 / 100;
    return $restbedrag;
}

function centBerekenaar($centeenheden, $restbedrag) {
    $restbedrag = roundUpToAny($restbedrag);
    $restbedrag *= 100;
    foreach($centeenheden as $value) {
        if ($restbedrag >= $value) {
            $aantalKeer1 = floor($restbedrag / $value);
            $restbedrag = fmod($restbedrag, $value);
            echo "$aantalKeer1 X $value cent" . PHP_EOL;
        }   
    } 
}
try {
    if (is_null($bedrag)) {
        throw new Exception ("Ik moet wel een waarde binnenkrijgen neef.");
    }   
    if (!is_numeric ($bedrag)) {
            throw new Exception ("Je hebt niet de correcte input gegeven.");
        }
    if ($bedrag < 0) {
            throw new Exception ("Geen negatieve getallen pls.");
    }
    $bedrag = floatval($argv[1]);
    var_dump($bedrag);
    $restbedrag = $bedrag;
    $restbedrag = euroBerekenaar($euroeenheden, $restbedrag);
    $restbedrag = centBerekenaar($centeenheden, $restbedrag);     
} catch (Exception $exception) {
    echo "Error: ". $exception -> getMessage ();
}
?>

