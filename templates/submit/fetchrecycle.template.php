<?php 
namespace Includes\Base;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );
	
	$DBModel = new DBModel();
	
	$param = $_POST['data'];
	$recycleSearch = $DBModel->recycleSearch($param);
	
	$output = '';
	foreach ($recycleSearch as $value) {
		$query_args = array( 'page' => 'bidi_recycle_program', 'return_id' => $value->return_id );
		$returnDetailsURL = add_query_arg( $query_args, admin_url('admin.php?') );

		$output = '
					<tr>
						<th scope="row" class="check-column"></th>
							<td class="order_number column-order_number has-row-actions column-primary" data-colname="Order">
							<a href="' . $returnDetailsURL . '"
									<strong>
										#' . $value->return_code . ' ' . $value->display_name . '
									</strong>
								</a>
							</td>
						<td class="order_status column-order_status" data-colname="Status">
							<mark class="alert alert-warning">
								<span>
									' . $value->user_email . '
								</span>
							</mark>
						</td>
						<td class="order_date column-order_date" data-colname="Date">
							<time datetime="' . date('F, j Y h:i:sa',strtotime($value->return_date)). '" title="' . date('F, j Y h:i:sa',strtotime($value->return_date)) . '">
								' . date('F, j Y h:i:sa',strtotime($value->return_date)) . '
							</time>
						</td>
						<td class="order_status column-order_status" data-colname="Status">
							<mark class="alert alert-warning">
								<span>
									' . $value->return_item_status . '
								</span>
							</mark>
						</td>

						<td class="order_status column-order_status" data-colname="Status">
							<a href="' . $returnDetailsURL . '">View</a> / <a href="">Delete</a>
						</td>
					</tr>';
		echo $output;
	}

