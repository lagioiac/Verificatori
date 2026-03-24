<?php

class Utility {
	//public $_cnn;
	
	public function __construct(){
		 //$this->MM = new SoggettiManager();
	}	


public function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function NumeroCasuale($lunghezza=2)
    {
        $numeri = "0123456789";
        $out = "";
        for($i = 0; $i < $lunghezza; $i++)
        {
            $out = $out . substr($numeri, rand(0, strlen($numeri) - 1), 1);
        }
        return $out;
    }

function checkEmail($email){
  if(preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email) > 0)
  {
    return true;
  }
  else
  {
    return false;
  }
}
	public function datediff($tipo, $partenza, $fine)
    {
        switch ($tipo)
        {
            case "A" : $tipo = 365;
            break;
            case "M" : $tipo = (365 / 12);
            break;
            case "S" : $tipo = (365 / 52);
            break;
            case "G" : $tipo = 1;
            break;
        }
        $arr_partenza = explode("/", $partenza);
        $partenza_gg = $arr_partenza[0];
        $partenza_mm = $arr_partenza[1];
        $partenza_aa = $arr_partenza[2];
        $arr_fine = explode("/", $fine);
        $fine_gg = $arr_fine[0];
        $fine_mm = $arr_fine[1];
        $fine_aa = $arr_fine[2];
        $date_diff = mktime(12, 0, 0, $fine_mm, $fine_gg, $fine_aa) - mktime(12, 0, 0, $partenza_mm, $partenza_gg, $partenza_aa);
        $date_diff  = floor(($date_diff / 60 / 60 / 24) / $tipo);
        return $date_diff;
    }
    
    function clean($str, $separator = 'dash', $lowercase = true)
    {
        if($separator == 'dash')
        {
            $search = '_';
            $replace = '-';
        } else
        {
            $search = '-';
            $replace = '_';
        }
        $trans = array(
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',
            '&\#\d+?;' => '',
            '&\S+?;' => '',
            '\s+' => $replace,
            '[^a-z0-9\-\._]' => '',
            $replace . '+' => $replace,
            $replace . '$' => $replace,
            '^' . $replace => $replace,
            '\.+' => ''
        );
        $str = trim(strip_tags($str));
        foreach($trans as $key => $val)
            $str = preg_replace("#" . $key . "#i", $val, $str);
        if($lowercase === TRUE)
            $str = strtolower($str);
        return trim(stripslashes($str));
    }
    
    function dataita($datadb){
    	$vetdata = explode(" ",$datadb);
        $data=explode('-',$vetdata[0]);
		$d_ord=$data[2].'/'.$data[1].'/'.$data[0];
		return($d_ord);
    }
    
     function dataeng($datadb){
        $data=explode('/',$datadb);
		$d_ord=$data[2].'-'.$data[1].'-'.$data[0];
		return($d_ord);
    }
    
     function traduciMese($mese){
    	switch ($mese){
    	case 'January': $g = "Gennaio"; break;
    	case 'February': $g = "Febbraio"; break;
    	case 'March': $g = "Marzo"; break;
    	case 'April': $g = "Aprile"; break;
    	case 'May': $g = "Maggio"; break;
    	case 'June': $g = "Giugno"; break;
    	case 'July': $g = "Luglio"; break;
    	case 'August': $g = "Agosto"; break;
    	case 'September': $g = "Settembre"; break;
    	case 'October': $g = "Ottobre"; break;
    	case 'November': $g = "Novembre"; break;
    	case 'December': $g = "Dicembre"; break;
    	default: break;
    	}
    	return $g;
    }
    
    function getMesiSelectForm(){
    	$mesi = array(
		"Gennaio"=>"01-01,01-31",
		"Febbraio" =>"02-01,02-29",
		"Marzo"=>"03-01,03-31",
		"Aprile"=>"04-01,04-30",
		"Maggio"=>"05-01,05-31",
		"Giugno"=>"06-01,06-30",
		"Luglio"=>"07-01,07-31",
		"Agosto"=>"08-01,08-31",
		"Settembre"=>"09-01,09-30",
		"Ottobre"=>"10-01,10-31",
		"Novembre"=>"11-01,11-31",
		"Dicembre"=>"12-01,12-31");
		
		return $mesi;
    }
    
     function traduciMeseByNumber($mese){
    	switch ($mese){
    	case '01': $g = "Gen"; break;
    	case '02': $g = "Feb"; break;
    	case '03': $g = "Mar"; break;
    	case '04': $g = "Apr"; break;
    	case '05': $g = "Mag"; break;
    	case '06': $g = "Giu"; break;
    	case '07': $g = "Lug"; break;
    	case '08': $g = "Ago"; break;
    	case '09': $g = "Set"; break;
    	case '10': $g = "Ott"; break;
    	case '11': $g = "Nov"; break;
    	case '12': $g = "Dic"; break;
    	default: break;
    	}
    	return $g;
    }
    
    function traduciMeseByNumberCompleto($mese){
    	switch ($mese){
    	case '01': $g = "Gennaio"; break;
    	case '02': $g = "Febbraio"; break;
    	case '03': $g = "Marzo"; break;
    	case '04': $g = "Aprile"; break;
    	case '05': $g = "Maggio"; break;
    	case '06': $g = "Giugno"; break;
    	case '07': $g = "Luglio"; break;
    	case '08': $g = "Agosto"; break;
    	case '09': $g = "Settembre"; break;
    	case '10': $g = "Ottobre"; break;
    	case '11': $g = "Novembre"; break;
    	case '12': $g = "Dicembre"; break;
    	default: break;
    	}
    	return $g;
    }
    
     function traduciMeseByMese($mese){
    	switch ($mese){
    	case 'Gen': $g = "1"; break;
    	case 'Feb': $g = "2"; break;
    	case 'Mar': $g = "3"; break;
    	case 'Apr': $g = "4"; break;
    	case 'Mag': $g = "5"; break;
    	case 'Giu': $g = "6"; break;
    	case 'Lug': $g = "7"; break;
    	case 'Ago': $g = "8"; break;
    	case 'Set': $g = "9"; break;
    	case 'Ott': $g = "10"; break;
    	case 'Nov': $g = "11"; break;
    	case 'Dic': $g = "12"; break;
    	default: break;
    	}
    	return $g;
    }
    
    function getGiorno($number){
    	switch ($number){
    	case '0': $g = "Domenica"; break;
    	case '1': $g = "Lunedi"; break;
    	case '2': $g = "Martedi"; break;
    	case '3': $g = "Mercoledi"; break;
    	case '4': $g = "Giovedi"; break;
    	case '5': $g = "Venerdi"; break;
    	case '6': $g = "Sabato"; break;
    	default: break;
    	}
    	return $g;
    }
    
    // // CONVERTE LA DATA DAL FORMATO TIME A QUELLO "UMANO" // 
    function convertiDataTime($dataTime) { 
    	$data = date("j/m/Y", $dataTime); $ora = date("H:i", $dataTime); $ieri = date("j/m/Y", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))); $oggi = date("j/m/Y", mktime(0, 0, 0, date("m"), date("d"), date("Y"))); if ($data == $ieri) $dataOk = "Ieri alle"; elseif ($data == $oggi) $dataOk = "Oggi alle"; else $dataOk = $data; return("$dataOk $ora"); }
	
	function get_web_page( $url ) 
{ 
    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => true,    // return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_ENCODING       => "",       // handle all encodings 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
        CURLOPT_TIMEOUT        => 120,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 

    $ch      = curl_init( $url ); 
    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    //$header['errno']   = $err; 
    //$header['errmsg']  = $errmsg; 
    //$header['content'] = $content; 
    print($header[0]); 
    return $header; 
}  

