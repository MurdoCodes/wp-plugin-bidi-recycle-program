// Set array to count modal product quantity
var countQty = [];
$(".content").niceScroll();
$(".modal-body").niceScroll();

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

		alert("Maximum Number of Product Quantity Reached. Limited to 10 Products. Please add the exact quantity. Current Product Quantity Count : " + eval(countQty.join('+')));		
		countQty.splice(-1,1)

	}else if(eval(countQty.join('+')) == 10){

		appendSingleProduct(id, modal_product_order_id, modal_product_order_item_id, modal_product_imgSrc, modal_product_name, modal_product_productQty);
		$('.modalButton').prop('disabled', true);
		$('#recycle-submit').removeAttr("disabled");
		
		alert("Maximum Number of Product Quantity Reached. Limited to 10 Products. Current Product Quantity Count : " + eval(countQty.join('+')));

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
					'<input type="hidden" name="order_id" id="order_id_'+id+'" value="'+ modal_product_order_id +'">'+
					'<input type="hidden" name="order_item_id" id="order_item_id_'+id+'" value="'+ modal_product_order_item_id +'">'+
					'<img src="' + modal_product_imgSrc + '" alt="' + modal_product_name + '" id="product_img_'+id+'" name="product_img">'+
					'<input type="text" value="' + modal_product_imgSrc + '" name="product_img" hidden>'+
					'<div class="product-details">'+
						'<h4 name="product_name" id="product_name_'+id+'">' + modal_product_name + '</h4>'+
						'<input type="text" name="product_name" id="product_name_'+id+'" value="' + modal_product_name + '" hidden>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="col-md-4">'+
			'<div class="product-qty">'+
				'<input type="number" class="product-quantity form-control" id="productQty_'+id+'" name="productQty" data-toggle="tooltip" title="Add Quantity" value="' + modal_product_productQty + '" placeholder="' + modal_product_productQty + '" disabled>'+
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

$(function () {

    $('#form-recycle').on('submit', function (e) {
      e.preventDefault();

      var datastring = $('#form-recycle').serialize()

      console.log(datastring);

     $.ajax({
	    type: "POST",
	    url: "your url.php",
	    data: datastring,
	    dataType: "json",
	    success: function(data) {
	        //var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
	        // do what ever you want with the server response
	    },
	    error: function() {
	        alert('error handling here');
	    }
	});

    });

});