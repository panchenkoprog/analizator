// панели авторизации и регистрации
$(document).ready(function(){
	$("#panel-login").hide();
	$("#sub-login").click(function(){
            if ($("#panel-reg").is(":visible")){
			$("#panel-reg").slideToggle();
		};
			$(".but-reg").css('background','#207F60');
			$(".but-log").css('background','#FFB840');
			$("#panel-login").slideToggle();
	});
        
        
	
	$("#panel-reg").hide();
	$("#sub-reg").click(function(){
		if ($("#panel-login").is(":visible")){
			$("#panel-login").slideToggle();
		};
			$(".but-log").css('background','#207F60');
			$(".but-reg").css('background','#FFB840');
			//$(".button-reg").css('box-shadow','none');
			//$(".button-vhod").css('box-shadow','0 2px 5px rgba(0, 0, 0, 0.3) inset');
			$("#panel-reg").slideToggle();			
	});
	
/* end */	
	
	 var url=document.location.href;
	 $.each($("ul.nav-top a"),function(){
	  if(this.href==url){$(this).parent().addClass('active');};
	 });
});