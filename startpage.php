<?php
	if (isset($_COOKIE['username'])){
		$prevuser = $_COOKIE['username'];
		/* If cookie is set from previous login, username will be displayed in SignIn page. Furthermore, javascript function
		  will be called upon window.onload event so that the target id can be found for manipulation */
		echo "<script type='text/javascript'>"
				,"window.onload = function(){document.getElementById('greeting').innerHTML = 'Welcome back $prevuser!';}"
				,"</script>";
	}
?>

<!DOCTYPE html>
<html>

<head>
	<title>TRUQA</title>
	
	<!-- time variable added at the end of href to prevent server loading from cache -->
	<link href="style.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<div style="width: 100%; height: 100%; padding: 0; margin: 0; top: 0px; left: 0px;">

		<h1><b>Thompson Rivers University Q&A</b></h1>
	
		<!--navigation bar-->
		
		<nav id="nav01">
			<ul style="margin: 0; padding:  0px 5px;">
				<li id="date" style="list-style: none; padding: 5px 0px 5px 9px;font-family: Georgia, serif;"><b>[<?php echo (date("h:m:s", time())); ?>]</b></li>
			</ul>
			<ul id="menu">
				<li>		
					<a href="#"><img src="menu-32-32.png" alt="Drop down icon" style="width:32px;height:32px"></a>
					<ul>
						<li id="first" onclick="displayPopup('join')"><a href="#">Join</a></li>
						<li onclick="displayPopup('signin')"><a href="#">Sign In</a></li>
						<li id="last" onclick="displayPopup('forgotpassword')"><a href="#">Forgot Password</a></li>
					</ul>
				</li>
			</ul>
			
		</nav>
	
		<!--Moving image-->
		<div id="divImage">
			<img id="movingImage" src="boo_hide.png">
		</div>
		
		<!--footer-->
		<footer id="foot01"><a href="https://cs.tru.ca"><b>About Us</b></a></footer>
		
		<!--greeting read from cookie-->
		<p id="greeting" style="position: absolute; left: 50%;transform: translateX(-50%); top:15%; font-family: Georgia, serif; font-size: 20px;"></p>
		
		<!--Transparent blanket when popup appears-->
		<div id="blanket" onclick="removePopup()"></div>
		
		<!--Join pop up box-->
		<div class="popUp" id="join">
			<p style="position: absolute; font-family: Georgia, serif; font-size: 20px; left: 50%; transform: translateX(-50%);">Join</p>
			<form method="post" action="controller.php" autocomplete="on"> <!--Autocomplete on only for input fields that are not passwords-->
			
				<div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%);">
					<input type="hidden" name="command" value="Join">
					Email: <br><input type="text" name="email" value="<?php echo $email ?>" id=><span style="color: red"><?php echo $error_sign_email; ?></span> <br>
					Username: <br><input type="text" name='username' value="<?php echo $username ?>"><span style="color: red"><?php echo $error_sign_username; ?></span> <br>
					Password:<br><input type="password" name="password"  autocomplete="off" value="<?php echo $password ?>"><span style="color: red"><?php echo $error_sign_password ?></span> <br>
					Confirm password: <br><input type="password" name="confirmpassword" value="<?php echo $confirmpassword ?>"><span style="color: red"><?php echo $error_sign_confirmpassword ?></span> <br>
				</div>	
				
				<p style="position: absolute; color: red; top: 58%; left: 4%;"><?php echo $error_message; ?></p><!-- For displaying error messages -->
				<input type="submit" value="Submit" style="position: absolute; top: 90%; left: 90%; transform: translateX(-100%);">
				<input type="button" value="Cancel" onclick="removePopup()" style="position: absolute; top: 90%; left: 10%">
				
			</form>
		</div>
		
		<!--Sign in pop up box-->
		<div class="popUp" id="signin">
			<p style="position: absolute; font-family: Georgia, serif; font-size: 20px; left: 50%; transform: translateX(-50%);">Sign in</p>
			<form method="post" action="controller.php" autocomplete="on"> <!--Autocomplete on only for input fields that are not passwords-->
			
				<div style="position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%);">
					<input type="hidden" name="command" value="SignIn">
					Username: <br><input type="text" name='username' value="<?php echo $username ?>"> <span style="color: red"><?php echo $error_sign_username; ?></span> <br>
					Password:<br><input type="password" name="password" autocomplete="off" value="<?php echo $password ?>"> <span style="color: red"><?php echo $error_sign_password ?></span> <br>
				</div>	
				
				<p style="position: absolute; color: red; top: 70%; left: 20%;"><?php echo $error_message; ?></p><!-- For displaying error messages -->
				<input type="submit" value="Sign in" style="position: absolute; top: 90%; left: 90%; transform: translateX(-100%);">
				<input type="button" value="Cancel" onclick="removePopup()" style="position: absolute; top: 90%; left: 10%">
				
			</form>
		</div>
		
		<!-- Forgot password pop up-->
		<div class="popUp" id="forgotpassword">
			<p style="position: absolute; font-family: Georgia, serif; font-size: 20px; left: 50%; transform: translateX(-50%);">Forgot Password</p>
			<form method="post" action="controller.php" autocomplete="on"> <!--Autocomplete on only for input fields that are not passwords-->
			
				<div style="position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%);">
					<input type="hidden" name="command" value="ForgotPassword">
					Enter username or email: <br><input type="text" name='username' value="<?php echo $username ?>"> <span style="color: red"><?php echo $error_sign_username; ?></span> <br>
				</div>	
				<p style="position: absolute; color: red; top: 70%; left: 20%;"><?php echo $error_message; ?></p><!-- For displaying error messages -->
				<input type="submit" value="Submit" style="position: absolute; top: 90%; left: 90%; transform: translateX(-100%);">
				<input type="button" value="Cancel" onclick="removePopup()" style="position: absolute; top: 90%; left: 10%">
				
			</form>
		</div>
		
	</div> 
	
	
	
	
	<script src="script.js?<?php echo time(); ?>"></script>
</body>


</html>