public function code_to_iso($string){
	
	
	$string = str_replace("��","aaaa",$string);
	$string = str_replace("�","&#144;",$string);
	$string = str_replace("’","&#146;",$string);
	$string = str_replace("˜","&#152;",$string);
	$string = str_replace("©","&#169;",$string);
	$string = str_replace("¬","&#172;",$string);
	$string = str_replace("´","&#180;",$string);
	$string = str_replace("¼","&#188;",$string);
	$string = str_replace("½","&#189;",$string);
	$string = str_replace("À","&#192;",$string);
	$string = str_replace("�","&#193;",$string);
	$string = str_replace("È","&#200;",$string);
	$string = str_replace("É","&#201;",$string);
	$string = str_replace("Ì","&#204;",$string);
	$string = str_replace("�","&#205;",$string);
	$string = str_replace("Ò","&#210;",$string);
	$string = str_replace("Ó","&#211;",$string);
	$string = str_replace("Ù","&#217;",$string);
	$string = str_replace("Ú","&#218;",$string);
	$string = str_replace("�","&#224;",$string);
	$string = str_replace("á","&#225;",$string);
	$string = str_replace("è","&#232;",$string);
	$string = str_replace("é","&#233;",$string);
	$string = str_replace("��","e",$string);
	
	
	$string = str_replace("ì","&#236;",$string);
	$string = str_replace("�","&#237;",$string);
	$string = str_replace("ò","&#242;",$string);
	$string = str_replace("ó","&#243;",$string);
	$string = str_replace("ù","&#249;",$string);
	$string = str_replace("ú","&#250;",$string);
	$string = str_replace("��","&#193;",$string);
	$string = str_replace("’","&#146;",$string);
	$string = str_replace("�","&#193;",$string);
	$string = str_replace("„","&#132;",$string);
	$string = str_replace("Ã","&#195;",$string);
	
	

	
	
	
	
	return $string;
}

function get_web_page_temp( $url ) 
{ 
    $options = array( 
        CURLOPT_RETURNTRANSFER => true,     // return web page 
        CURLOPT_HEADER         => true,    // return headers 
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
        CURLOPT_ENCODING       => "",       // handle all encodings 
        CURLOPT_USERAGENT      => "spider", // who am i 
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect 
        CURLOPT_TIMEOUT        => 10,      // timeout on response 
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
    ); 

    $ch      = curl_init( $url ); 
    curl_setopt_array( $ch, $options ); 
    $content = curl_exec( $ch ); 
    $err     = curl_errno( $ch ); 
    $errmsg  = curl_error( $ch ); 
    $header  = curl_getinfo( $ch ); 
    curl_close( $ch ); 

    //$header['errno']   = $err; 
    //$header['errmsg']  = $errmsg; 
    //$header['content'] = $content; 
    print($header[0]); 
    return $header; 
}  

function currency($from_Currency,$to_Currency,$amount) {
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('"', $rawdata);
    $data = explode(' ', $data['3']);
    $var = $data['0'];
    return round($var,2);
}

function fixEncoding($in_str)
{
	$cur_encoding = mb_detect_encoding($in_str) ;
	if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
		return $in_str;
	else
		return utf8_encode($in_str);
}

}
?>