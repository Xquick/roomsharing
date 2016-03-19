<?php
function cw_dates_from_weekday($dayNumber, $format = '%d.')
{
    $_days = array(1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

    $_lastday = date('Y-m-t', strtotime(date('Y-m'))); //posledni den v dnesnim mesici

    $_currday = strtotime("first $_days[$dayNumber] of " . date('Y-m')); //prvni datum pozadovaneho dne v dnesnim mesici

    $_dates = StrFTime($format, $_currday);

    while (date("Y-m-d", strtotime("next $_days[$dayNumber]", strtotime(date('Y-m-d', $_currday)))) <= $_lastday):

        $_currday = strtotime("next $_days[$dayNumber]", strtotime(date('Y-m-d', $_currday))); //dalsi pozadovany den v tomto mesici
        $_dates .= " / " . StrFTime($format, $_currday);
    endwhile;

    return $_dates;
}

/**
 *
 * @param String $string Date and Time in string to be formatted
 * @param String $format
 * @return string
 */
function cw_datetostr($string, $format = '%a %d. %m. %Y', $ofset = NULL)
{
    return strftime($format, strtotime($string . $ofset));
}

function cw_longdates_to_str($startDate, $endDate, $times = true)
{
    $_from = 'od';
    $_to = 'do';

    if (strtotime($endDate) == 0 || strtotime($startDate) == strtotime($endDate)): //jeli akce jednodenni
        $_hours = ($times) ? "$_from %H:%M" : '';
        return StrFTime("%a %d.%m.%Y $_hours", strtotime($startDate)); else: //vicedenni akce
        //ruzny je jen cas, [W d.m.Y od H:i do H:i]
        if (date('Ymd', strtotime($startDate)) == date('Ymd', strtotime($endDate))):
            if ($times)
                return StrFTime("%a %d.%m.%Y $_from %H:%M", strtotime($startDate)) . StrFTime(" $_to %H:%M", strtotime($endDate));
            else
                return StrFTime("%a %d.%m.%Y", strtotime($startDate)); //ruzny jen den v mesici, [W d. - W d.m.Y od H:i do H:i]
        elseif (date('Ym', strtotime($startDate)) == date('Ym', strtotime($endDate))):
            $_date = StrFTime("%a %d.", strtotime($startDate)) . ' - ' . StrFTime("%a %d.%m.%Y", strtotime($endDate));
            if ($times) $_date .= " $_from " . StrFTime("%H:%M", strtotime($startDate)) . " $_to " . StrFTime("%H:%M", strtotime($endDate));

            return $_date; //ruzny je den i mesic ve stejnem roce, [W d.m - W d.m.Y od H:i do H:i]
        elseif (date('Y', strtotime($startDate)) == date('Y', strtotime($endDate))):
            $_date = StrFTime("%a %d.%m.", strtotime($startDate)) . ' - ' . StrFTime("%a %d.%m.%Y", strtotime($endDate));
            if ($times) $_date .= " $_from " . StrFTime("%H:%M", strtotime($startDate)) . " $_to " . StrFTime("%H:%M", strtotime($endDate));

            return $_date; //pres rok, [W d.m.Y - W d.m.Y od H:i do H:i]
        else:
            $_date = StrFTime("%a %d.%m.%Y", strtotime($startDate)) . ' - ' . StrFTime("%a %d.%m.%Y", strtotime($endDate));
            if ($times) $_date .= " $_from " . StrFTime("%H:%M", strtotime($startDate)) . " $_to " . StrFTime("%H:%M", strtotime($endDate));

            return $_date;
        endif;
    endif;
}


function cw_timeDropdown()
{
    $min = array('00', '15', '30', '45');
    $i = $start = 15;
    $first = true;
    while ($first || $i != $start):
        for ($j = 0; $j < count($min); $j++):
            $h = str_pad((int)$i, 2, "0", STR_PAD_LEFT);
            $date["$h:$min[$j]"] = "$h:$min[$j]";
        endfor;
        $i++;
        $first = false;
        if ($i > 23) $i = 0;

    endwhile;

    return $date;
}

function cw_dayDropdown()
{
    for ($i = 1; $i <= 31; $i++):
        $d = str_pad((int)$i, 2, "0", STR_PAD_LEFT);
        $data[$d] = $d;
    endfor;

    return $data;
}

function cw_monthDropdown()
{
    for ($i = 1; $i <= 12; $i++):
        $m = str_pad((int)$i, 2, "0", STR_PAD_LEFT);
        $data[$m] = $m;
    endfor;

    return $data;
}

function cw_yearDropdown()
{
    for ($i = (date('Y') - 1); $i <= (date('Y') + 2); $i++)
        $data[$i] = $i;


    return $data;
}

function calcutateAge($dob)
{

    $dob = date("Y-m-d", strtotime($dob));

    $dobObject = new DateTime($dob);
    $nowObject = new DateTime();

    $diff = $dobObject->diff($nowObject);

    return $diff->y;
}

function formatTime($timestamp)
{
    $timestamp = strtotime($timestamp);
    if ($timestamp > time() - 60 * 60 * 24) {
        return lang("today");
    } else {
        if ($timestamp > time() - 2 * 60 * 60 * 24) {
            return lang("yesterday");
        } else {
            if ($timestamp > time() - 7 * 60 * 60 * 24) {
                return lang("thisweek");
            } else {
                return date("d.m.Y", $timestamp);
            }
        }
    }
}