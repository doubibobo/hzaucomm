$(function() {
//****************************     登陆、注册    ****************************
  //按钮的透明度
  $(".okbtn").hover(function (){
    $(this).stop().animate({
      opacity: '1'
    }, 600);
  }, function () {
    $(this).stop().animate({
      opacity: '0.6'
    }, 700);
  });

//***********登陆***********
  //弹出登录
  $("#header_right .logBut").on('click', function () {
    $("body").append("<div id='mask'></div>");
    $("#mask").addClass("mask").fadeIn("slow");
    $("#LoginBox").fadeIn("slow");
  });
  //文本框不允许为空---按钮触发
  $("#okbtn").on('click', function () {
    var logName = $("#logName").val();
    var logPwd = $("#logPwd").val();
    if (logName == "" || logName == undefined || logName == null) {
      if (logPwd == "" || logPwd == undefined || logPwd == null) {
        $(".warning").css({ display: 'block' });
      }
      else {
        $("#warn").css({ display: 'block' });
        $("#warn2").css({ display: 'none' });
      }
    }
    else {
      if (logPwd == "" || logPwd == undefined || logPwd == null) {
        $("#warn").css({ display: 'none' });
        $(".warn2").css({ display: 'block' });
      }
      else {
        $(".warning").css({ display: 'none' });
      }
    }
  });
  //文本框不允许为空---单个文本触发
  $("#logName").on('blur', function () {
    var logName = $("#logName").val();
    if (logName == "" || logName == undefined || logName == null) {
      $("#warn").css({ display: 'block' });
    }
    else {
      $("#warn").css({ display: 'none' });
    }
  });
  $("#logName").on('focus', function () {
    $("#warn").css({ display: 'none' });
  });
  //
  $("#logPwd").on('blur', function () {
    var logName = $("#logPwd").val();
    if (logName == "" || logName == undefined || logName == null) {
      $("#warn2").css({ display: 'block' });
    }
    else {
      $("#warn2").css({ display: 'none' });
    }
  });
  $("#logPwd").on('focus', function () {
    $("#warn2").css({ display: 'none' });
  });
  //关闭
  $(".close_btn").hover(function () { $(this).css({ color: 'black' }) },
   function () { $(this).css({ color: '#999' }) }).on('click', function () {
    $("#LoginBox").fadeOut("fast");
    $("#mask").css({ display: 'none' });
  });
  
//***********注册***********
  //弹出登录
  $(".reg_link").on('click', function () {
    $("body").append("<div id='mask'></div>");
    $("#mask").addClass("mask").fadeIn("slow");
    $("#RegisterBox").fadeIn("slow");
  });
  //关闭
  $(".close_btn").hover(function () { $(this).css({ color: 'black' }) }, 
  	function () { $(this).css({ color: '#999' }) }).on('click', function () {
    $("#RegisterBox").fadeOut("fast");
    $("#mask").css({ display: 'none' });
  });

});	