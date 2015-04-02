<!-- Model -->

<?php
    // Connect to MySQL to your database with your MySQL username and password
    $conn = mysqli_connect('localhost', '********', '********', '********');
    if (mysqli_connect_errno())  // not error()
        echo "Failed to connect to COMP3540: " . mysqli_connect_error();

    // The function to check the validity of a user
    function checkValidity($username, $password) {
        global $conn;  
		$hashed_password = sha1($password);
        $sql = "SELECT * FROM users where username='$username' AND password='$hashed_password'";  // Select from users where username and password are the same as the user input.
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0)  // If the query returns 0 row, i.e., not matching row
            return false;
        else
            return true;
    }
	
	//Function for checking duplicate user names
	function checkUserDuplicate($username){
		global $conn;
		$sql = "SELECT * FROM users where username='$username'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0)
			return false;
		else
			return true;
	}
	
	//Function for checking duplicate emails
	function checkEmailDuplicate($email){
		global $conn;
		$sql = "SELECT * FROM users where email='$email'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0)
			return false;
		else
			return true;
	}
	
	//Function will return 0 is query is successful. If not, will echo error message and return 1
	function createUser($email, $username, $password){
		global $conn;
		$hashed_password = sha1($password);
		$sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
		if (mysqli_query($conn, $sql))
			return 0;
		else{
			echo "error: " . mysqli_error($conn);
			return 1;
		}
	}
	
	//Function to post question into database
	function postQuestion($question, $explanation, $datetime, $username){
		global $conn;
		$sql = "INSERT INTO questions (question, explanation, datetime, user) VALUES ('$question', '$explanation', '$datetime', '$username')";
		if (mysqli_query($conn, $sql))
			return 0;
		else{
			echo "error: " . mysqli_error($conn);
			return 1;
		}
	}
	
	//Function will return json object of questions from the database
	function getQuestions(){
		global $conn;
		$sql = "SELECT * FROM questions ORDER BY datetime DESC";
		$result = mysqli_query($conn, $sql);
		$json = array();
		if (mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)){
				$json[] = array(
					'id' => $row['id'],
					'question' => $row['question'],
					'explanation' => $row['explanation'],
					'datetime' => $row['datetime'],
					'user' => $row['user']
				);
			}
		}
		return json_encode($json);
	}
	
	//Function will return json object of questions from the database
	function getUserQuestions($username){
		global $conn;
		$sql = "SELECT * FROM questions WHERE user='$username' ORDER BY datetime";
		$result = mysqli_query($conn, $sql);
		$json = array();
		if (mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)){
				$json[] = array(
					'id' => $row['id'],
					'question' => $row['question'],
					'explanation' => $row['explanation'],
					'datetime' => $row['datetime'],
					'user' => $row['user']
				);
			}
		}
		return json_encode($json);
	}
	
	function getAnswers($id){
		global $conn;
		$sql = "SELECT * FROM answers WHERE qid='$id' ORDER BY datetime";
		$result = mysqli_query($conn, $sql);
		$json = array();
		if (mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)){
				$json[] = array(
					'id' => $row['id'],
					'qid' => $row['qid'],
					'answer' => $row['answer'],
					'datetime' => $row['datetime'],
					'user' => $row['user']
				);
			}
			return json_encode($json);
		}
		else
			return "empty";
	}
	
	function postAnswer($qid, $answer, $datetime, $user){
		global $conn;
		$sql = "INSERT INTO answers (qid, answer, datetime, user) VALUES ('$qid', '$answer', '$datetime', '$user')";
		if (mysqli_query($conn, $sql))
			return 0;
		else{
			echo "error: " . mysqli_error($conn);
			return 1;
		}
	}
	
	function deleteQuestion($qid){
		global $conn;
		$sql = "DELETE FROM questions WHERE id = '$qid'";
		if (mysqli_query($conn, $sql))
			return 0;
		else{
			echo "error: " . mysqli_error($conn);
			return 1;
		}
		$sql = "DELETE FROM answers WHERE qid = '$qid'";
		if (mysqli_query($conn, $sql))
			return 0;
		else{
			echo "error: " . mysqli_error($conn);
			return 1;
		}
	}
	
	function searchQuestions($str){
		global $conn;
		if ($str != ''){
			$words = explode(" ", $str);
			$sql = "SELECT * FROM questions WHERE ";
			$i = count($words);
			while ($i-- > 0){
				$sql .= "(question LIKE '%$words[$i]%' OR explanation LIKE '%$words[$i]%') AND ";
			}
			$sql = rtrim($sql, ' AND ' );
			$sql .= ";";
		}
		else 
			$str = "SELECT * FROM questions ORDER BY datetime DESC";
		if ($result = mysqli_query($conn, $sql)){
			$json = array();
			if (mysqli_num_rows($result) > 0){
				while ($row = mysqli_fetch_assoc($result)){
					$json[] = array(
						'id' => $row['id'],
						'question' => $row['question'],
						'explanation' => $row['explanation'],
						'datetime' => $row['datetime'],
						'user' => $row['user']
					);
				}
			}
			return json_encode($json);
		}
		
	}
	
	
	
	//Function to close the MySQL connection
	function closeConn(){
		global $conn;
		mysqli_close($conn);
		unset($conn);
	}
?>




























