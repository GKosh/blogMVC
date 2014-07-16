<?php
class model 
{
		public $data = array();
		
	
	public function __construct(){
		
		}
		
		public function dbQuery($Query){
		
		
		$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die('Не удалось соединиться: ' . mysql_error());
		$mysqli->set_charset("utf8");
		$result = $mysqli->query($Query);
		$table = array();
		if ((isset($result))&&($result!='')){
			while ($row = $result->fetch_assoc()){
			array_push($table,$row)  ;
			}
		return $table;
		}
		mysqli_close($mysqli); 
		
		}
		
}
