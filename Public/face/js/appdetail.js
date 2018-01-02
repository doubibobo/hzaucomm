window.onload = function () {
	var Replay = document.getElementsByClassName('replay');
	var Form   = document.getElementById('reply_h');
	var Textarea = document.getElementById('textarea');

	Form.style.top = ((document.body.offsetHeight)-350)/2 + "px";
	Form.style.left = ((document.body.offsetWidth)-260)/2 + "px";

	for (var i = Replay.length - 1; i >= 0; i--) {
		Replay[i].onclick = function () {
			Form.style.display = "block";
			// Textarea.placeholder = "huifugei";//更改回复人提示
			document.getElementById('Cheader').style.filter = 'blur(2px) ';
			document.getElementById('Cnav').style.filter    = 'blur(2px) ';
			document.getElementById('Cmain').style.filter   = 'blur(2px) ';
			document.getElementById('Cfooter').style.filter = 'blur(2px) ';

		}
	}

	
}