// Set array to count modal product quantity
var countQty = [];
$(".content").niceScroll();
$(".modal-body").niceScroll();

// Get File Location
function pluginURL(){
	var plugin_url = pluginScript.pluginsUrl;
	return plugin_url;
}

// To add product on the list function
function addElement(elem){
	event.preventDefault();
	// Get button value
	var id = $(elem).attr("value");
	// Get Value of items on the modal product list
	var modal_product_imgSrc = $('#modal_product_image_' + id).attr('src');
	var modal_product_name = $('#modal_product_name_' + id).html();
	var modal_product_order_id = $('#modal_order_id_' + id).val();
	var modal_product_order_item_id = $('#modal_order_item_id_' + id).val();
	var modal_product_productQty = $('#modal_productQty_' + id).val();

	// Storing value in array
	countQty.push(modal_product_productQty);
	
	// Sum all the quantity in the array
	// If statement
	if(eval(countQty.join('+')) < 10){

		appendSingleProduct(id, modal_product_order_id, modal_product_order_item_id, modal_product_imgSrc, modal_product_name, modal_product_productQty);

	}else if(eval(countQty.join('+')) > 10){
		
		$.confirm({
		    title: '<style="">Warning!',
		    content: 'Maximum Number of Product Quantity Reached. Limited to 10 Products. Please add the exact quantity. Current Product Quantity Count : ' + + eval(countQty.join('+')),
		    buttons: {
		        Ok: function () {
		        	countQty.splice(-1,1)
		        }
		    }
		});		

	}else if(eval(countQty.join('+')) == 10){

		appendSingleProduct(id, modal_product_order_id, modal_product_order_item_id, modal_product_imgSrc, modal_product_name, modal_product_productQty);
		

		$.confirm({
		    title: 'Success!',
		    content: 'Maximum Number of Product Quantity Reached. Limited to 10 Products. Current Product Quantity Count : ' + eval(countQty.join('+')),
		    buttons: {
		        Ok: function () {
		        	$('.modalButton').prop('disabled', true);
					$('#recycle-submit').removeAttr("disabled");
		        }
		    }
		});

	}
	
	// Delete item on the product list
    $("#buttonDelete_"+ id).click(function(){

    	// Get Value of items on the product list
		var product_order_id = $('#order_id_' + id).val();
		var product_order_item_id = $('#order_item_id_' + id).val();
		var product_imgSrc = $('#product_img_' + id).attr('src');
		var product_name = $('#product_name_' + id).html();
		var product_productQty = $('#productQty_' + id).val();

		// Get the index number of the product to delete
		var index = countQty.indexOf(product_productQty);
		if (index !== -1) countQty.splice(index, 1);

		// Append deleted product in modal
		appendModalProduct(id, product_order_id, product_order_item_id, product_imgSrc, product_name, product_productQty);

		// Remove modal attribute disabled
    	$('.modalButton').removeAttr("disabled");

	});

}

// To append product on the product list and remove in modal product list
function appendSingleProduct(id, modal_product_order_id, modal_product_order_item_id, modal_product_imgSrc, modal_product_name, modal_product_productQty){

	$(".product-list .content").append(
		'<div class="row single-product single-product-'+id+'">'+
			'<div class="col-md-8">'+
				'<div class="product-info flex">'+
					'<input type="hidden" name="order_id[]" id="order_id_'+id+'" value="'+ modal_product_order_id +'">'+
					'<input type="hidden" name="order_item_id[]" id="order_item_id_'+id+'" value="'+ modal_product_order_item_id +'">'+
					'<img src="' + modal_product_imgSrc + '" alt="' + modal_product_name + '" id="product_img_'+id+'">'+
					'<input type="text" value="' + modal_product_imgSrc + '" name="product_img[]" hidden>'+
					'<div class="product-details">'+
						'<h4 name="product_name" id="product_name_'+id+'">' + modal_product_name + '</h4>'+
						'<input type="text" name="product_name[]" id="product_name_'+id+'" value="' + modal_product_name + '" hidden>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="col-md-4">'+
			'<div class="product-qty">'+
				'<input type="number" class="product-quantity form-control" id="productQty_'+id+'" name="product_qty[]" data-toggle="tooltip" title="Add Quantity" value="' + modal_product_productQty + '" placeholder="' + modal_product_productQty + '" readonly>'+
				'<button type="button" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Delete Product" id="buttonDelete_'+id+'" value="'+id+'">'+
					'<i class="fa fa-times"></i>'+
				'</button>'+
			'</div>'+
			'</div>'+
		'</div>'
	);

	$('.modal-product-'+ id).fadeOut("fast", function() {
        $(this).remove();
    });

}

