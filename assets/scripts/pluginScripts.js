// Set array to count modal product quantity
var countQty = [];
var countItemQty = [];
$(".content").niceScroll();
$(".modal-body").niceScroll();

/** Get File Location **/
function pluginURL(){
	var plugin_url = pluginScript.pluginsUrl;
	return plugin_url;
}

/** Function To add product on the list **/
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
		    content: 'Maximum Number of Product Quantity Reached. Limited to 10 Products. Please add the exact quantity. Product Quantity Count if Item Is Added : ' + + eval(countQty.join('+')),
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

/** To append product on the product list and remove in modal product list **/
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


    var itemQtyInList = $('#productQty_' + id).val();
    countItemQty.push(itemQtyInList);
    var totalItemQty = eval(countItemQty.join('+'));
    
	returnStampsValue(totalItemQty);

}

function returnStampsValue(totalItemQty){

	var fixedItemWeight = 0.5;
    var totalItemWeight = totalItemQty * fixedItemWeight;

    var from_firstname = $("input[name=from_firstname]").val();
	var from_lastName = $("input[name=from_lastName]").val();	
	var from_address = $("input[name=from_address]").val();	
	var from_city = $("input[name=from_city]").val();
	var from_state = $("input[name=from_state]").val();
	var from_postcode = $("input[name=from_postcode]").val();
	var from_phone_number = $("input[name=from_phone_number]").val();
	var from_email = $("input[name=from_email]").val();

	var data = {
		from_firstname : from_firstname,
		from_lastName : from_lastName,
		from_address : from_address,
		from_city : from_city,
		from_state : from_state,
		from_postcode : from_postcode,
		from_phone_number : from_phone_number,
		from_email : from_email,
		totalItemQty : totalItemQty,
		totalItemWeight : totalItemWeight
	};

	console.log(data);

	$.ajax({
		url: pluginURL() + 'templates/submit/stampsSubmit.template.php',
	    method: 'POST',	    
	    data: data,
	    dataType:"JSON",
	    success: function(response) {       	
	       	$('input[name=ServiceType]').val(response.PackageType);
	       	$('input[name=ServiceDescription]').val(response.ServiceDescription);
	       	$(".serviceType").html(response.ServiceDescription + "/" + response.PackageType);

	       	var maxAmount = parseFloat(response.MaxAmount);
	       	$('input[name=returnedRate]').val(response.MaxAmount);
	       	$(".returnedRate").html("$" + maxAmount.toFixed(2));

	       	$('input[name=totalItemQty]').val(totalItemQty);

			$('input[name=totalItemWeight]').val(response.WeightOz);
			$(".totalItemWeight").html(totalItemQty + " Items * " + "0.5oz/stick = " + response.WeightOz + "oz");

			$('input[name=DeliverDays]').val(response.DeliverDays);
			$(".DeliverDays").html(response.DeliverDays);

			$('input[name=ShipDate]').val(response.ShipDate);
			$(".ShipDate").html(response.ShipDate);
	       	
	       	console.log(response);
	    }
	});

}


/** To append product in Modal and remove from the product list **/
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

/** Function Not To Exeed Product Count **/
function getModalProdQty(){
	var modalValue = event.srcElement.value;
	$('input.modal_productQty').on('input',function(e){
	 	var value = $( this ).val();
	 	if(value > modalValue){
			$(this).val(modalValue);
	 		$.confirm({
			    title: 'Warning!',
			    content: 'You cannot exceed more than ' + modalValue + ' quantity',
			    buttons: {
			        Ok: function () {
			        }
			    }
			});

	 	}

	 	if(value == 0){
			$(this).val('1');
	 		$.confirm({
			    title: 'Warning!',
			    content: 'You cannot add less than 1 quantity',
			    buttons: {
			        Ok: function () {
			        }
			    }
			});

	 	}
	});
}


