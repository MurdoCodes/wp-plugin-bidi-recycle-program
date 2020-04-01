<div class="wrap">
	<h1 class="wp-heading-inline">Bidi Recycle Program</h1>
</div>

<div class="wrap">


	<h2 class="screen-reader-text">Filter coupons</h2><ul class="subsubsub">
		<li class="all"><a href="edit.php?post_type=shop_coupon&amp;all_posts=1" class="current" aria-current="page">All <span class="count">(12)</span></a> |</li>
		<li class="mine"><a href="edit.php?post_type=shop_coupon&amp;author=964">Mine <span class="count">(1)</span></a> |</li>
		<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=shop_coupon">Published <span class="count">(12)</span></a></li>
	</ul>

	<form id="posts-filter" method="get">

		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Search Returns:</label>
			<input type="search" id="post-search-input" name="s" value="">
			<input type="submit" id="search-submit" class="button" value="Search coupons">
		</p>
		
		<input type="hidden" name="post_status" class="post_status_page" value="all">
		<input type="hidden" name="post_type" class="post_type_page" value="shop_coupon">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="a46a3afdbc"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit.php?post_type=shop_coupon">	<div class="tablenav top">

		<div class="alignleft actions bulkactions">
			<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
			<select name="action" id="bulk-action-selector-top">
				<option value="-1">Bulk Actions</option>
				<option value="edit" class="hide-if-no-js">Edit</option>
				<option value="trash">Move to Trash</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
					
		<div class="alignleft actions">
			<select name="coupon_type" id="dropdown_shop_coupon_type">
				<option value="">Show all types</option>
				<option value="percent">Percentage discount</option>
				<option value="fixed_cart">Fixed cart discount</option>
				<option value="fixed_product">Fixed product discount</option>
			</select>
			<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">		
		</div>

		<div class="tablenav-pages one-page">
			<span class="displaying-num">12 items</span>
			<span class="pagination-links"><span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
			<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
			<span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current Page</label><input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span class="total-pages">1</span></span></span>
			<span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
			<span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span></div>
			<br class="clear">
		</div>
		
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
							<span>Transaction Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Status</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
				</tr>
			</thead>

			<tbody id="the-list">
				<tr id="post-18233" class="iedit author-other level-0 post-18233 type-shop_order status-wc-completed post-password-required hentry">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-18233">Select Order – March 12, 2020 @ 07:15 AM</label>
						<input id="cb-select-18233" type="checkbox" name="post[]" value="18233">
						<div class="locked-indicator">
							<span class="locked-indicator-icon" aria-hidden="true"></span>
							<span class="screen-reader-text">
							“Order – March 12, 2020 @ 07:15 AM” is locked				</span>
						</div>
					</th>
					<td class="order_number column-order_number has-row-actions column-primary" data-colname="Order">
						<a href="#" class="order-preview" data-order-id="18233" title="Preview">Preview</a>
						<a href="http://bidivapor.quikfillrx.org/wp-admin/post.php?post=18233&amp;action=edit" class="order-view">
							<strong>#00001 Kim Daddie</strong>
						</a>
					</td>
					<td class="order_date column-order_date" data-colname="Date">
						<time datetime="2020-03-12T07:15:03+00:00" title="March 12, 2020 7:15 am">Mar 12, 2020</time>
					</td>
					<td class="order_status column-order_status" data-colname="Status">
						<mark class="order-status status-completed tips">
							<span>Completed</span>
						</mark>
					</td>	
				</tr>
				
			</tbody>

			<tfoot>
				<tr>
					<td class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
						<input id="cb-select-all-2" type="checkbox">
					</td>
					<th scope="col" class="manage-column column-order_number column-primary sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=ID&amp;order=asc">
							<span>Order</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-order_date sortable desc">
						<a href="http://bidivapor.quikfillrx.org/wp-admin/edit.php?post_type=shop_order&amp;orderby=date&amp;order=asc">
							<span>Date</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-order_status">Status</th>	
				</tr>
			</tfoot>

		</table>
		
	</form>
		
	<div id="ajax-response"></div>
	<br class="clear">
</div>

