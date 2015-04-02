<!-- Controller -->

<?php

	if (!isset($_SERVER['HTTPS']) && !isset($_SERVER['https'])) {
		/* https and http_host should be before any output; location is changed to the new $url with redirect status code */
		$url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: " . $url);
		exit();
	}
	
	session_start();
	
	/* If the session cookie exists for the client cookie, then resume session by automatically directing to the main page */
	if (isset($_SESSION['signedin'])){
		include('mainpage.php');
		exit;
	}


	//Model file included once.
	include_once('model.php');

    // Clear variables used in the view files. These variables will only output the '*' asterisk symbol
	// where the corresponding $error_message variable will output the reason for error
    $error_sign_username = '';
    $error_sign_password = '';
	$error_sign_email= '';
	$error_sign_confirmpassword = '';
	
	// Error message variable
	$error_message = '';
	
    $username = '';
    $password = '';
	$email = '';
	$confirmpassword = '';
        
    // If it is the first time, then display the 'StartPage' page. .
    
    if (empty($_POST['command'])) {
        include('startpage.php');  
        exit();
    }

    // It is not the first time. The user sent data.
    $command = $_POST['command'];

    switch($command) 
    {
    case 'SignIn':
		if (empty($_POST['username'])){
			$error_sign_username = '*';
			$error_message = '*required';
		}
		else
			$username = $_POST['username'];
		
		if (empty($_POST['password'])){
			$error_sign_password = '*';
			$error_message = '*required';
			}
		else
			$password = $_POST['password'];
		
		//Login successful
		if ($error_message == ''){
			if(checkValidity($username, $password)){
				closeConn(); //Close MySQL connection
				$_SESSION['signedin'] = 'YES';
				$_SESSION['username'] = $username;
				setcookie('username', $username);//store cookie value of user since login was successful
				include('mainpage.php');
			}
			else{
				closeConn(); //Close MySQL connection
				$error_message = '*invalid username or password';
				$error_sign_username = '*';
				$error_sign_password = '*';
				include('startpage.php');
				echo '<script type = "text/javascript">'
					,'displayPopup("signin");'
					,'</script>';	
			}
		}
		else{
		//Login incorrect, will redisplay the StartPage with the sign in pop up
			include('startpage.php');
			echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("signin");'
					,'</script>';			
		}
        exit();

    case 'Join':
		if (empty($_POST['email'])){
			$error_sign_email = '*';
			$error_message = '*required<br>';//Break line for potential regex errors
		}
		else
			$email = $_POST['email'];
	
		if (empty($_POST['username'])){
			$error_sign_username = '*';
			$error_message = '*required<br>';
		}
		else
			$username = $_POST['username'];
		
		if (empty($_POST['password'])){
			$error_sign_password = '*';
			$error_message = '*required<br>';
			}
		else
			$password = $_POST['password'];
			
		if (empty($_POST['confirmpassword'])){
			$error_sign_confirmpassword = '*';
			$error_message = '*required<br>';
			}
		else
			$confirmpassword = $_POST['confirmpassword'];
		
	
		//If the password and confirm password are not equal, then a mismatch has occurred
		if (strcmp($_POST['password'], $_POST['confirmpassword']) != 0){
			$error_sign_confirmpassword = '*';
			$error_message .= '*confirmed password does not match<br>';
			}

		/*Check input validity--------------------------------------------------------------------------*/
		if ($error_sign_email == ''){ //if variable is empty, indicates email field was inputted
			$pattern = '/^[a-zA-Z0-9]+@[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)+$/';
			$result = preg_match($pattern, $email);
			if (!$result){
				$error_sign_email = '*';
				$error_message .= '*email address invalid (must include @ symbol).<br>';
			}
		}
		if ($error_sign_username == ''){ //if variable is empty, indicates email field was inputted
			$pattern = '/^[a-zA-Z_][a-zA-z0-9_]{3,}$/';
			$result = preg_match($pattern, $username);
			if (!$result){
				$error_sign_username = '*';
				$error_message .= '*username cannot start with digit; must be min. 4 letters, digits, or \'_\'.<br>';
			}
		}
		
		if ($error_sign_password == ''){ //if variable is empty, indicates password field was inputted
			$pattern = '/^(?=.*[\!\@\#\$\%\^&\*\(\)\_\+])[a-zA-Z0-9\!\@\#\$\%\^&\*\(\)\_\+]{6,}$/';
			$result = preg_match($pattern, $password);
			if (!$result){
				$error_sign_password = '*';
				$error_message .= '*pwd must be min 6 letters, at least one digit, and at least one sp. char.<br>';
			}
		}
		/*Done input validity--------------------------------------------------------------------------*/
		
		
		/*Successfully enter user credentials. Will do further check with the database to ensure no duplicates.*/
		if ($error_message == ''){
			if (checkUserDuplicate($username)){//Check for duplicate usernames
				$error_sign_username = '*';
				$error_message = '*username already exists'; //Set error message to indicate duplicate username
				include('startpage.php');
				echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("join");'
					,'</script>';	
			}
			else if (checkEmailDuplicate($email)){//Check for duplicate emails
				$error_sign_email = '*';
				$error_message = '*email already exists'; //Set error message to indicate duplicate email
				include('startpage.php');
				echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("join");'
					,'</script>';	
			}
			else{
				if (createUser($email, $username, $password)){//If createUser function returns 1, error occured															 
					include('startpage.php');
					echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
							,'displayPopup("join");'
							,'</script>';		
				}
				else{	
				$email = '';
				$username = '';
				$password = '';
				$confirmpassword = '';
				include('startpage.php');
				echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
						,'displayPopup("signin");'
						,'alert("User account successfully created. Please sign in.")'
						,'</script>';	
				}
			}
		}
		else{
			include('startpage.php');
			echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("join");'
					,'</script>';			
		}
        exit();	

    case 'ForgotPassword':
		if (empty($_POST['username'])){
			$error_sign_username = '*';
			$error_message = '*required';
		}
		else
			$username = $_POST['username'];
		
		if ($error_message == ''){
			$email = '';
			$username = '';
			$password = '';
			$confirmpassword = '';
			include('startpage.php');
			echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("signin");'
					,'alert("An email has been sent to the corresponding address. Please sign in with the new credentials.")'
					,'</script>';
		}
		else{
			include('startpage.php');
			echo '<script type = "text/javascript">' //Run javascript function to bring up the pop up box.
					,'displayPopup("forgotpassword");'
					,'</script>';
		}
        exit();

    default:
        include('startpage.php');  // ViewStart
        exit();
    }
	
?>
