
window.onload = function () {
	//省略文段
	var setButton = document.getElementById('pwreset');
	var closeSet  = document.getElementById('close2');
	var getButton = document.getElementById('pwgetback');
	var closeGet  = document.getElementById('close1');

	setButton.onclick = function() {
		var oDIV = document.getElementById('reset');
		oDIV.style.display = "block";
		oDIV.style.top  = ((document.body.offsetHeight- oDIV.offsetHeight )/2) + "px";
		oDIV.style.left = ((document.body.offsetWidth- 450 )/2) + "px";

		document.getElementById('Cheader').style.filter = 'blur(2px) ';
		document.getElementById('Cnav').style.filter    = 'blur(2px) ';
		document.getElementById('Cmain').style.filter   = 'blur(2px) ';
		document.getElementById('Cfooter').style.filter = 'blur(2px) ';
	}
	closeSet.onclick = function() {
		var oDIV = document.getElementById('reset');
		document.getElementById('Cheader').style.filter = 'blur(0px) ';
		document.getElementById('Cnav').style.filter    = 'blur(0px) ';
		document.getElementById('Cmain').style.filter   = 'blur(0px) ';
		document.getElementById('Cfooter').style.filter = 'blur(0px) ';
		oDIV.style.display = "none";
	}
	getButton.onclick = function() {
		var oDIV = document.getElementById('getback');
		oDIV.style.display = "block";
		oDIV.style.top  = ((document.body.offsetHeight- oDIV.offsetHeight )/2) + "px";
		oDIV.style.left = ((document.body.offsetWidth- 450 )/2) + "px";

		document.getElementById('Cheader').style.filter = 'blur(2px) ';
		document.getElementById('Cnav').style.filter    = 'blur(2px) ';
		document.getElementById('Cmain').style.filter   = 'blur(2px) ';
		document.getElementById('Cfooter').style.filter = 'blur(2px) ';
	}
	closeGet.onclick = function() {
		var oDIV = document.getElementById('getback');
		document.getElementById('Cheader').style.filter = 'blur(0px) ';
		document.getElementById('Cnav').style.filter    = 'blur(0px) ';
		document.getElementById('Cmain').style.filter   = 'blur(0px) ';
		document.getElementById('Cfooter').style.filter = 'blur(0px) ';
		oDIV.style.display = "none";
	}
}