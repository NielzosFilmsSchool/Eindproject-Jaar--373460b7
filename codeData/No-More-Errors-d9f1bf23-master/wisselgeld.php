<?php
$munten=array (
    "50 euro"=>0,
    "20 euro"=>0,
    "10 euro"=>0,
    "5 euro"=>0,
    "2 euro"=>0,
    "1 euro"=>0,
    "0.50 cent"=>0,
    "0.20 cent"=>0,
    "0.10 cent"=>0,
    "0.05 cent"=>0,
);
$bedrag = doubleval($argv[1]);
$restBedrag = $bedrag;



try {
    if(!$argv[1]) {
        throw new exception ("Er is geen argument meegegeven, geef een getal.");
    }
    if($bedrag<0) {
        throw new exception ("ik kan geen min getal terug geven.");
    }
    else if(is_int($bedrag)==false) {
        throw new exception ("U heeft geen geldig bedrag gegeven. ");
    }

} catch(exception $ex){
    echo("Error: ".$ex->getMessage());
}


foreach($munten as $munt => $hoeveelMunt) {
    $muntFix = doubleval($munt);
    while($restBedrag >= $muntFix) {
        $munten[$munt]++;
        $restBedrag = round($restBedrag-$muntFix, 2);
    }
}

foreach($munten as $munt=>$hoeveelMunt) {
    $muntFix = doubleval($munt);
    if($hoeveelMunt >= 1) {
        $tussen=explode(" ", $munt);
        if($muntFix<1){
            $muntFix = $muntFix*100;
            echo($muntFix." ".$tussen[1]." x ".$hoeveelMunt.PHP_EOL);
        }
        else{
            echo($muntFix." ".$tussen[1]." x ".$hoeveelMunt.PHP_EOL);
        }
    }
}