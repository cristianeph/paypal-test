/**
 * 
 */

var product = null;

$(document).ready(function(){
	
	if($.getUrlVar("id") != null){
		
		var parameters = {
			"id" : $.getUrlVar("id")
		};
		
		//getData("/customers/palosanto/php/show.php?id=" + parameters.id, null).done(function(data){
		getData("/~cristian/palosanto/php/show.php?id=" + parameters.id, null).done(function(data){
			
			console.log(data);
			
			product = new Product(data);
			
			window.ProductView = Backbone.View.extend({
				el			: '.product',
				template	: _.template($('#product-template').html()),
				events 		: {
					"click .cart a":"addCart",
					"click .photos a":"photoClick",
					"click .information a":"topicClick"
				},
				addCart : function(event){
					
					event.preventDefault();
					
					var quantity = $(".options select").val();
					
					addItemToLocalCart(product.attributes, parseInt(quantity));
					
				},
				photoClick : function(event){
					
					event.preventDefault();
					
					console.log("click a imagen miniatura");
					
					var element = $(event.currentTarget);
					var src = element.find("img").attr("src");
					var picture = $(".photos div img").attr("src",src);
					
				},
				topicClick : function(event){
					
					event.preventDefault();
					
					console.log("click al topic");
					
					var element = $(event.currentTarget);
					var reference = element.attr("href");
					var topic = $(".information").find("[data-reference='" + reference.substring(1, reference.length) + "']");
					
					$(".information .topics ul li").each(function(i, item){
						
						$(item).find("a").removeClass("selected");
						
					});
					
					element.addClass("selected");
					
					$(".information .more").each(function(i, item){
						
						$(item).removeClass("show");
						$(item).addClass("hidden");
						
					});
					
					topic.removeClass("hidden");
					topic.addClass("show");
					
				},
				finishRender : function(){
					console.log("termino de actualizar");
					console.log($(".detail .photos ul li"));
					$(".information .topics ul li:first-child a").trigger("click");
				},
			    initialize	: function(){
			        this.render();
			        this.finishRender();
			    },
			    render		: function () {
			    	this.$el.html(this.template(this.model.toJSON()));
			    }
			});
			
			window.MyProductView = new ProductView({model:product});
			
		});
		
	}
	
});