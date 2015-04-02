<?php
	if (session_status() == PHP_SESSION_NONE) 	
                session_start();
	if (!isset($_SESSION['signedin'])){
		include('sign_out.php');
		exit();
	}
	$username = $_SESSION['username'];

//Model file included once.
	include_once('model.php');
	
	if (isset($_POST['command']))
		$command = $_POST['command'];
	
	if (isset($_GET['file'])){
		$command = $_GET['file'];
		
		
	}
	

    switch($command) 
    {
    case 'PostQuestion':
		$question = $_POST['question'];
		$explanation = $_POST['explanation'];
		$datetime =  date("Y-m-d H:i:s", time());
		postQuestion($question, $explanation, $datetime, $username);
		$url = 'controller.php';
		header("Location: " . $url);
		closeConn(); 
        exit();	
		
	case 'PostAnswer':
		$answer = $_POST['answer'];
		$qid = $_POST['qid'];
		$datetime =  date("Y-m-d H:i:s", time());
		postAnswer($qid, $answer, $datetime, $username);
		ob_end_clean();
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$data = getAnswers($qid);
		closeConn();
		echo($data);
		exit();
	
	case 'all':
		ob_end_clean(); /* ob_start("ob_tidyhandler") is enabled in php.ini. By adding ob_end_clear() begore header and json_encode, json
								    string is sent back to client successfully */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$data = getQuestions();
		closeConn();
		echo($data);
		exit();
		
	case 'answers':
		ob_end_clean();
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$data = getAnswers($_GET['id']);
		closeConn();
		echo($data);
		exit();
		
	case 'deleteList':
		ob_end_clean(); /* ob_start("ob_tidyhandler") is enabled in php.ini. By adding ob_end_clear() begore header and json_encode, json
								    string is sent back to client successfully */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$data = getUserQuestions($username);
		closeConn();
		echo($data);
		exit();
	
	case 'deleteQuestion':
		$qid = $_GET['qid'];
		ob_end_clean(); /* ob_start("ob_tidyhandler") is enabled in php.ini. By adding ob_end_clear() begore header and json_encode, json
								    string is sent back to client successfully */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$errorlog = deleteQuestion($qid);
		$data = getUserQuestions($username);
		closeConn();
		echo($data);
		exit();
		
	case 'search':
		$query = $_GET['q'];
		ob_end_clean(); /* ob_start("ob_tidyhandler") is enabled in php.ini. By adding ob_end_clear() begore header and json_encode, json
								    string is sent back to client successfully */
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		$data = searchQuestions($query);
		closeConn();
		echo($data);
		exit();
  
		
    }
?>