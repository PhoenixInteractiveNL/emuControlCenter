<?php
class TimeDiff
{
    static $arBeginTimes = array();

    public static function start($nIndex = 0)
    {
        self::$arBeginTimes[$nIndex] = microtime(1);
    }//public static function start($nIndex = 0)



    public static function stop($nIndex = 0)
    {
        $flEnd = microtime(1);
        return $flEnd - self::$arBeginTimes[$nIndex];
    }//public static function stop($nIndex = 0)



    public static function printStop($nIndex = 0, $strExtraText = null)
    {
        $flDiff = self::stop($nIndex);
        if ($strExtraText == null) {
            echo 'TimeDiff #' . $nIndex . ': ' . $flDiff . "\r\n";
        } else {
            echo 'TimeDiff ' . $strExtraText . ': ' . $flDiff . "\r\n";
        }
    }//public static function printStop($nIndex = 0)
}//class TimeDiff
?>