<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\BaseController;
use Includes\Base\CustomerOrder;
use Includes\StampsAPI\StampService;
use Includes\StampsAPI\Address;
	// // Declare variable that contains current user details from wp_user table
	if(wp_get_current_user()){
	$customer = wp_get_current_user();

	// Order count for a "loyal" customer
    $loyal_count = 5;

    // Get Customer Orders and its Details if post status is completed
    $customerOrderDetails = get_posts( array(
	        'numberposts' => -1,
	        'meta_key'    => '_customer_user',
	        'meta_value'  => get_current_user_id(),
	        'post_type'   => wc_get_order_types(),
	        'post_status' => 'wc-completed',
	    ) );
    if($customerOrderDetails){

    // Text for our "thanks for loyalty" message
    $notice_text = sprintf( 'Hey %1$s &#x1f600; We noticed you\'ve placed more than %2$s orders with us – thanks for being a loyal customer!', $customer->display_name, $loyal_count );

	// Declare CustomerORder Object
	$CustomerOrderObj = new CustomerOrder();
	// Set Customer Order Details
	$CustomerOrderObj->setCustomerOrderDetails($customerOrderDetails);
	// To Return all the detials of the customer
	$orderDetails = $CustomerOrderObj->returnOrderDetails();

	// Generate Random Hash Code
	$random_hash = substr(md5(uniqid(rand(), true)), 16, 16);
	
?>
<!-- <form method="POST" action="<?php //echo $this->plugin_url . 'templates/submit/pageSubmit.template.php'; ?>"> -->
<form id="form-recycle" method="POST">
	<input type="text" name="current_user_id" value="<?php echo get_current_user_id(); ?>" hidden>
	<div class="container">
		<div class="row">
			<div class="row">
				<div class="col-md-12">
					<?php 
						// Display our notice if the customer has at least 5 orders
					    if ( count( $customerOrderDetails ) >= $loyal_count ) {
					        wc_print_notice( $notice_text, 'notice' );
					    } 
    				?>
				</div>
			</div>
			<div class="col-md-8">
				
				<div class="product-list default-container-border">
					<header>
						<h1>RECYCLE</h1>
						<button type="button" class="btn btn btn-success btn-md" id="buttonModal" data-toggle="modal" data-target="#selectProductModal">Add Product</button>
					</header>

					<hr>

					<div class="content">
						<!-- Append Products Here -->
					</div>

				</div>

			</div>

			<div class="col-md-4">
				
				<div class="user-details default-container-border">

					<header>
						<h1>FROM</h1>
					</header>
					
					<hr>

					<div class="content">
						
							<div class="form-group">
						    	<label for="firstName">First Name:</label>
						    	<input type="text" class="form-control" name="from_firstname" value="<?php echo $orderDetails->get_shipping_first_name(); ?>" placeholder="<?php echo $orderDetails->get_shipping_first_name(); ?>" >
						  	</div>

						  	<div class="form-group">
						    	<label for="lastName">Last Name:</label>
						    	<input type="text" class="form-control" name="from_lastName" value="<?php echo $orderDetails->get_shipping_last_name(); ?>" placeholder="<?php echo $orderDetails->get_shipping_last_name(); ?>" >
						  	</div>

						  	<div class="form-group">
						    	<label for="email">Email Address:</label>
						    	<input type="email" class="form-control" name="from_email" value="<?php echo $orderDetails->get_billing_email(); ?>" placeholder="<?php echo $orderDetails->get_billing_email(); ?>">
						  	</div>
							
							<div class="form-group">
						    	<label for="email">Address:</label>
						    	<input type="text" class="form-control" name="from_address" value="<?php echo $orderDetails->get_shipping_address_1(); ?>" placeholder="<?php echo $orderDetails->get_shipping_address_1(); ?>" >
						  	</div>


						  	<div class="form-group">
						    	<label for="note">Phone :</label>
						    	<input type="number" class="form-control" name="from_phone_number" value="<?php echo $orderDetails->get_billing_phone(); ?>" placeholder="<?php echo $orderDetails->get_billing_phone(); ?>" >
						  	</div>

						  	<div class="form-group">
						    	<label for="country">Country:</label>
						    	<input type="text" class="form-control" name="from_country" value="<?php echo $orderDetails->get_shipping_country(); ?>" placeholder="<?php echo $orderDetails->get_shipping_country(); ?>" >
						  	</div>

						  	<div class="form-group">
						    	<label for="postcode">Postcode:</label>
						    	<input type="text" class="form-control" name="from_postcode" value="<?php echo $orderDetails->get_shipping_postcode(); ?>" placeholder="<?php echo $orderDetails->get_shipping_postcode(); ?>" >
						  	</div>

						  	<div class="form-group">
						    	<label for="city">City:</label>
						    	<input type="text" class="form-control" name="from_city" value="<?php echo $orderDetails->get_shipping_city(); ?>" placeholder="<?php echo $orderDetails->get_shipping_city(); ?>">
						  	</div>

						  	<div class="form-group">
						    	<label for="state">State:</label>
						    	<input type="text" class="form-control" name="from_state" value="<?php echo $orderDetails->get_shipping_state(); ?>" placeholder="<?php echo $orderDetails->get_shipping_state(); ?>">
						  	</div>
						
					</div>
				</div>

			</div>
		</div>


		<div class="row" style="margin-top:.5em;">
			<div class="col-md-12">
				<div class="mail-return default-container-border">
					<h3>Shipping Details : </h3>
					<input type="hidden" class="form-control" id="totalItemWeight" name="totalItemWeight" value="">
					<hr>
					<div class="row">
						<div class="col-md-3">
							
							<h4 style="color:#fff;font-weight: bold;">Package Details</h4>
							<div class="col-md-12">
								<div class="form-group">
									<label for="serviceType">Service Type:</label>
									<p class="serviceType"></p>
								</div>

								<div class="form-group">
									<label for="totalItemWeight">Total Item Weight in oz:</label>
									<p class="totalItemWeight"></p>
								</div>

								<div class="form-group">
									<label for="returnedRate">Amount:</label>
									<p class="returnedRate"></p>
								</div>

								<div class="form-group">
									<label for="ShipDate">Ship Date:</label>
									<p class="ShipDate"></p>
								</div>

								<div class="form-group">
									<label for="DeliverDays">Deliver Days:</label>
									<p class="DeliverDays"></p>
								</div>
							</div>

						</div>
						<div class="col-md-5">

							<h4 style="color:#fff;font-weight: bold;">Ship To : </h4>

							<div class="col-md-6">
								<div class="form-group">
									<label for="ShippingFullName">Full Name :</label>
									<p>Eric Mosser</p>
								</div>

								<div class="form-group">
									<label for="ShippingEmail">Email :</label>
									<p>admin@kaivalbrands.com</p>
								</div>

								<div class="form-group">
									<label for="ShippingPhone">Phone :</label>
									<p>5035671167</p>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="ShippingStreetAddress">Address :</label>
									<p>16300 SW Blackbird Street</p>
									<p>Beaverton</p>
									<p>Oregon</p>
									<p>97007</p>
								</div>
							</div>

						</div>
						<div class="col-md-4">
							<h4 style="color:#fff;font-weight: bold;">Card Details : </h4>
							<div class="col-md-12">
								<div class="form-group">
									<label for="creditCardNumber">Card Number:</label>
									<input type="number" class="form-control" id="creditCardNumber" name="creditCardNumber">
								</div>
								<div class="form-group">
									<label for="ExpirationDate">Expiry Date:</label>
									<input type="month" class="form-control" id="card_exp_month" name="card_exp_month">
								</div>
								<div class="form-group">
									<label for="returnedRate">CVC CODE:</label>
									<input type="text" class="form-control" id="card_cvc" name="card_cvc" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="returnedRate">Amount:</label>
									<p class="returnedRate"></p>
								</div>

								<div class="form-group">
									<button type="submit" name="submit" class="form-control btn btn-success" id="recycle-submit" disabled>Confirm Recycle</button>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>	
		</div>

	</div>
</form>

<div id="loader"></div>

<!-- Modal -->
<div id="selectProductModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Product</h4>
      </div>

      <div class="modal-body">
			<?php 
				
				// To Get All Order Items
				$orderItems = $CustomerOrderObj->getOrderItems();
				$x = 1;
				
				foreach ($orderItems as $key => $value) {
					$order_id = $value['order_id'];
					$order_item_id = $value['id'];
					$product_name = $value['name'];
					$product_id = $value['product_id'];
					$product_qty = $value['quantity'];

					if($product_qty != 0){
					
			?>
      		<!-- Product -->

        	<div class="row modal-product modal-product-<?php echo $x ?>">

				<div class="col-md-8">
					<div class="product-info flex">
						<?php 
							if($product_id == 0){
								?>
							     <img src="<?php echo $this->plugin_url . 'assets/img/woocommerce-placeholder-500x500.png'; ?>" alt="<?php echo $product_name; ?>" id="modal_product_image_<?php echo $x; ?>">
								<?php
							}else{
								?>
								<?php 
									$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
								?>
								<img src="<?php echo $image['0']; ?>" alt="<?php echo $product_name; ?>" id="modal_product_image_<?php echo $x; ?>">
								<?php
							}
						 ?>
						
						<div class="product-details">
							<h4 id="modal_product_name_<?php echo $x; ?>"><?php echo $product_name; ?></h4>
						</div>
					</div>

				</div>

				<div class="col-md-4">
					<div class="product-qty">
						<input type="hidden" id="modal_order_id_<?php echo $x; ?>" name="order_id" value="<?php echo $order_id; ?>">
						<input type="hidden" id="modal_order_item_id_<?php echo $x; ?>" name="order_item_id" value="<?php echo $order_item_id; ?>">
						<input type="number" class="form-control" id="modal_productQty_<?php echo $x; ?>" name="productQty" placeholder="<?php echo $product_qty; ?>" value="<?php echo $product_qty; ?>">
						<button type="button" class="modalButton btn btn-success btn-circle" id="modal_buttonAdd_<?php echo $x; ?>" value="<?php echo $x; ?>" onclick="addElement(this)"><i class="fa fa-plus"></i></button>
					</div>
				</div>

			</div>

		<?php $x++; } } ?>
			
      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>

    </div>

  </div>
</div>

<?php 
	}else{
		echo "User doesnt have orders that are completed";
	}
}