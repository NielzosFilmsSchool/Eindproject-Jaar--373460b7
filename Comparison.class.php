<?php
/**
 * Compares 2 variables of code and outputs a % and boolean
 *
 * Still needs to be able to look through a .php file
 * and make special cases for variable, function names
 */
class Comparison
{
    private $thresh;

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
        $len_diff = abs(strlen($code_1) - strlen($code_2));

        echo "length diff = ".$len_diff.PHP_EOL;

        $new_thresh = $this->map($len_diff, 0, 1000, 50, 100);
        echo "new threshold = " . $new_thresh.PHP_EOL;

        $this->thresh = $new_thresh;
    }

    private function map($value, $fromLow, $fromHigh, $toLow, $toHigh)
    {
        $fromRange = $fromHigh - $fromLow;
        $toRange = $toHigh - $toLow;
        $scaleFactor = $toRange / $fromRange;

        $tmpValue = $value - $fromLow;
        
        $tmpValue *= $scaleFactor;

        return $tmpValue + $toLow;
    }

    public function setThreshold($thresh)
    {
        $this->thresh = $thresh;
    }
}