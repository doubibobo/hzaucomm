$(document).ready(function(){
        var oDiv = document.getElementById("slide");
        var oUl = document.getElementsByTagName("ul")[0];
        var oLi = document.getElementsByTagName("li");
        var oDiv2 = document.getElementById("inclu");
        var oA = oDiv2.getElementsByTagName("a");
        var speed = -400;
        function move(){
                if( oUl.offsetTop < (-oUl.offsetHeight + 800)){
                    oUl.style.top = 0 + 'px';
                }
                
                    oUl.style.top = oUl.offsetTop + speed + 'px';   //注意此处的speed
            } 
        var timer = setInterval(move, 2000); 
        oDiv.onmouseover=function ()
        {
            clearInterval(timer);
        };
        oDiv.onmouseout=function ()
        {
            timer=setInterval(move, 2000);
        };

        
        oA[0].onmouseover=function ()
        {
            oUl.style.top = 0 + "px";
            clearInterval(timer);
        };
        oA[1].onmouseover=function ()
        {
            oUl.style.top = -400*(1) + "px";
            clearInterval(timer);
        };
        oA[2].onmouseover=function ()
        {
            oUl.style.top = -400*(2) + "px";
            clearInterval(timer);
        };
        oA[3].onmouseover=function ()
        {
            oUl.style.top = -400*(3) + "px";
            clearInterval(timer);
        };
        oA[4].onmouseover=function ()
        {
            oUl.style.top = -400*(4) + "px";
            clearInterval(timer);
        };
        oA[5].onmouseover=function ()
        {
            oUl.style.top = -400*(5) + "px";
            clearInterval(timer);
        };
        oA[6].onmouseover=function ()
        {
            oUl.style.top = -400*(6) + "px";
            clearInterval(timer);
        };
        oDiv2.onmouseout=function ()
        {
            timer=setInterval(move, 2000);
        };
  
  
});
      