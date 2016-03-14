<?php
require_once "dbwrapper_mysql_wrapper.php";
class NOTIFY extends  MYSQL_WRAPPER
{
	public function __construct($dbHost, $dbUser, $dbPassword, $dbName, $dbPort=3306)
	{
		 parent::__construct($dbHost, $dbUser, $dbPassword, $dbName, $dbPort, null);

	}

	public function updateVisio($ValuesIndexByField, $table, $salesOrder)
	{
		try
		{
		if(parent::update($ValuesIndexByField, $table, "salesOrder=$salesOrder") == 0)
		{
//			var_dump($ValuesIndexByField);
			parent::insert_into($ValuesIndexByField, $table, $salesOrder);

		}
		}
		catch (Exception $e)
		{
		//	echo parent::getLastFailedQuery();
		}

	}

	public function SysProd_Act_by_ID($login)
	{
		$out = parent::basic_select("users", "U_login=$login", "U_id");
		return $out;
	}
}
