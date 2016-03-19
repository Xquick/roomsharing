<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */

/**
 vraci orezany retezec, je li delsi, nez potrebny pocet znaku
 @param string puvodni retezec
 @param count potrebny pocet znaku
 @return string orezany retezec na pocet znaku
 */

function cw_hellip($string, $count, $appendHellip = true) {
	mb_internal_encoding("UTF-8");
	$string = strip_tags(trim($string));
	if(mb_strlen($string) > $count) {
		$substr = mb_substr($string, 0, $count);
		$pos = mb_strrpos($substr, ". ");
		if ($pos === false) {
			$pos = mb_strrpos($substr, " ");
			if ($pos === false) {
				$pos = $count;
			}
		}
		return mb_substr($substr, 0, $pos).($appendHellip ? "&hellip;" : '');
	}
	else {
		return ($string);
	}
}



/**
 * Vrací první písmeno velké,
 * podporuje české znaky
 * @param string $str
 * @return string
 */

function mb_ucfirst($str) {
	return mb_strtoupper(mb_substr($str, 0, 1)) .mb_substr($str, 1);
}

function serverUri() {
	$_result = explode('?', $_SERVER['REQUEST_URI']);
	return $_result[0];
}

function orderUrl($page, $order) {
    if($page == FALSE)
        return "?order=$order";
    return "?page=$page&amp;order=$order";
}


/**
 prepisuje retezec na url adresu
 @param address retezec
 @return address prepsana adresa
 **/
function seo_url($address){

	// odstraneni mezer a dikaritiky
	$address = str_replace(
	Array(" ","á","č","ď","é","ě","í","ľ","ň","ó","ř","š","ť","ú","ů","ý","ž","Á","Č","Ď","É","Ě","Í","Ľ","Ň","Ó","Ř","Š","Ť","Ú","Ů","Ý","Ž", "&") ,
	Array("-","a","c","d","e","e","i","l","n","o","r","s","t","u","u", "y","z","A","C","D","E","E","I","L","N","O","R","S","T","U","U","Y","Z", "a") ,
	$address);

	// prevod na mala pismena
	$address = strtolower ($address);

	// nahradi nealfanumericke znaky pomlckou
	$re = "/[^[:alpha:][:digit:]]/";
	$replacement = "-";
	$address = preg_replace ($re, $replacement, $address);

	// odstraneni prebytecnych pomlcek
	$re = "/[-]+/";
	$replacement = "-";
	$address = preg_replace ($re, $replacement, $address);

	// odstrani pomlcek ze zacatku a konce retezce
	$address = trim ($address, "-");

	return $address;
}

function cw_numberformat($number, $zeros) {
    return str_pad((int) $number, $zeros, "0", STR_PAD_LEFT);
}

?>