<?php

class MySqlConnector {
	public $dbUserName = '';
	public $dbUserPassword = '';
	public $dbName = '';
	public $dbHostName = '';
	
	private $link;
	
	function __construct() {
		$this->dbUserName = ''; //username
		$this->dbUserPassword = ''; //password
		$this->dbName = ''; //Database name
		$this->dbHostName = ''; //host name or IP address.
		
		if(!$this->link = mysql_pconnect($this->dbHostName, $this->dbUserName, $this->dbUserPassword)){
			$error = mysql_error();
			//echo $error['message'];
			//echo $error['code'];
			die('Error: Could not connect to the database');
		}
		mysql_select_db($this->dbName, $this->link);
	}
	
	//Query with sql
	public function doQuery($query)
	{
		$result = mysql_query($query, $this->link);
		
		return $result;
	}
	
	//Fetch object
	public function fetchObject($result)
	{
		$row = mysql_fetch_object($result);

		return $row;
	}
	
	//Fetch array
	public function fetchArray($result)
	{
		$row = mysql_fetch_array($result);

		return $row;
	}
	
	//Count Number of rows
	public function numOfRows($result)
	{
		$numberOfRows = mysql_num_rows($result);

		return $numberOfRows;
	}
	
}