
window.onload = function () {
	//省略文段
	var l = 160;
	var Htexd_c = document.getElementsByClassName("htexd_c");
    for (var i = Htexd_c.length - 1; i >= 0; i--) {
        var string = Htexd_c[i].innerHTML;
        // console.log(Htexd_c[i].nodeValue);
        // console.log(Htexd_c[i].innerHTML);
        Htexd_c[i].innerHTML = checkString(string,l,"...");
	}	
	function checkString (string,l,tag) {
		if (string.length > l) {
			return string.substring(0,l)+tag;
		}
	}
}
 