<?php
class BlobDemo
{
	 const DB_HOST = 'localhost';
	 const DB_NAME = 'carsforsale';
	 const DB_USER = 'root';
	 const DB_PASSWORD = 'root';
	 
	 private $conn = null;
	 
	 /**
	 * Open the database connection
	 */
	 public function __construct(){
		 // open database connection
		 $connectionString = sprintf("mysql:host=%s;dbname=%s;charset=utf8",
		 BlobDemo::DB_HOST,
		 BlobDemo::DB_NAME);
		 
		 try {
		 $this->conn = new PDO($connectionString,
		 BlobDemo::DB_USER,
		 BlobDemo::DB_PASSWORD);
		 //for prior PHP 5.3.6
		 //$conn->exec("set names utf8");
	 
		 } 
		 catch (PDOException $pe) {
			die($pe->getMessage());
		 }
	 }
 
 
	public function insertBlob($cname,$pr,$cond,$mile,$description,$filePath){
	 $blob = fopen($filePath,'rb');
	 
	 //echo $filePath."<br>";
	 //echo $mime."<br>";
	 $sql = "INSERT INTO cars_tbl (c_name, c_price, c_cond, c_mile, c_desc, c_src) VALUES(:cname,:pr,:cond,:mile,:description,:data)";
	 $stmt = $this->conn->prepare($sql);
	 
	 $stmt->bindParam(':cname',$cname,PDO::PARAM_STR);
	 $stmt->bindParam(':pr',$pr);
	 $stmt->bindParam(':cond',$cond);
	 $stmt->bindParam(':mile',$mile);
	 $stmt->bindParam(':description',$description,PDO::PARAM_LOB);
	// $stmt->bindParam(':mime',$mime);
	 $stmt->bindParam(':data',$blob,PDO::PARAM_LOB);

	 
	 return $stmt->execute();
	}
	
	/**
	 * select data from the the files
	 * @param int $id
	 * @return array contains mime type and BLOB data
	 */
	public function selectBlob($id) {
	 
	 $sql = "SELECT c_src FROM cars_tbl WHERE id = :id";
	 
	 $stmt = $this->conn->prepare($sql);
	 $stmt->execute(array(":id" => $id));
	 //$stmt->bindColumn(1, $mime);
	 $stmt->bindColumn(1, $data, PDO::PARAM_LOB);
	 
	 $stmt->fetch(PDO::FETCH_BOUND);
	 
	 return array("data" => $data);
	 
	}
	/**
	 * close the database connection
	 */
	 public function __destruct() {
	 // close the database connection
	 $this->conn = null;
	 }
	 /**
	 * insert blob into the files table
	 * @param string $filePath
	 * @param string $mime mimetype
	 */

}




?>