
window.onload = function () {
	var Replay = document.getElementsByClassName('replay');
	var Form   = document.getElementById('reply_h');
	var Textarea = document.getElementById('textarea');

	var addButton = document.getElementsByClassName("addbutton");

	var addForm = document.getElementById("add_comment");
	var header = document.getElementById('Cheader');
	var nav = document.getElementById('Cnav');
	var main = document.getElementById('Cmain');
	var footer = document.getElementById('Cfooter');

	Form.style.top = ((document.body.offsetHeight)-350)/2 + "px";
    Form.style.left = ((document.body.offsetWidth)-260)/2 + "px";
    addForm.style.top = ((document.body.offsetHeight)-350)/2 + "px";
    addForm.style.left = ((document.body.offsetWidth)-260)/2 + "px";

    addButton[0].onclick = function () {
        addForm.style.display = "block";
        header.style.filter = 'blur(2px) ';
        nav.style.filter    = 'blur(2px) ';
        main.style.filter   = 'blur(2px) ';
        footer.style.filter = 'blur(2px) ';
        document.body.scrollTop = 0 +"px";
    }
    console.log(addButton);
    for (var i = Replay.length - 1; i >= 0; i--) {
        Replay[i].onclick = function () {
            Form.style.display = "block";
            // Textarea.placeholder = "huifugei";//更改回复人提示
			header.style.filter = 'blur(2px) ';
            nav.style.filter    = 'blur(2px) ';
            main.style.filter   = 'blur(2px) ';
			footer.style.filter = 'blur(2px) ';
            document.body.scrollTop = 0 +"px";
        }
    }

}