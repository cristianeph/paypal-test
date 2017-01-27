/**
 * 
 */
$(document).ready(function($){
	
	getData("/~cristian/palosanto/php/checkout.php", null).done(function(data){
	//getData("/customers/palosanto/php/checkout.php", null).done(function(data){
		
		console.log(data);
		
		$(".check h1").text("Mr(s). " + data.lastnames + " we have place an order with number: " + data.series + ", please check your email.")
		
	});
	
});