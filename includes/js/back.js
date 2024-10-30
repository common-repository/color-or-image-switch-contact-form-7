jQuery( document ).ready(function() {
	//jQuery("#hide_cat_box").show();
    jQuery('body').on('change','input[name=type]:checked',function(){
	    var value = jQuery(this).val();
	    if(value == "ciscf7_image"){
	    	jQuery("#hide_cat_boxx").hide();
	    	jQuery("#hide_img_box").show();
	    }else{
	    	jQuery("#hide_cat_boxx").show();
	    	jQuery("#hide_img_box").hide();
	    }
	});
	jQuery('body').on('keyup', '.shhh', function() {
	  	jQuery(".oc_tooltip_cls").trigger("click");
	});
});



jQuery( document ).ready(function() {
	jQuery('.add_more_img').click(function(){
		var minNumber = 100;
		var maxNumber = 100000

		var randomNumber = randomNumberFromRange(minNumber, maxNumber);

		function randomNumberFromRange(min,max)
		{
		    return Math.floor(Math.random()*(max-min+1)+min);
		}
		jQuery.ajax({
	        url:ajax_url,
            type:'POST',
	        data: { 'random_number': randomNumber },
	        success : function(response) {
	        	setTimeout(function(){ jQuery('#hide_img_box .last_add_more').before(response); }, 300);
	           
	        },
	        error: function() {
	            
	        }
	    });


	}); 
}); 