<?php

namespace App\Core;

use \PDO;

class Database {

  private $dbh;
  private $stmt;

  public static function getPDO() {
		$strKey = md5(serialize(array(DB_HOST, DB_NAME, DB_USER, DB_PASS)));
		if (!(isset($GLOBALS["PDOS"][$strKey]) && $GLOBALS["PDOS"][$strKey] instanceof \PDO)) {
			
			try {
				$GLOBALS["PDOS"][$strKey] = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(\PDO::ATTR_PERSISTENT => true, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
			} 
			catch (\PDOException $e) {
				die('Couldn\'t connect to the database because: '.$e->getMessage());
			}
		};
		return( $GLOBALS["PDOS"][$strKey]);
	}

}

?>