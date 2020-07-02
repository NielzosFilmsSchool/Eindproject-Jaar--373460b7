<?php
    
$munten = [
    "50 euro"=>0,
    "20 euro"=>0,
    "10 euro"=>0,
    "5 euro"=>0,
    "2 euro"=>0,
    "1 euro"=>0,
    "0.5 cent"=>0,
    "0.2 cent"=>0,
    "0.1 cent"=>0,
    "0.05 cent"=>0
];

try {
    if(count($argv) < 2) throw new Exception("Je hebt geen bedrag mee gegeven");
    if(!is_numeric($argv[1])) throw new Exception("Je hebt geen geldig bedrag mee gegeven");
    
    $input = doubleval($argv[1]);
    $geld = (round($input*2, 1)/2);

    if($geld == 0) throw new Exception("Geen wisselgeld");
    if($geld < 0) throw new Exception("Ik kan geen negatief bedrag wisselen");

    foreach($munten as $munt=>$hoeveelheid){
        $waarde = explode(" ", $munt);
        $munten[$munt] = floor($geld/$waarde[0]);
        $geld = fmod($geld, $waarde[0]);
    }
    
    foreach($munten as $munt=>$hoeveelheid) {
        if($hoeveelheid > 0) echo($hoeveelheid." X ".$munt.PHP_EOL);
    }
} catch (Exception $e){
    echo($e->getMessage());
}

?>