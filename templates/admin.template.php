<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\DBModel;
use Includes\Base\CustomerOrder;

$DBModel = new DBModel();
$CustomerOrder = new CustomerOrder();

if(isset($_GET['return_id'])){
	$return_id = $_GET['return_id'];
	$getReturnProductData = $DBModel->getReturnProductData($return_id);
	$getUserBillingShipping = $CustomerOrder->getUserBillingShipping($getReturnProductData[0]->customer_id);

	
?>
	<!-- Start View Single Recycle Data -->
	<div class="wrap return-product-data">
		<h1 class="wp-heading-inline">Bidi Recycle Program</h1>
		<div class="container-fluid" style="padding:1em;">
			<div class="row">
				<div class="col-md-10">
					<div class="row">
						<div class="row">
							<div class="customerDetails">
								<h2 class="woocommerce-order-data__heading">Recycle Tracking Number : <?php echo $getReturnProductData[0]->shipping_tracking_number; ?></h2>
								<div class="details">
									<div class="general">
										<h4>General</h4>
										<label>Customer Name : </label>
										<p><?php echo $getReturnProductData[0]->display_name . "</br>"; ?></p>
										</br>
										<label>Date of Return : </label>
										<p><?php echo $getReturnProductData[0]->return_date . "</br>"; ?></p>
									</div>
									<div class="billing">
										<h4>Billing</h4>
										<p>
											<?php 
												echo $getUserBillingShipping['billing_first_name'] . " " . $getUserBillingShipping['billing_last_name'];
												echo "</br>";
												echo $getUserBillingShipping['billing_address_1'];
												echo "</br>";
												echo $getUserBillingShipping['billing_city'] . " " . $getUserBillingShipping['billing_state'] . " " . $getUserBillingShipping['billing_postcode'];
											?>
										</p>
										<label>Phone : </label>
										<p>
											<?php 
												echo $getUserBillingShipping['billing_phone'];
											 ?>
										</p>
										<label>Email : </label>
										<p>
											<?php 
												echo $getUserBillingShipping['billing_email'];
											 ?>
										</p>
									</div>
									<div class="shipping">
										<h4>Shipping</h4>
										<p>
											<?php 
												echo $getUserBillingShipping['shipping_first_name'] . " " . $getUserBillingShipping['shipping_last_name'];
												echo "</br>";
												echo $getUserBillingShipping['shipping_address_1'];
												echo "</br>";
												echo $getUserBillingShipping['shipping_city'] . " " .$getUserBillingShipping['shipping_state'] . " " . $getUserBillingShipping['shipping_postcode'];
											 ?>
										</p>
										
									</div>
									<div class="form-group">
										<label>Status:</label>
										<!-- <form action="<?php //echo $this->plugin_url . 'templates/submit/adminSubmit.template.php'; ?>" method="POST"> -->
										<form id="form-admin-recycle" method="POST">
											<input type="hidden" name="customer_id" value="<?php echo $getReturnProductData[0]->customer_id; ?>">
											<input type="hidden" name="shipping_tracking_number" value="<?php echo $getReturnProductData[0]->shipping_tracking_number; ?>">
											<input type="hidden" name ="return_id" value="<?php echo $return_id; ?>">
											<input type="hidden" name="transaction_date" value="<?php echo date("Y-m-d h:i:sa"); ?>">
											<input type="hidden" name="customerEmail" value="<?php echo $getUserBillingShipping['billing_email']; ?>">
											<?php
												$count = count($getReturnProductData);
												$counter = 0;
												foreach ($getReturnProductData as $value) {
													$counter++; 
											?>
												<input type="hidden" name="order_ids[]" value="<?php echo $value->product_order_id; ?>">
												<input type="hidden" name="product_item_id[]" value="<?php echo $value->product_item_id; ?>">
											<?php } ?>
											
											<?php if($getReturnProductData[0]->return_item_status == 'wc-completed'){ ?>
												<select class="form-control" name="transaction_status" id="transaction_status">
													<option value="" disabled selected hidden>
														PENDING
													</option>
													<option value="wc-recycled">RECYCLE</option>
												</select>
											<?php }else if($getReturnProductData[0]->return_item_status == 'wc-recycled'){ ?>
												<input type="text" placeholder="RECYCLED" readonly>
											<?php } ?>


											</br>


											<?php if($getReturnProductData[0]->return_item_status == 'wc-completed'){ ?>
												<button type="submit" class="btn btn-primary" id="adminSubmitButton">Save Transaction</button>
											<?php }else if($getReturnProductData[0]->return_item_status == 'wc-recycled'){ ?>
												<button type="submit" class="btn btn-primary" disabled>Save Transaction</button>
											<?php } ?>
										</form>
										<div id="adminLoader"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="productReturnDetails">

								<table class="table widefat">
								  <thead class="thead-light">
								    <tr>
								      <th scope="col">#</th>
								      <th scope="col">Image</th>
								      <th scope="col">Name</th>
								      <th scope="col">ID</th>
								      <th scope="col">Order ID</th>
								      <th scope="col">Item Id</th>
								      <th scope="col">Return Date</th>
								    </tr>
								  </thead>
								  <tfoot>
									  <th scope="col">#</th>
								      <th scope="col">Image</th>
								      <th scope="col">Name</th>
								      <th scope="col">ID</th>
								      <th scope="col">Order ID</th>
								      <th scope="col">Item Id</th>
								      <th scope="col">Return Date</th>
									</tfoot>
								  <tbody>
								  	<?php 
										$count = count($getReturnProductData);
										$counter = 0;
										foreach ($getReturnProductData as $value) {
											$counter++;
									 ?>
								    <tr>
								      <th scope="row"><?php echo $counter; ?></th>
								      <td>
								      	<?php 
										$image = wp_get_attachment_image_src( get_post_thumbnail_id( $value->product_image ), 'single-post-thumbnail' );
									?>
								      	<img width="100" height="100" src="<?php echo $image['0']; ?>" class="attachment-thumbnail size-thumbnail" alt="<?php echo $value->product_name; ?>" title="<?php echo $value->product_name; ?>">
								      	</td>
								      <td><?php echo $value->product_name; ?></td>
								      <td><?php echo $value->product_info_id; ?></td>
								      <td><?php echo $value->product_order_id; ?></td>
								      <td><?php echo $value->product_item_id; ?></td>
								      <td><?php echo date('F, j Y h:i:sa',strtotime($value->product_return_date)); ?></td>
								    </tr>
								    <?php } ?>
								  </tbody>
								</table>



							</div>
						</div>
					</div>
				</div>
				<div class="col-md-2">
				</div>
			</div>
		</div>
	</div>
	<!-- End View Single Recycle Data -->

<!-- Start View Recycle Data List -->
<?php

}else if(empty($getReturnProductData)){
?>
	
	<div class="wrap">
		<h1 class="wp-heading-inline">Bidi Recycle Program</h1>
		<form id="posts-filter" method="get">

			<p class="search-box">
				<label class="screen-reader-text" for="post-search-input">Search Returns:</label>
				<input type="search" id="recycle-search-input" name="searchRecyle" placeholder="Type Email To Search">
			</p>

			<table class="wp-list-table widefat fixed striped posts">
				<thead>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>

						<th scope="col" class="manage-column column-order_number column-primary">							
							<span style="display: block;overflow: hidden;padding: 8px;">Returns</span>							
						</th>
						<th scope="col" class="manage-column column-order_number column-primary">
							<span style="display: block;overflow: hidden;padding: 8px;">Email</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<span style="display: block;overflow: hidden;padding: 8px;">Return Date
								<span class="sortingIcon">
									<i class="glyphicon glyphicon-triangle-top dateSorting" data-id="ASC"></i>
									<i class="glyphicon glyphicon-triangle-bottom dateSorting" data-id="DESC"></i>
								</span>
							</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<span style="display: block;overflow: hidden;padding: 8px;">Status
								<span class="sortingIcon">
									<i class="glyphicon glyphicon-triangle-top statusSorting" data-id="wc-recycled"></i>
									<i class="glyphicon glyphicon-triangle-bottom statusSorting" data-id="wc-completed"></i>
								</span>
							</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<center><span style="display: block;overflow: hidden;padding: 8px;">Actions</span></center>
						</th>
					</tr>
				</thead>

				<tbody id="the-recycle-list">
					<!-- show all the list of returns -->
				</tbody>

				<tfoot>
					<tr>
						<td id="cb" class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>

						<th scope="col" class="manage-column column-order_number column-primary">
							<span style="display: block;overflow: hidden;padding: 8px;">Returns</span>
						</th>
						<th scope="col" class="manage-column column-order_number column-primary">
							<span style="display: block;overflow: hidden;padding: 8px;">Email</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<span style="display: block;overflow: hidden;padding: 8px;">Return Date
								<span class="sortingIcon">
									<i class="glyphicon glyphicon-triangle-top dateSorting" data-id="ASC"></i>
									<i class="glyphicon glyphicon-triangle-bottom dateSorting" data-id="DESC"></i>
								</span>
							</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<span style="display: block;overflow: hidden;padding: 8px;">Status
								<span class="sortingIcon">
									<i class="glyphicon glyphicon-triangle-top statusSorting" data-id="wc-recycled"></i>
									<i class="glyphicon glyphicon-triangle-bottom statusSorting" data-id="wc-completed"></i>
								</span>
							</span>
						</th>
						<th scope="col" class="manage-column column-order_date">
							<center><span style="display: block;overflow: hidden;padding: 8px;">Actions</span></center>
						</th>
					</tr>
				</tfoot>

			</table>
			
		</form>
	</div>
	<!-- End View Recycle Data List -->
	<!-- Show no Data -->
<?php 
}else{
	echo '<h1 class="wp-heading-inline">Bidi Recycle Program</h1>';
	echo "<h1>No Data!</h1>";
}