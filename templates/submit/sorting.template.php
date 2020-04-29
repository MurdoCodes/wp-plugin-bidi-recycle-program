<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\DBModel;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );
	


if(isset($_POST['dateSorting'])){
	$DBModel = new DBModel();
	$param = $_POST['dateSorting'];
	$limit = 20;
	$sorting = $DBModel->recycleSortingByDate($param,$limit);
	showDetails($sorting);

}else if(isset($_POST['statusSorting'])){
	$DBModel = new DBModel();
	$param = $_POST['statusSorting'];
	$limit = 20;
	$sorting = $DBModel->recycleSortingStatus($param,$limit);
	showDetails($sorting);	
}

function showDetails($param){
	$output = '';
	foreach ($param as $value) {
		$query_args = array( 'page' => 'bidi_recycle_program', 'return_id' => $value->return_id );
		$returnDetailsURL = add_query_arg( $query_args, admin_url('admin.php?') );

		if($value->return_item_status == 'wc-recycled'){
			$itemStatus = '<mark class="alert alert-success">
								<span>
									RECYCLED
								</span>
							</mark>';
		}else if($value->return_item_status == 'wc-completed'){
			$itemStatus = '<mark class="alert alert-warning">
								<span>
									PENDING
								</span>
							</mark>';
		}

		$output .= '
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
							<mark class="alert alert-success">
								<span>
									' . $value->user_email . '
								</span>
							</mark>
						</td>
						<td class="order_date column-order_date" data-colname="Date">
							<time datetime="' . date('F, j Y h:i:sa',strtotime($value->return_date)). '" title="' . date('F, j Y h:i:sa',strtotime($value->return_date)) . '">
								' . date('F, j Y h:i:sa',strtotime($value->return_date)) . '
							</time>
						</td>';
						
		$output .= '
						<td class="order_status column-order_status" data-colname="Status">'
						. $itemStatus .
						'</td>';
		$output .= '
						<td class="order_status column-order_status" data-colname="Status">
							<center><a href="' . $returnDetailsURL . '" style="text-align:center;"><i class="fas fa-eye"></i></a></center>
						</td>
					</tr>';
		
	}
	echo $output;
}