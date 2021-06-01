<?php

namespace App\Core;

class Utilities {
	
	public static $monthNames = array('January', 'February', 'Maret', 'April', 'Mei', 'Juni', 'juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

	public static function formatDate ($datetime) {
		$datetime = explode(' ', $datetime);
		$date = explode('-', $datetime[0]);
		$day = $date[2];
		$month = $date[1];
		$year = $date[0];
		return $day . ' ' . self::$monthNames[$month -1] . ' ' . $year;
	}

	public static function formatRupiah ($number) {
		$number = (float) self::numbersOnly($number);
		return number_format($number, 0, ',', '.');
	}

	public static function numbersOnly($string) {
		return preg_replace('/[^0-9]/', '', $string);
	}

	public static function sanitizeDBInput($i) {
    $i = htmlspecialchars(trim($i));
    $i = addslashes($i);
    $i = str_replace(array(';','--'), '', $i);
    return $i;
  }

	public static function getOrderLinks($columns) {

    if (!is_array($columns) || count($columns) < 1) {
      return array();
    }

    $url = '?';
		$qs = $_SERVER['QUERY_STRING'];
		$qs = explode('&',$qs);
		if (is_array($qs)) {
      for ($i = 0; $i < count($qs); $i++) {
        if (preg_match('/^orderkey=|ordervalue=|' . REWRITE_QS . '=/', $qs[$i]) != 1 && $qs[$i] != '') {
					$url .= $qs[$i]."&";
				}
			}
		}

    $data = array();
    foreach ($columns as $item) {
      $data[$item]['ordervalue'] = isset($_GET['orderkey']) && isset($_GET['ordervalue']) && $_GET['orderkey'] == $item && $_GET['ordervalue'] == 'desc' ? 'asc' : 'desc';
      $data[$item]['classname'] = isset($_GET['orderkey']) && $_GET['orderkey'] == $item ? $data[$item]['ordervalue'] : '';
      $data[$item]['link'] = $url . 'orderkey=' . $item . '&ordervalue=' . $data[$item]['ordervalue'];
    }
    
    return $data;
  
  }
}

?>