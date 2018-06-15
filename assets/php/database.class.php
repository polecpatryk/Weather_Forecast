<?php

	class Database
	{
		const HOST_AND_DB = 'mysql:host=localhost;dbname=id6198039_weather_forecast';
		const USER        = 'id6198039_patryk';
		const PASS        = '741963456';
		
		private $pdo;
		
		public function __construct()
		{
			try
			{
				$this->pdo = new PDO(Database::HOST_AND_DB, Database::USER, Database::PASS);
			}
			catch(PDOException $message)
			{
				echo $message->getMessage();
			}
		}
		
		public function __destruct()
		{
			$this->pdo = null;
		}
		
		public function Insert($city, $from_date, $to_date)
		{			
			$stmt = $this->pdo->prepare('INSERT INTO weather_search (ip, city, from_date, to_date) VALUES (:ip, :city, :from_date, :to_date)');

			$stmt->execute(array(
				':ip' => $this->GetIP(),
				':city' => $city,
				':from_date' => $from_date,
				':to_date' => $to_date
			));
		}
		
		public function Select()
		{
			$stmt = $this->pdo->prepare('SELECT * FROM weather_search ORDER BY id DESC LIMIT 5');
			$stmt->execute();
			
			return $stmt->fetchAll();
		}
		
		public function GetIP($ip2long = true)
		{
			if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else
				$ip = $_SERVER['REMOTE_ADDR'];

			if($ip2long)
				$ip = ip2long($ip);

			return $ip;
		}
	}