// To append product in Modal and remove from the product list
function appendModalProduct(id, product_order_id, product_order_item_id, product_imgSrc, product_name, product_productQty){

	$("#selectProductModal .modal-body").append(
		'<div class="row modal-product modal-product-'+id+'">'+
			'<div class="col-md-8">'+
				'<div class="product-info flex">'+
					'<img src="'+product_imgSrc+'" alt="'+product_name+'" id="modal_product_image_'+id+'">'+
						'<div class="product-details">'+
							'<h4 id="modal_product_name_'+id+'">'+product_name+'</h4>'+
						'</div>'+
				'</div>'+
			'</div>'+
			'<div class="col-md-4">'+
				'<div class="product-qty">'+
					'<input type="hidden" id="modal_order_id_'+id+'" value="'+product_order_id+'">'+
					'<input type="hidden" id="modal_order_item_id_'+id+'" value="'+product_order_item_id+'">'+
					'<input type="number" class="form-control" id="modal_productQty_'+id+'" placeholder="'+product_productQty+'" value="'+product_productQty+'">'+
					'<button type="button" class="modalButton btn btn-success btn-circle" id="modal_buttonAdd_'+id+'" value="'+id+'">'+
						'<i class="fa fa-plus"></i>'+
					'</button>'+
				'</div>'+
			'</div>'+
		'</div>'
	);

	$('.single-product-'+ id).fadeOut("fast", function() {
        $(this).remove();
    });

}

$(function() {
    
	$("#loader").hide();
    $('#form-recycle').on('submit', function(event) { 
        event.preventDefault();
        $("#loader").show();
        var data = $( "#form-recycle" ).serialize();
        jQuery.ajax({
        	dataType: "json",
        	type : "POST",
        	data : data,
        	url : pluginURL() + "templates/submit/submit.template.php",
        	success: success,
        	error: printError
        });

    });

    var success = function( resp ){
    	alert("Form is Submitted Successfully");
    };

    var printError = function( req, status, err ) {
    	$("#loader").hide();
    	$.confirm({
		    title: 'Bidi Recycle Submitted Succeffully!',
		    content: 'Thank You For Using Bidi Recycle!\nPlease wait for further Information regarding the Recycle Request',
		    buttons: {
		        Ok: function () {
		            location.reload();
		        }
		    }
		});
	};
    
	var searchValue = $('#recycle-search-input').val();	
	if( !searchValue ){
		var txt = $(this).val();
		$('result').html('');
		$.ajax({
			url : pluginURL() + "templates/submit/fetchrecycle.template.php",
			method: "POST",
			data: {data: txt},
			dataType: "html",
			success: function(data){
				$('#the-list2').html(data);
			}
		});

	}

	$('#recycle-search-input').keyup(function(){
		$('#the-list2').hide();
		var txt = $(this).val();
		$('result').html('');
		$.ajax({
			url : pluginURL() + "templates/submit/fetchrecycle.template.php",
			method: "POST",
			data: {data: txt},
			dataType: "html",
			success: function(data){
				$('#the-list').html(data);
			}
		});		
	});
});