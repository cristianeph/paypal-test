/**
 * 
 */
$(document).ready(function($){
	
	var mainSlide = setInterval(function(){
		
		var actualSlide = $(".slide .topic.visible").index();
		var nextSlide = actualSlide + 1;
		var totalSildes = $(".slide .topic").length;
		
		$(".slide .topic").removeClass("visible");
		$(".slide .topic").addClass("novisible");
		
		if(nextSlide < totalSildes){
			
			$(".slide .topic").eq(nextSlide).removeClass("novisible");
			$(".slide .topic").eq(nextSlide).addClass("visible");
			
		}else{
			
			$(".slide .topic").eq(0).removeClass("novisible");
			$(".slide .topic").eq(0).addClass("visible");
			
		}
		
	}, 5000);
	
});