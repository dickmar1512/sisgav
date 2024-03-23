<?php
class Database {
	public static $db;
	public static $con;
	public $user="tarosoft";
	public $pass="armagedon";
	public $host="localhost";
	public $ddbb="dbsigav";
	
	function Database(){
		$this->user="tarosoft";$this->pass="armagedon";$this->host="localhost";$this->ddbb="dbsigav";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		$con->query("set sql_mode=''");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
