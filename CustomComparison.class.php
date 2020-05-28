<?php
/**
 * Compares 2 array's of code and outputs a % and boolean
 *
 * Currently checks all lines that are simular
 */
class CustomComparison
{
    private $thresh;

    public function compare($code_1, $code_2)
    {
        $result = 0;
        $simular = false;

        $linesFound = 0;
        foreach ($code_2 as $line_2) {
            if (in_array($line_2, $code_1)) {
                $linesFound++;
            }
        }
        
        echo "Simular lines found = $linesFound";
        echo PHP_EOL;

        $result = (100 / count($code_1) * $linesFound);
        $result = round($result*10)/10;

        if ($result > $this->thresh) {
            $simular = true;
        }

        return array($simular, $result);
    }

    public function detectThreshold($code_1, $code_2)
    {
        $len_diff = abs(count($code_1) - count($code_2));

        echo "Length diff = ".$len_diff.PHP_EOL;

        $new_thresh = $this->map($len_diff, 0, 500, 50, 100);
        $new_thresh = round($new_thresh*10)/10;
        
        echo "New threshold = " . $new_thresh.PHP_EOL;

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