$(function() {
	/** START DATE PICKER **/
	$('#card_exp_month').datepicker( {
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm',
		minDate: 0,
		onClose: function(dateText, inst) { 
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
		}
	});
	$("#card_exp_month").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
	/** END DATE PICKER **/
    /** Start Front End Form Submission **/
	    // Hide Loader
		$("#loader").hide();
		// Submit Front End Form
	    $('#form-recycle').on('submit', function(event) { 
	        event.preventDefault();
	        $("#loader").show();
	        var data = $( "#form-recycle" ).serialize();
	        jQuery.ajax({
	        	dataType: "json",
	        	type : "POST",
	        	data : data,
	        	url : pluginURL() + "templates/submit/pageSubmit.template.php",
	        	success: success,
	        	error: printError
	        });

	    });

	    // Success Message
	    var success = function( resp ){
	    	alert("Form is Submitted Successfully");
	    };
	    // Error Message > This is the function being called whenever the form submission is succesful
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
    /** End Front End Form Submission **/

    /** Start Admin End Form Submission **/    	
    	$('#adminSubmitButton').prop('disabled', true);
    	// Hide Loader
		$("#adminLoader").hide();
		$("#transaction_status").change(function(){
	        var selectedCountry = $(this).children("option:selected").val();
	        
	        if(selectedCountry = 'wc-recycled'){
	        	// Submit Front End Form
	        	$('#adminSubmitButton').prop('disabled', false);
			    $('#form-admin-recycle').on('submit', function(event) { 
			        event.preventDefault();
			        $("#adminLoader").show();
			        var data = $( "#form-admin-recycle" ).serialize();
			        jQuery.ajax({
			        	dataType: "json",
			        	type : "POST",
			        	data : data,
			        	url : pluginURL() + "templates/submit/adminSubmit.template.php",
			        	success: successAdmin,
			        	error: printErrorAdmin
			        });

			    });
			    // Success Message
			    var successAdmin = function( resp ){
			    	alert("Form is Submitted Successfully");
			    };
			    // Error Message > This is the function being called whenever the form submission is succesful
			    var printErrorAdmin = function( req, status, err ) {
			    	$("#adminLoader").hide();
			    	$.confirm({
					    title: 'Item Recycled Successfully!',
					    content: 'You have changed the current status of this recycled item.\nThank You!',
					    buttons: {
					        Ok: function () {
					            location.reload();
					        }
					    }
					});
				};
	        }

	    });
    /** Start Admin End Form Submission **/

    /** Start Admin Searching **/
		var searchValue = $('#recycle-search-input').val();	
		if( !searchValue ){
			var txt = $(this).val();
			$('result').html('');
			$.ajax({
				url : pluginURL() + "templates/submit/search.template.php",
				method: "POST",
				data: {data: txt},
				dataType: "html",
				success: function(data){
					$('#the-recycle-list').html(data);
				}
			});

		}

		$('#recycle-search-input').keyup(function(){
			$('#the-list2').hide();
			var txt = $(this).val();
			alert(txt);
			// $('result').html('');
			$.ajax({
				url : pluginURL() + "templates/submit/search.template.php",
				method: "POST",
				data: {data: txt},
				dataType: "html",
				success: function(data){
					$('#the-recycle-list').html(data);
				}
			});		
		});

	/** End Admin Searching **/

	/** Start Date Sorting **/
		// By Date
		$('.dateSorting').click(function(event) { 
		    event.preventDefault();
		    var txt = $(this).data("id");
		    $.ajax({
		        url : pluginURL() + "templates/submit/sorting.template.php",
				method: "POST",
				data: {dateSorting: txt},
				dataType: "html",
				success: function(data){
					$('#the-recycle-list').html(data);
				}
		    });
		    return false; // for good measure
		});

		// By Status		
		$('.statusSorting').click(function(event) { 
		    event.preventDefault();
		    var txt = $(this).data("id");
		    $.ajax({
		        url : pluginURL() + "templates/submit/sorting.template.php",
				method: "POST",
				data: {statusSorting: txt},
				dataType: "html",
				success: function(data){
					$('#the-recycle-list').html(data);
				}
		    });
		    return false; // for good measure
		});
	/** End Sorting **/
});