/**
 * 
 */

var Product = Backbone.Model.extend({
	defaults: {
		id			:0,
		code		:"",
		name		:"",
		description :"",
		photo		:"",
		price		:0,
		quantity	:0,
		detailPhotos:[]
	}
});

var Products = Backbone.Collection.extend({
	model: Product,
	initialize: function(){
		console.log("inicializando colleccion!");
	}
});

$(document).ready(function($){
	
	setNumberItemsLocalCart();
	
});

function getData(action, entity){
	
	console.log(JSON.stringify(entity));
	
	var settings = {
		"async": true,
		"crossDomain": true,
		"url": action,
		"method": "POST",
		"headers": {
			"content-type": "application/json"
		},
		"processData": false,
		"data": JSON.stringify(entity)
	}
	
	return $.ajax(settings);
	
}

function updateLocalCart(items){
	
	var localStorageCart = localStorage.getItem("palosanto-cart");
	
	console.log(JSON.parse(localStorageCart));

	localStorage.setItem("palosanto-cart", JSON.stringify(items));
	
	console.log(JSON.parse(localStorage.getItem("palosanto-cart")));
	
	setNumberItemsLocalCart();
	
}

function addItemToLocalCart(item, quantity){
	
	var localStorageCart = localStorage.getItem("palosanto-cart");
	
	var arrayCart = new Array();
	
	if(localStorageCart == null){
		
		console.log("no habia local storage");
		if(quantity == null){
			item["quantity"] = 1;
		}else{
			item["quantity"] = quantity;
		}
		arrayCart.push(item);
		notification("The item: " + item.name + " has been added to the cart.")
		
	}else{
		
		console.log("si habia local storage");
		
		arrayCart = JSON.parse(localStorageCart);
		
		var condition = true;
		
		$(arrayCart).each(function(e, element){
			console.log(element.id + " : " + item.id);
			if(element.id == item.id){
				condition = false;
			}
		});
		
		if(condition == true){
			if(quantity == null){
				item["quantity"] = 1;
			}else{
				item["quantity"] = quantity;
			}
			arrayCart.push(item);
			notification("The item: " + item.name + " has been added to the cart.")
			
		}else{
			
			notification("This item is already in your cart.")
			
		}
		
	}
	
	localStorage.setItem("palosanto-cart", JSON.stringify(arrayCart));
	
	console.log(JSON.parse(localStorage.getItem("palosanto-cart")));
	
	setNumberItemsLocalCart();
	
}

function setNumberItemsLocalCart(number){
	
	var localStorageCart = localStorage.getItem("palosanto-cart");
	
	var arrayCart = new Array();
	
	if(localStorageCart == null){
		
		$("a[href='cart.html'] span:nth-child(2)").text("empty");
		
	}else{
		
		arrayCart = JSON.parse(localStorageCart);
		$("a[href='cart.html'] span:nth-child(2)").text(arrayCart.length + " item(s)");
		
	}
	
}

function notification(text){
	
	$(".notification").removeClass("show");
	$(".notification").addClass("show");
	$(".notification .alert p").text(text);
	
}