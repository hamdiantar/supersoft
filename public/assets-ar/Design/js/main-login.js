'use strict';

$(window).on('load', function() { 
	/*------------------
		Preloder
	--------------------*/
	$(".loader").fadeOut(); 
	$("#preloder").delay(400).fadeOut("slow");
});



(function($){
	/*------------------
		TYPED JS
	--------------------*/
	$(".element").typed({
		strings: ["نظام إداري متكامل", "نظام حسابات متكامل", " لإدارة الشركات والمؤسسات"],
		typeSpeed: 10,
		loop:true,
		backDelay: 2000
	});
	//$(".element2").typed({
	//	strings: ["ليس لديك الصلاحية للدخول باسم هذا المستخدم"],
	//	typeSpeed: 10,
	//	loop:true,
	//	backDelay: 2000
	//});
})(jQuery);




$(".user").focusin(function(){
  $(".inputUserIcon").css("color", "#2295f2");
}).focusout(function(){
  $(".inputUserIcon").css("color", "white");
});

$(".pass").focusin(function(){
  $(".inputPassIcon").css("color", "#2295f2");
}).focusout(function(){
  $(".inputPassIcon").css("color", "white");
});


$('.play').click(function(){
	var $this = $(this);
	var id = $this.attr('id').replace(/btn/, '');
	$this.toggleClass('active');
	if($this.hasClass('active')){
		$this.text(''); 
		$('audio[id^="sound"]')[id-1].play();        
	} else {
		$this.text('');
		$('audio[id^="sound"]')[id-1].pause();
	}
});

