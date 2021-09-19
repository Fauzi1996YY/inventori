<?php

namespace App\Core;

class Paging {
	
	private static $perpage = 30;
	private static $perstage = 5;
	private static $numpages;
	
	public static function setPerpagePaging ($int) {
		self::$perpage = $int;
	}
	
	public static function setPerstagePaging ($int) {
		self::$perstage = $int;
	}
	
	private static function getCurrentPage ($current_query_string) {
		if ($current_query_string != '' && is_numeric($current_query_string)) {
			$ex[0] = $current_query_string;
			$ex[1] = ceil((int) $current_query_string / self::$perstage);
		}
		else {
			$ex = array('1','1');
		}
		return $ex;
	}
	
	public static function getLimit ($current_query_string) {
		$ex = self::getCurrentPage($current_query_string);
		$offset = ($ex[0] - 1) * self::$perpage;
		return $offset.", ".self::$perpage;
	}
	
	public static function getTotalData ($sql, $col = '*') {
		$sql = trim($sql);
		$sql = preg_replace('~limit.*?$~sD', '', $sql); /* kalo ada limit hasilnya gak bener */
		$sql = 'select count(*) from (' . $sql . ') as `total_rows`';
		
		$conn = Database::getPDO();
		try {
			$stmt = $conn->query($sql);
			$r = $stmt->fetchColumn();
			$stmt->closeCursor();
			return $r;
		} catch(\PDOException $e) {
			return false;
		}
		
	}
	
	public static function getLastPage () {
		$last_page = self::$numpages;
		$last_page = (int)$last_page < 1 ? '1' : $last_page;
		return $last_page;
	}
	
	public static function getLinks ($total, $current_query_string) {
		$ex = self::getCurrentPage($current_query_string);
		$perpage = self::$perpage;
		$perstage = self::$perstage;
		$numpages = self::$numpages = ceil($total / $perpage);
		$numofstage = ceil($numpages / $perstage);
		
		if (((int) $ex[0] > $numpages && $total > 0) || (int) $ex[0] < 1) {
			return false;
		}
		if ($total <= $perpage) {
			return ' '; # it means the function returns true and prints nothing rather than 1
		}
		
		/*
		if ((int) $ex[0] > $numpages || $total <= $perpage) {
			return null;
		}
		*/
		
		/*
    add a question mark as a start of the query string
		we will split the query string and remove the 'page' and 'url' variables from it.
    */

		$url = '?';
		$qs = $_SERVER['QUERY_STRING'];
		$qs = explode('&',$qs);
		if (is_array($qs)) {
      for ($i = 0; $i < count($qs); $i++) {
        if (preg_match('/^page=|appsuniquequerystring=/', $qs[$i]) != 1 && $qs[$i] != '') {
					$url .= $qs[$i]."&";
				}
			}
		}
		
		if ($ex[1] > 1) {
			$backstage = $ex[1] - 1;
			$backpage = ($perstage * $ex[1] - $perstage + 1 - $perstage) + ($perstage - 1);
		}
		
		$linkperstage = $perstage * $ex[1];
		$cparam = $linkperstage <= $numpages ? $linkperstage : $numpages;
		if ($ex[1] < $numofstage) {
			$nextstage = $ex[1] + 1;
			$nextpage = $perstage*$ex[1]+1;
		}
		
		$result_string = '';
		$result_string .= '<ul class="pagination">';
		$result_string .= isset($backstage) ? "<li><a href='".$url."page=1' title='Halaman 1'><span class='arrow'>&laquo;</span></a></li>" : "";
		$result_string .= isset($backstage) ? "<li><a href='".$url."page=".$backpage."' title='Halaman ".$backpage."'><span class='arrow'>&#139;</span></a></li>" : "";
		
		$total_loop = 0;
		
		for ($i = ($perstage*$ex[1])-($perstage-1); $i <= $cparam; $i++) {
			$result_string .= ($i == $ex[0]) ? "<li><span class='current'>".$i."</span></li>" : "<li><a href='".$url."page=".$i."' title='Halaman ".$i."' class='radius-4 shadow-inset'>".$i."</a></li>";
			$total_loop++;
		}
		
		$result_string .= $numofstage > $ex[1] ? "<li><a href='".$url."page=".$nextpage."' title='Halaman ".$nextpage."'><span class='arrow'>&#155;</span></a></li>" : "";
		$result_string .= $numofstage > $ex[1] ? "<li><a href='".$url."page=".$numpages."' title='Halaman ".$numpages."'><span class='arrow'>&raquo;</span></a></li>" : "";
		$result_string .= '</ul>';
		
		return $result_string;
	}
}

?>