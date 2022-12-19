<?php
if (! function_exists('is_day')) {
    function is_day():bool{
        $sun = new AurorasLive\SunCalc(new DateTime(), 59.4428,26.2139);
        $sun = $sun->getSunTimes();
        $sunrise = $sun['sunrise']->format('H:i');
        $sunset = $sun['sunset']->format('H:i');
        
        $current_time = new DateTime();
        $current_time = $current_time->format('H:i');
        $date1 = DateTime::createFromFormat('H:i', $current_time);
        $date2 = DateTime::createFromFormat('H:i', $sunrise);
        $date3 = DateTime::createFromFormat('H:i', $sunset);
        return $date1 > $date2 && $date1 < $date3;
    }   
}
