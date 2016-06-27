<?php
	require_once 'db_connect.php';
	class DatabaseObject{
		private $id, $name, $description, $address, $phone, $email, $website;
		function __construct($id, $name, $description, $address, $phone, $email, $website){
			$this->id = $id;
			$this->name = $name;
			$this->description = $description;
			$this->address = $address;
			$this->phone = $phone;
			$this->email = $email;
			$this->website = $website;
		}


		public static function search($keyword=''){
			global $mySqli;
			if(!empty($keyword)){
				// $keyword = '-8vvxxdd';
				$query="SELECT * FROM companies WHERE name LIKE '%".$keyword."%' OR address LIKE '%".$keyword."%' OR phone LIKE '%".$keyword."%'";
				
			} else {
				$query="SELECT * FROM companies";
				
			}
			$result = $mySqli->query($query);
			$objects_array = array();
			if(!$result){ return false;}

			while ($row = $result->fetch_assoc()) {
				$dbObject = new DatabaseObject($row['id'], $row['name'], $row['description'], $row['address'], $row['phone'], $row['email'], $row['website']);
				$objects_array[] = $dbObject;
			}
			return $objects_array;
			
		}

		public function get_name(){
			return $this->name;
		}

		public function get_description(){
			return $this->description;
		}
		public function get_address(){
			return $this->address;
		}
		public function get_phone(){
			return $this->phone;
		}
		public function get_email(){
			return $this->email;
		}
		public function get_website(){
			return $this->website;
		}
	}
?>