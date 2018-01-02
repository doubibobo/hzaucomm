        var curIndex=0;
        //时间间隔 单位毫秒
        var timeInterval=1500;
        
        setInterval(changeImg,timeInterval);
        function changeImg()
    {
            var obj=document.getElementById("obj");
          if (curIndex==arr.length-1) 
            {
                curIndex=0;
            }
            else
            {
                curIndex+=1;
            }
            obj.src=arr[curIndex];
    }

    // 注册function
    $(function ($) {
        //弹出注册
        $("#register").hover(function () {
            $(this).stop().animate({
                opacity: '1'
            }, 600);
        }, function () {
            $(this).stop().animate({
                opacity: '0.6'
            }, 1000);
        }).on('click', function () {
            $("body").append("<div id='mask'></div>");
            $("#mask").addClass("mask").fadeIn("slow");
            $("#registerBox").fadeIn("slow");
        });
        //
        //按钮的透明度
        $("#r_loginbtn").hover(function () {
            $(this).stop().animate({
                opacity: '1'
            }, 600);
        }, function () {
            $(this).stop().animate({
                opacity: '0.8'
            }, 1000);
        });
        //文本框不允许为空---按钮触发
        /*$("#r_loginbtn").on('click', function () {
           
            }
        });*/
        $(".close_btn").hover(function () { $(this).css({ color: 'black' }) },
         function () { $(this).css({ color: '#999' }) }).on('click', function () {
            $("#AdminBox").fadeOut("fast");
            $("#mask").css({ display: 'none' });
        });
        //关闭
        $(".close_btn").hover(function () { $(this).css({ color: 'black' }) }, 
            function () { $(this).css({ color: '#999' }) }).on('click', function () {
            $("#registerBox").fadeOut("fast");
            $("#mask").css({ display: 'none' });
        });
    });