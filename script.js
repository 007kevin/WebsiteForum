
/*------------------------Questions Script-------------------------*/







/*-------------------------------------------------------------------*/
/* Server sent events for updating client time every second*/
if (typeof(EventSource) !== "undefined"){
	var source = new EventSource("time.php");
	source.onmessage = function(event) {
		document.getElementById("date").innerHTML = "<b>[" + event.data + "]</b>";
	}
}
else {
	document.getElementById("date").innerHTML = "sse not supported";
}


/*Variable to store the "setInterval" id for pausing/resuming movement of moving image*/
var intervalID;

/*Function will display the corresponding pop up element on top of the blanket div. At the same time, the moving image will start moving*/
function displayPopup(element){
	var elem = document.getElementById(element);
	elem.style.display = "block";
	document.getElementById("blanket").style.display = "block";
	
	/*Alternate image*/
	if (element != "postquestion"){
	document.getElementById("movingImage").src = "boo.png";
	intervalID = setInterval(moveImage, 1000);
	}
}

function removeQuestionPopup(){
	document.getElementById("blanket").style.display = "none";
	document.getElementById("questionwindow").style.display = "none";
	var elem = document.getElementById("postquestion").style.display = "none";
	document.getElementById("formquestion").reset();
}

/*Function will remove the pop up box as well as the div blanket. At the same time, the moving image will become still*/
function removePopup(){
	document.getElementById("blanket").style.display = "none";
	document.getElementById("join").style.display = "none";
	document.getElementById("signin").style.display = "none";
	document.getElementById("forgotpassword").style.display = "none";
	
	/*Alternate image*/
	document.getElementById("movingImage").src = "boo_hide.png";
	window.clearInterval(intervalID);
}

	





/*For the document's onmousemove event, will set the mouse position to the global
  variables window.mouseX and window.mouseY. Mouse position can only be retrieved from an event,
  so the position of the mouse will have to be set in the global variable so the timed function moveImage()
  can use the information */
document.onmousemove = function(e) {
	var event = e || window.event;
	window.mouseX = event.clientX;
	window.mouseY = event.clientY;
};


/*Moves the image in the div container 5px per second towards the mouse and will stay within the container*/
function moveImage(){
	
	var imageBox = document.getElementById("divImage");
	var image = document.getElementById("movingImage");
	
	/*image.clientWidth/2 implemented in the code since the image element is positioned by the "transform = translate(50%, 50%)" in style.css*/
	if (image.clientWidth/2 < image.offsetLeft && image.offsetLeft < imageBox.clientWidth - image.clientWidth/2){
		if (window.mouseX > image.offsetLeft + imageBox.offsetLeft - (imageBox.clientWidth / 2)){
			/* following if statement will prevent the image from leaving the X range coordinates it must stay within*/
			if (image.offsetLeft + 5 < imageBox.clientWidth - image.clientWidth/2)
				image.style.left = (image.offsetLeft + 5) + "px";
		}
		else{
			if (image.offsetLeft - 5 > image.clientWidth/2)
				image.style.left = (image.offsetLeft - 5) + "px";
		}
	}
	
	if (image.clientHeight/2 < image.offsetTop && image.offsetTop < imageBox.clientHeight - image.clientHeight/2){
		if (window.mouseY > image.offsetTop + imageBox.offsetTop - (imageBox.clientHeight / 2)){
			if (image.offsetTop + 5 < imageBox.clientHeight - image.clientHeight/2)
				image.style.top = (image.offsetTop + 5) + "px";
		}
		else{
			if (image.offsetTop - 5 > image.clientHeight/2)
				image.style.top = (image.offsetTop - 5) + "px";
		}
	}
	
	
}



