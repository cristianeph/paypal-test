/**
 * 
 */
$(document).ready(function($){
	
	setNumberItemsLocalCart();
	
	//getData("/customers/palosanto/php/list.php", null).done(function(data){
	getData("/~cristian/palosanto/php/list.php", null).done(function(data){
		console.log("retorno!!!");
		console.log(data);
		
		var productsCollection = new Products(data);
		
		window.ProductsView = Backbone.View.extend({
			el			: ".list",
			template	: _.template($("#product-template").html()),
			events		: {
				"click .more a[href*='id']": "showItem",
				"click .price a[href*='id']": "addCart"
			},
			showItem : function(event){
				
				event.preventDefault();
				
				alert("click!!!!!!");
				
			},
			addCart : function(event){
				
				event.preventDefault();
				
				var id = $(event.currentTarget).data("id");
				var item = this.collection.get(id);
				
				console.log(item.attributes);
				
				addItemToLocalCart(item.attributes, null);
				
			},
		    initialize	:function(){
		        this.render();
		    },
		    render		: function () {
		    	this.$el.html(this.template({collection:this.collection.toJSON()}));
		    }
		});
		
		window.ShopProductsView = new ProductsView({collection:productsCollection});
		
	});
	
});