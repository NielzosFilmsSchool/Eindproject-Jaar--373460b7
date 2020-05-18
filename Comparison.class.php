<?php
/**
 * Compares 2 variables of code
 *
 * Still needs to be able to look through a .php file and make special cases for variable, function names
 */
class Comparison
{
    private $thresh;

    public function __construct($thresh)
    {
        $this->thresh = $thresh;
    }

    public function compare($code_1, $code_2)
    {
        $result = 0;
        $simular = false;

        similar_text($code_1, $code_2, $result);

        if ($result > $this->thresh) {
            $simular = true;
        }

        $output = array($simular, $result);
        return $output;
    }

    public function detectThreshold($code_1, $code_2)
    {
    }

    public function setThreshold($thresh)
    {
        $this->thresh = $thresh;
    }
}