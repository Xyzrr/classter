<?php
	class SqlConnection {
		private $link;
		private $result;

		function __construct() {
			if(empty($this->link)) {
				$this->link = mysqli_connect($GLOBALS["conf"]["db_hostname"], $GLOBALS["conf"]["db_username"], $GLOBALS["conf"]["db_password"]);
				if(!$this->link) {
					$this->writeError("Failed to connect to database.");
				}
				if(!mysqli_set_charset($this->link, "utf8")) {
					$this->writeError("Failed to set database encoding.");
				}
			}
			if(!mysqli_select_db($this->link, $GLOBALS["conf"]["db_name"])) {
				$this->writeError("Failed to select database.");
			}
		}

		public function query($sql) {
			if(empty($this->link) or empty($sql)) {
				$this->writeError();
			}
			$result = mysqli_query($this->link, $sql);
			if(!$result) {
				$this->writeError("SQL error: " . mysqli_error($this->link));
			}
			$this->result = $result;
		}

		public function escapeString($str) {
			if(!is_string($str)) 
				$this->writeError("Tried to escape a non-string.");

			return mysqli_real_escape_string($this->link, $str);
		}

		public function fetchArray() {
			if(!$this->result)
				$this->writeError("Fetched array of failed query.");
			return mysqli_fetch_array($this->result);
		}

		public function lastID() {
			return mysqli_insert_id($this->link);
		}

		public function numRows() {
			if($this->result == null) {
				$this->writeError("Tried to get number of rows of nonexistent query.");
			}
			return mysqli_num_rows($this->result);
		}

		public function rowsAffected() {
			return mysqli_affected_rows($this->link);
		}

		public function getResult() {
		    $rows = array();
	        while($row = $this->fetchArray()) {
	        	$rows[] = $row;
	        }
	        return $rows;
		}

		public function writeError($error) {
			throw new Exception($error);
		}
	}
?>