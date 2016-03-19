<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pocita hodnotu hodnoceni holky
 * @param double $sum soucet hodnoceni
 * @param int $voters pocet hodnoticich
 * @return double
 */
function cw_get_rating($sum, $voters) {
    if($voters == 0) return 0;
    
    $rating = $sum / $voters;
  
    return number_format(round($rating*2)/2, 1, '.', '');
}

?>
