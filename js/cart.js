/**
 * 
 */
$(document).ready(function($){
	
	var localStorageCart = localStorage.getItem("palosanto-cart");
	
	var arrayCart = new Array();
	
	if(localStorageCart == null){
		
		alert("The cart is empty");
		
	}else{
		
		arrayCart = JSON.parse(localStorageCart);
		
		var productsCollection = new Products(arrayCart);
		
		window.ProductsView = Backbone.View.extend({
			el			: "#cart",
			template	: _.template($("#product-cart-template").html()),
			events 		: {
				"click .delete a":"removeCart"
			},
			removeCart 	: function(event){
				
				event.preventDefault();
				
				var id = $(event.currentTarget).attr("href").split("=")[1];
				
				$("table tbody tr[data-row='" + id + "']").remove();
				
				var item = this.collection.get(parseInt(id));
				console.log(item);
				//item.destroy();
				this.collection.remove(item);
				console.log(this.collection);
				
				alert("remove: " + id);
				
				$("table tbody tr").each(function(i, item){
					
					$(this).find(".item span").text("# " + (i + 1));
					
				});
				
				var temporarycollection = [];
				
				$(this.collection.models).each(function(i, item){
					
					temporarycollection.push(item.attributes);
					
				});
				
				updateLocalCart(temporarycollection);
				
			},
			initialize	: function(){
		        this.render();
		    },
		    render		: function () {
		    	this.$el.html(this.template({collection:this.collection.toJSON()}));
		    }
		});
		
		window.ShopProductsView = new ProductsView({collection:productsCollection});
		
	}
	
	$("#user_info").submit(function(event){
		
		event.preventDefault();
		
		var details = [];
		
		$(".list table tbody tr").each(function(i, item){
			
			var item = {
				"idProduct" : $(item).data("row-value"),
				"quantity" : $(item).find("td.quantity span").text()
			}
			
			details.push(item)
			
		});
		
		console.log(details);
		
		var parameters = {
			"firstnames":$("input[name='firstnames']").val(),
			"lastnames":$("input[name='lastnames']").val(),
			"email":$("input[name='email']").val(),
			"address1":$("input[name='address1']").val(),
			"address2":$("input[name='address2']").val(),
			"country":$("input[name='country']").val(),
			"postalcode":$("input[name='postalcode']").val(),
			"phone":$("input[name='phone']").val(),
			"total":$("input[name='total']").val(),
			"series":$("input[name='series']").val(),
			"details" : details
		}
		
		$("#payment").show();
		
		/*getData("/~cristian/palosanto/php/cart.php", parameters).done(function(data){
		//getServerData("/customers/palosanto/php/cart.php", parameters).done(function(data){
			
			console.log(data);
			
			if(data == "SUCCESS"){
				
				$("#payer").hide();
				$(".form .actions").hide();
				$("#payment").show();
				
				var total = $("#cart tfoot .valuetotal span").html();
				
				$("#payment div h1").html("Mr(s). " + parameters.lastnames + " are you agree to charge " + total + " into your Paypal account?")
				
				$("#payment input[name='amount']").val(parseFloat(total));
				$("#payment input[name='first_name']").val(parameters.lastnames);
				$("#payment input[name='last_name']").val(parameters.lastnames);
				$("#payment input[name='payer_email']").val(parameters.email);
				$("#payment input[name='item_number']").val("Goods from Palo Santo");
			}
			
		});*/
		
	});
	
	var header = $("nav");
	
	$(".actions a").click(function(){
		
		var element = $(this);
		var reference = $(this).attr("href");
		var objective = $(reference).position().top;
		
		$("html, body").animate({scrollTop: (objective - header.height()) + "px"}, 800);
		
	});
	
});

function getServerData(action, parameters){

    return $.ajax({
        data:  parameters,
        url:   action,
        type:  'post'
    });
    
}