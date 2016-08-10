<?php

class Database {

	private $dbName, $dbHost, $dbUser, $dbPass;
	private $connection = null;

	public function __construct($dbName, $dbHost, $dbUser, $dbPass) {
		$this->dbName = $dbName;
		$this->dbHost = $dbHost;
		$this->dbUser = $dbUser;
		$this->dbPass = $dbPass;
	}

	/**
	  * Initialize a newly created Database object
	  */
	public function dbCreation() {

		try {
			$this->connection = new PDO("mysql:host=$this->dbHost", $this->dbUser, $this->dbPass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->exec("CREATE DATABASE IF NOT EXISTS " . $this->dbName);
			//echo "Database created successfully!<br>";
			$this->dbDisconnection();
		}
		catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	  * Establishes the connection with SQL server
	  */
	public function dbConnection() {

		if($this->connection == null) {
			try{
				$this->connection = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->connection->exec("SET CHARACTER SET utf8");
				//echo "Connection successful!<br><br>";
			}
			catch(PDOException $e) {
				die($e->getMessage());
			}
		}
		return $this->connection;
	}

	/**
	  * Close the connection with SQL server
	  */
	public function dbDisconnection() {

		$this->connection = null;
		//echo "Connection closed!<br><br>";
	}

	/**
     * Creates the tables of the database
     */
	public function createTables() {

		try{
			$sql = "
					CREATE TABLE `users` (
						`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`username` VARCHAR(30) NOT NULL,
						`password` VARCHAR(30) NOT NULL,
						`name` VARCHAR(30),
						`surname` VARCHAR(30),
						`email` VARCHAR(30) NOT NULL,
						`photo` VARCHAR(300)
						); 
					CREATE TABLE `data` (
						`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
						`account_name` VARCHAR(30) NOT NULL,
						`username` VARCHAR(30) NOT NULL,
						`password` VARCHAR(30) NOT NULL,
						`comment` VARCHAR(300),
						`url` VARCHAR(300)
						);
					";

			if($this->connection != null) {
				$stmt = $this->connection->prepare($sql);
				$stmt->execute();
				//echo "Tables users and data created successfully!<br>";
			}
			else {
				throw new Exception("Connection is not open!<br>");
			}
		}
		catch(PDOException $e) {
			echo ("Tables already exists!, " + $e->getMessage() + "<br>");
		}
	}

	/**
     *	Authorization for users data
     *	return false if the authorization fail else true
     */
	public function userLogin($username, $password) {

		try{
			$sql = "SELECT * FROM USERS
					WHERE username=:username AND password=:password";

			if($this->connection != null) {
				$stmt = $this->connection->prepare($sql);
				$stmt->bindParam(':username', $username, PDO::PARAM_STR);
				$stmt->bindParam(':password', $password, PDO::PARAM_STR);
				$stmt->execute();
				if($stmt->rowCount()>0) {
					return true;	// found the record
				}
			return false; 			// connection is closed or didn't find the record
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Selects a record from table data by dynamic sql PDO statement 
	 */
	public function searchRecord($account_name, $username, $url) {

		try{
			$sql = "SELECT * FROM data WHERE 1=1";
			if(isset($account_name) && !empty($account_name)) 	$sql .= " AND account_name = '$account_name'";
			if(isset($username) && !empty($username))  			$sql .= " AND username = '$username'";
			if(isset($url) && !empty($url)) 					$sql .= " AND url = '$url'";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			$jsonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $jsonData;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Insert a record to the table data by dynamic sql PDO statement
	 */
	public function insertRecord($account_name, $username, $password, $comment, $url) {

		try{
			$sql 		 = "INSERT INTO data (account_name, username, password, comment, url)
							VALUES (:account_name, :username, :password, :comment, :url)";
			$sql_last_id = "SELECT * FROM data
							ORDER BY id DESC LIMIT 1";

			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(':account_name', $account_name, PDO::PARAM_STR);
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->bindParam(':password', $password, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':url', $url, PDO::PARAM_STR);
			$stmt->execute();
			//separate queries because there are insert and select requests
			$stmt = $this->connection->prepare($sql_last_id);
			$stmt->execute();
			$jsonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $jsonData;
		}
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1062) {
				$duplicateEntry = explode(" ", $e->errorInfo[2]);
				return $duplicateEntry;
			}
			echo $e->getMessage();
		}
	}

	/**
	 * Update a record from the table data by dynamic sql PDO statement
	 */
	public function updateRecord($old_username, $account_name, $username, $password, $comment, $url) {

		try{
			$sql 		 = "UPDATE data
							SET account_name=:account_name, username=:username, password=:password, comment=:comment, url=:url
							WHERE username=:old_username";
			$sql_last_id = "SELECT * FROM data
							WHERE username=:username";

			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(':old_username', $old_username, PDO::PARAM_STR);
			$stmt->bindParam(':account_name', $account_name, PDO::PARAM_STR);
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->bindParam(':password', $password, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':url', $url, PDO::PARAM_STR);
			$stmt->execute();

			$stmt = $this->connection->prepare($sql_last_id);
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->execute();
			$jsonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $jsonData;
		}
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1062) {
				$duplicateEntry = explode(" ", $e->errorInfo[2]);
				return $duplicateEntry;
			}
			echo $e->getMessage();
		}
	}
}

?>