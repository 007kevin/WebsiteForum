<?php

if (session_status() == PHP_SESSION_NONE) 	
                session_start();
if (!isset($_SESSION['signedin'])){
		include('sign_out.php');
		exit();
	}
$username = $_SESSION['username'];
?>

<!-- View: Main -->

<!DOCTYPE html>

<html>
<head>
	<title>Main Page</title>
	<!-- time variable added at the end of href to prevent server loading from cache -->
	<link href="style.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<div style="width: 100%; height: 100%; padding: 0; margin: 0; top: 0px; left: 0px;">
		<h1><b>Main Page</b></h1>
		
		<!--Observation: It seems the date element is positioning the nav & header elements.
			Edit: Reason for seemingly proper alignment was due to unclosed <p> on previous assignments. -->

		<nav id="nav01">
				<ul style="margin: 0; padding:  0px 5px;">
					<li id="date" style="list-style: none; padding: 5px 0px 5px 9px;font-family: Georgia, serif;"><b>[<?php echo (date("h:m:s", time())); ?>]</b></li>
				</ul>
				<ul id="menu">
					<li>		
						<a href="#"><img src="menu-32-32.png" alt="Drop down icon" style="width:32px;height:32px"></a>
						<ul>	
							<li id="first" onclick="displayPopup('postquestion')"><a href=#>Post a Question</a></li>
							<li onclick="deleteQuestions()"><a href="#">Delete a Question</a></li>
							<li onclick="listNew();"><a href="#">List New Questions</a></li>
							<li onclick="listAll()"><a "href="#">List All Questions</a></li>
							<li onclick="search()"><a id="searchtag" href="#">Search Questions</a></li>
							<li id="last"><a href="sign_out.php">Sign Out</a></li>
						</ul>
					</li>
					<li style="float: left; padding: 5px 0px 5px 0px;font-family: Georgia, serif;"><b>User: <?php echo $username; ?> &nbsp</b></li>
				</ul>
		</nav>
		
		<!--Post question pop up-->
		<div class="popUp" id="postquestion">
			<p align="center" style="font-family: Georgia, serif; font-size: 20px;">Ask a question</p>
			<form id="formquestion"method="post" action="questions.php" autocomplete="on"> <!--Autocomplete on only for input fields that are not passwords-->
			
				<div style="position: absolute; top: 25%; left: 4.5%; width: 90%; height: 45%;">
					<input type="hidden" name="command" value="PostQuestion">
					<input type="hidden" name="user" value=<?php echo $username; ?>>
					Subject: <input style="border: 1px solid; background-color: #ffffff;" type="text" name="question" required><br><br>
				
					<textarea name="explanation" style="border: 1px solid; width: 100%; height: 100%; resize: none;" required></textarea>
				</div>
				
				<input type="submit" value="Post Question" style="position: absolute; top: 90%; left: 90%; transform: translateX(-100%);">
				<input type="button" value="Cancel" onclick="removeQuestionPopup()" style="position: absolute; top: 90%; left: 10%">
				
			</form>
		</div>
		
		<div id="search"><input type="text" id="searchbox" placeholder="Type to search" onkeyup="showResult(this.value)"></div>
		
		<!--Transparent blanket when popup appears-->
		<div id="blanket" onclick="removeQuestionPopup()"></div>
		
		<div id="board"></div>
		
		<div id="questionwindow">
			<p id="question" style="padding: 15px 15px 0px 20px;"></p>
			<div id="answers"></div>
				<form id="formanswer" style="height: 10%; padding: 20px 27px 0px 20px;" autocomplete="on"> <!--Autocomplete on only for input fields that are not passwords-->
				
						<textarea id="answerText" name="answer" style="border: 1px solid; width: 100%; height: 100%; resize: none;" required></textarea>
						<input type="button" value="Cancel" onclick="removeQuestionPopup()" style="margin-top: 5px;">
						<input type="button" value="Post Answer" onclick="postAnswer()" style="margin-top: 5px; float: right;">
					
				</form>
			
		</div>
		
	</div>
	
	
	<!-- Javascript section on the bottom so ensure elements are loaded first -->
	<script src="script.js?<?php echo time(); ?>"></script>
	
	<script> 
		
		var currentData; //To store ajax data
		var index; //To track which question is open
		
		window.onload = listAll();
		
		function listAll(){
		var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			
			xmlhttp.open("Get", url + "?file=all", true);
			xmlhttp.send();
			
			function defaultFunction(response) {
				var arr = JSON.parse(response);
				currentData = arr;
				var i;
				var out = "<table><tr><th style='width: 10%;'>User</th><th  style='width: 10%;'>Date</th><th  style='width: 10%;'>Question</th><th  style='width: 65%;'>Explanation</th><th  style='width: 5%;'></th></tr>";
				for (i = 0; i < arr.length; i++){
					out += "<tr><td>" + 
					arr[i].user +
					"</td><td>" +
					arr[i].datetime + 
					"</td><td>" +
					arr[i].question + 
					"</td><td>" +
					arr[i].explanation +
					"</td><td>" +
					"<button class='reply' onclick='openReply(this)'>Reply</button>" +
					"</td></tr>";
				}
			
				out += "</table>";
			
				document.getElementById("board").innerHTML = out;
				
			}
		}
		
		function listNew(){
		var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			
			xmlhttp.open("Get", url + "?file=all", true);
			xmlhttp.send();
			
			function defaultFunction(response) {
				var arr = JSON.parse(response);
				currentData = arr;
				var i;
				var out = "<table><tr><th style='width: 10%;'>User</th><th  style='width: 10%;'>Date</th><th  style='width: 10%;'>Question</th><th  style='width: 65%;'>Explanation</th><th  style='width: 5%;'></th></tr>";
				for (i = 0; i < arr.length && i < 10; i++){
					out += "<tr><td>" + 
					arr[i].user +
					"</td><td>" +
					arr[i].datetime + 
					"</td><td>" +
					arr[i].question + 
					"</td><td>" +
					arr[i].explanation +
					"</td><td>" +
					"<button class='reply' onclick='openReply(this)'>Reply</button>" +
					"</td></tr>";
				}
			
				out += "</table>";
			
				document.getElementById("board").innerHTML = out;
				
			}
		}
			
		function openReply(e){
			index = e.parentNode.parentNode.rowIndex - 1;
			document.getElementById("questionwindow").style.display = "block";
			document.getElementById("blanket").style.display = "block";
			document.getElementById("question").innerHTML = "User " + currentData[index].user + " asked \"" + currentData[index].question + "\" on " + 
																						currentData[index].datetime + ".<br><br>" + currentData[index].explanation + "<br>";
			
			var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			
			xmlhttp.open("Get", url + "?file=answers&id=" + currentData[index].id, true);
			xmlhttp.send();
			
			function defaultFunction(response) {
				if (response == "empty"){
					document.getElementById("answers").innerHTML = "No submitted answers";
				}
				else {
				
				var arr = JSON.parse(response);
				var i;
				var out = "";
				for (i = 0; i < arr.length; i++){
					out += "User " + arr[i].user + " answered on " + arr[i].datetime + ":<br>" + arr[i].answer + "<br><br>";
				}
					
				document.getElementById("answers").innerHTML = out;
				}
			}
						
		}
		
		function postAnswer(){
			var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			var data = "command=PostAnswer&qid=" + currentData[index].id + "&answer=" + document.getElementById("answerText").value;

			xmlhttp.open("POST", url, true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send(data);
		
			function defaultFunction(response) {
				if (response == "empty"){
					document.getElementById("answers").innerHTML = "No submitted answers";
				}
				else {
				
				var arr = JSON.parse(response);
				var i;
				var out = "";
				for (i = 0; i < arr.length; i++){
					out += "User " + arr[i].user + " answered on " + arr[i].datetime + ":<br>" + arr[i].answer + "<br><br>";
				}
					
				document.getElementById("answers").innerHTML = out;
				}
			}
			
			document.getElementById("answerText").value = '';
		}
		
		function deleteQuestions(){
			var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			
			xmlhttp.open("Get", url + "?file=deleteList", true);
			xmlhttp.send();
			
			function defaultFunction(response) {
				var arr = JSON.parse(response);
				currentData = arr;
				var i;
				var out = "<table><tr><th style='width: 10%;'>User</th><th  style='width: 10%;'>Date</th><th  style='width: 10%;'>Question</th><th  style='width: 65%;'>Explanation</th><th  style='width: 5%;'></th></tr>";
				for (i = 0; i < arr.length; i++){
					out += "<tr><td>" + 
					arr[i].user +
					"</td><td>" +
					arr[i].datetime + 
					"</td><td>" +
					arr[i].question + 
					"</td><td>" +
					arr[i].explanation +
					"</td><td>" +
					"<button class='reply' onclick='deleteQ(this)'>Delete</button>" +
					"</td></tr>";
				}
			
				out += "</table>";
			
				document.getElementById("board").innerHTML = out;
				
			}
		}
		
		function deleteQ(e){
			index = e.parentNode.parentNode.rowIndex - 1;
			var xmlhttp = new XMLHttpRequest();
			var url = "questions.php";
			
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
					defaultFunction(xmlhttp.responseText);
				}
			}
			console.log(currentData[index].id);
			xmlhttp.open("Get", url + "?file=deleteQuestion&qid=" + currentData[index].id, true);
			xmlhttp.send();
			
			function defaultFunction(response) {
				var arr = JSON.parse(response);
				currentData = arr;
				var i;
				var out = "<table><tr><th style='width: 10%;'>User</th><th  style='width: 10%;'>Date</th><th  style='width: 10%;'>Question</th><th  style='width: 65%;'>Explanation</th><th  style='width: 5%;'></th></tr>";
				for (i = 0; i < arr.length; i++){
					out += "<tr><td>" + 
					arr[i].user +
					"</td><td>" +
					arr[i].datetime + 
					"</td><td>" +
					arr[i].question + 
					"</td><td>" +
					arr[i].explanation +
					"</td><td>" +
					"<button class='reply' onclick='deleteQ(this)'>Delete</button>" +
					"</td></tr>";
				}
			
				out += "</table>";
			
				document.getElementById("board").innerHTML = out;
				
			}
		}
		
		function search(){
			if (document.getElementById("search").style.display == "block"){
				document.getElementById("search").style.display = "none";
				document.getElementById("searchtag").innerHTML = "Search Questions";
				listAll();
			}
			else {
				document.getElementById("search").style.display = "block";
				document.getElementById("searchtag").innerHTML = "Hide Search Box";
			}
		}
		
		function showResult(str){
			if (str.length == 0){
				
			}
			else {
				var xmlhttp=new XMLHttpRequest();
					xmlhttp.onreadystatechange = function(){
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
						defaultFunction(xmlhttp.responseText);
					}
				}
				xmlhttp.open("GET","questions.php?file=search&q="+str,true);
				xmlhttp.send();
				
				function defaultFunction(response) {
				console.log(response);
				
				var arr = JSON.parse(response);
				currentData = arr;
				var i;
				var out = "<table><tr><th style='width: 10%;'>User</th><th  style='width: 10%;'>Date</th><th  style='width: 10%;'>Question</th><th  style='width: 65%;'>Explanation</th><th  style='width: 5%;'></th></tr>";
				for (i = 0; i < arr.length; i++){
					out += "<tr><td>" + 
					arr[i].user +
					"</td><td>" +
					arr[i].datetime + 
					"</td><td>" +
					arr[i].question + 
					"</td><td>" +
					arr[i].explanation +
					"</td><td>" +
					"<button class='reply' onclick='openReply(this)'>Reply</button>" +
					"</td></tr>";
				}
			
				out += "</table>";
			
				document.getElementById("board").innerHTML = out;
				
				
			}
			}
		}
	
		
	</script>
	<!------------------------------------------------------------------------------------>
	
</body>
</html>


























