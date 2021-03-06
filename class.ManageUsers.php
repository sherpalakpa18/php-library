<?php
	include_once( 'class.database.php' );

	class ManageUsers{
		public $linker;

		function __construct()
		{
			$db_connection = new dbConnection();
			$this->linker = $db_connection->connect();
			return $this->linker;
		}

		// Get book From Main Page
		function getBook($bid){
				$query = $this->linker->query("SELECT * FROM books WHERE bid = '$bid'");
				$counts = $query->rowCount();
				if($counts == 1){
					$result = $query->fetchAll();
				}else{
					$result = $counts;
				}
				return $result;
		}


		function registerUsers($username,$email,$password,$ip_address,$date,$time)
		{
			$query = $this->linker->prepare("INSERT INTO users (username,email,password,ip_address,reg_date,reg_time) 
				VALUES (?,?,?,?,?,?)");
			$values = array($username,$email,$password,$ip_address,$date,$time);
			$query->execute($values);
			$counts = $query->rowCount();
			return $counts;
		}

		function LoginUsers($username,$password){
			$query = $this->linker->query("SELECT * FROM users WHERE username = '$username' AND password='$password'");
			$rowcount = $query->rowCount();
			return $rowcount;
		}

		function GetUserInfo($username){
			$query = $this->linker->query("SELECT * FROM users WHERE username = '$username'");
			$rowcount = $query->rowCount();
			// Check if username exits i.e if 1 exits else not
			if($rowcount == 1)
			{
				$result = $query->fetchAll();
				return $result;
			}
			else
			{
				return $rowcount;
			}
		}
		function getNewBooks(){
			$query = $this->linker->query("SELECT * FROM books ORDER BY bid DESC LIMIT 0,4");
			return $query->fetchAll();
		}

		function bookmarkBook($bookmark,$b_id,$s_id)
			{
				$query = $this->linker->prepare("INSERT INTO issues (b_id,s_id,bookmarked) VALUES (?,?,?)");

				$values = array($b_id,$s_id,$bookmark);
				$query->execute($values);
				$counts = $query->rowCount();
				return $counts;
			}

		function checkBookmark($b_id,$s_id){
			$query = $this->linker->query("SELECT * FROM issues WHERE b_id = '$b_id' AND s_id = '$s_id'");
			return $query->rowCount();
		}
		function checkQunatity($b_id){
			$query = $this->linker->query("SELECT * FROM books WHERE bid = '$b_id'");
			return $query->fetchAll();
		}

		function setZero($b_id){
			$query = $this->linker->query("UPDATE `books` SET `quantity`=0 WHERE bid = '$b_id'");
			return $query->rowCount();
		}

		function decBook($b_id){
			$query = $this->linker->query("UPDATE `books` SET `quantity`=`quantity`-1 WHERE bid = '$b_id'");
			return $query->rowCount();
		}

		function delIssue($issue_id){
			$query = $this->linker->query("DELETE FROM issues WHERE tid='$issue_id'");
			$counts = $query->rowCount();
			return $counts;
		}

		function incBook($book_id){
			$query = $this->linker->query("UPDATE `books` SET `quantity`=`quantity`+1 WHERE bid = '$book_id' ");
			return $query->rowCount();
		}

		function checkLimit($s_id){
			$query = $this->linker->query("SELECT * FROM issues WHERE s_id = '$s_id'");

			$rowcount = $query->rowCount();
			
			return $rowcount;
			
		}

		function insertFeeds($feedby,$feed_type,$feeds){
			$query = $this->linker->prepare("INSERT INTO feedbacks (feedby,feed_type,feeds) VALUES (?,?,?)");
			$values = array($feedby,$feed_type,$feeds);
			$query->execute($values);
			$counts = $query->rowCount();
			return $counts;
		}

		function paginateBook($limit){

			$query = $this->linker->query("SELECT bid,name,image,author FROM books ORDER BY bid DESC $limit");

			$counts = $query->rowCount();

			if($counts > 0)
			{
				$result = $query->fetchAll();
			}
			else
			{
				$result = $counts;
			}
			return $result;
		}
		function getTotal(){
			$query = $this->linker->query("SELECT * FROM books");
			return $query->rowCount();
		}

		function searchBook($nm){

			$query = $this->linker->query("SELECT * FROM books WHERE name LIKE('%$nm%') LIMIT 0,6");
			return $query->fetchAll();
		}

		function searchNote($nm){
			$query = $this->linker->query("SELECT * FROM notes WHERE subject LIKE('%$nm%')");
			return $query->fetchAll();
		}
		
		function searchEbook($nm){

			$query = $this->linker->query("SELECT * FROM ebooks WHERE name LIKE('%$nm%')");

			return $query->fetchAll();
		}

}

?>
