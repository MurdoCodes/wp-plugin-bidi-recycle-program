<?php 
namespace Includes\Base;
$DBModel = new DBModel();
$getAllReturnAndUserData = $DBModel->getAllReturnAndUserData();

if(isset($_GET['return_id'])){
	$return_id = $_GET['return_id'];
	$getReturnProductData = $DBModel->getReturnProductData($return_id);
?>

<div class="wrap return-product-data">
	<h1 class="wp-heading-inline">Bidi Recycle Program</h1>
	<div class="container-fluid" style="padding:1em;">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="row">
						<div class="customerDetails">
							<h2 class="woocommerce-order-data__heading">Return Code : <?php echo $getReturnProductData[0]->return_code; ?></h2>
							<div class="details">
								<div class="general">
									<h5>General</h5>
									<?php 
										echo "Return Date : " . $getReturnProductData[0]->return_date . "</br>";
										echo "Return Status : " . $getReturnProductData[0]->return_item_status . "</br>";
										echo "Name of Returnee : " . $getReturnProductData[0]->display_name . "</br>";
									 ?>
								</div>
								<div class="billing">
									<h1>NO DETAILS YET</h1>
								</div>
								<div class="shipping">
									<h1>NO DETAILS YET</h1>
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
							      	<img width="100" height="100" src="<?php echo $value->product_image; ?>" class="attachment-thumbnail size-thumbnail" alt="<?php echo $value->product_name; ?>" title="<?php echo $value->product_name; ?>">
							      	</td>
							      <td><?php echo $value->product_name; ?></td>
							      <td><?php echo $value->product_info_id; ?></td>
							      <td><?php echo $value->product_order_id; ?></td>
							      <td><?php echo $value->product_item_id; ?></td>
							      <td><?php echo $value->product_return_date; ?></td>
							    </tr>
							    <?php } ?>
							  </tbody>
							</table>



						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
</div>

<?php
	}else if(empty($getReturnProductData)){
?>

<div class="wrap">
	<h1 class="wp-heading-inline">Bidi Recycle Program</h1>
	<form id="posts-filter" method="get">

		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Search Returns:</label>
			<input type="search" id="post-search-input" name="s" value="">
			<input type="submit" id="search-submit" class="button" value="Search Returns">
		</p>

		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</td>

					<th scope="col" id="order_number" class="manage-column column-order_number column-primary sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=ID&amp;order=asc">
							<span>Returns</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Return Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Actions</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
				</tr>
			</thead>

			<tbody id="the-list">
				<?php foreach ($getAllReturnAndUserData as $value): ?>
					
				<tr>
					<th scope="row" class="check-column">
						
					</th>
					<td class="order_number column-order_number has-row-actions column-primary" data-colname="Order">
						<!-- <a href="#" data-id="<?php echo $value->return_id; ?>" data-toggle="modal" data-target="#ReturnInformationModal" class="ReturnInformationModal"> -->
							<?php 
								$query_args = array( 'page' => 'bidi_recycle_program', 'return_id' => $value->return_id );
								$returnDetailsURL = add_query_arg( $query_args, admin_url('admin.php?') );
							 ?>
						<a href="<?php echo $returnDetailsURL; ?>">
							<strong>
								#<?php echo $value->return_code . " " . $value->display_name; ?>
							</strong>
						</a>
					</td>
					<td class="order_date column-order_date" data-colname="Date">
						<time datetime="<?php echo date('F, j Y h:i:sa',strtotime($value->return_date)); ?>" title="<?php echo date('F, j Y h:i:sa',strtotime($value->return_date)); ?>">
							<?php echo date('F, j Y h:i:sa',strtotime($value->return_date)); ?>
						</time>
					</td>
					<td class="order_status column-order_status" data-colname="Status">
						<mark class="alert alert-warning">
							<span>
								<?php echo $value->return_item_status; ?>
							</span>
						</mark>
					</td>

					<td class="order_status column-order_status" data-colname="Status">
						<a href="<?php echo $returnDetailsURL; ?>">View</a> / <a href="">Delete</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>

			<tfoot>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</td>

					<th scope="col" id="order_number" class="manage-column column-order_number column-primary sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=ID&amp;order=asc">
							<span>Returns</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Return Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Actions</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
				</tr>
			</tfoot>

		</table>
		
	</form>
</div>

<?php 
	}else{
		echo '<h1 class="wp-heading-inline">Bidi Recycle Program</h1>';
		echo "<h1>No Data!</h1>";
	}