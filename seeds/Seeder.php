<?php

namespace Netinteractive\Seed;

/**
 *
 * @package    Seeder
 * @version    1.0.0
 * @author     Kamil Pietrzak
 */
 
class Seeder extends \Seeder
{
    /**
     * Metoda pokazuje progressbar na ekranie
     * Aby funkcja działała prawidłowo musi przyjąć na poczatku $current = 1
     * 
     * @param $currenct akualny index
     * @param $max najwyzszy index
     * #param $title tytuł akcji
     */
    public function progressBar($current, $max, $title = null)
    {
        exec('tput cols 2>&1',$out,$ret);
        
        if(isset($out[0]) && $out[0] > 0){
            $progressWidth = $out[0];
        }
        else
        {
            $progressWidth=80-23-10;
        }
        
        //var_dump($out,$ret);
        $bar = '';

        if ($current == 1 && $title)
        {
            echo " --- $title($max):\n";
        }
        $percent = $current / $max * 100;

        if ($percent < 10)
            $bar .= ' ';

        if ($percent < 100)
            $bar .= ' ';

        $bar .= number_format($percent, 2) . '% - ';
        $bar .= '|';
        $checkedSymbols = $progressWidth * $current / $max;

        for ($x = 0; $x < $progressWidth; $x++)
        {
            if ($x < $checkedSymbols)
            {
                $bar .= "\033[0;32m-\033[0m";
            }
            else
            {
                $bar .= ".";
            }
        }
        $bar .= "| - $current/$max";

        echo "\r\r $bar";

        if ($current == $max)
        {
            echo "\n";
        }
    }
}
