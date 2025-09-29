<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Dispatch Report";?></h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'create-pick-list'?>" title="Create Pick List"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<form action="<?php echo BASE_URL.$this->page_name?>" method="POST" data-parsley-validate="">
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<!--div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<form action="" method="POST" data-parsley-validate="">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Customer Name: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="customer_id" required id="customer_id">
												<option value="">Select customer</option>
												<?php echo $this->customer_option; ?>
											</select>
											<input type="hidden" name="act" id="act" value="get_order">
										</div>	
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Sales Order No.: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="order_id" required id="order_id">
												<option value="">Select Order</option>
											</select>
										</div>	
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">From Date:</label>
											<input class="form-control" name="from_date" id="from_date" value="" type="date">
										</div>	
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">To Date:</label>
											<input class="form-control" name="to_date" id="to_date" value="" type="date">
										</div>	
									</div>								
									<div class="col-md-12 text-center">
										<button class="btn ripple btn-info" type="submit">Search</button>
									</div>
								</div>
							</form>
						</div-->
						<div class="card-body">							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
                                            <th>Sales Order No.</th>
											<th>Customer Name</th>
                                            <th>Product Name</th>
											<th>Box No.</th>
											<th>Quantity</th>
                                            <th>Delivery Date</th>
											<th>No. of Sticker</th>
											<th>Box Slip</th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($dispatch_list)):
											foreach($dispatch_list as $obj): ?>
											<tr>
												<td><?php echo $obj->sales_order_no ?></td>
												<td><?php echo $obj->company_name ?></td>
												<td><?php echo $obj->product_name ?></td>
												<td><?php echo $obj->box_no; ?></td>
												<td><?php echo $obj->no_of_items; ?></td>
												<td><?php echo $obj->delivery_date?></td>
												<td>
													<input type="text" value="<?php echo $obj->no_of_stickers ?? ''; ?>" name="no_of_stickers" id="no_of_stickers_<?php echo $obj->pick_list_id?>" style="width:75px !important;" onchange="update_no_of_stickers('<?php echo $obj->pick_list_id?>')" >
												</td>
												<td>
													<a class="btn btn-sm btn-info" href="<?php echo BASE_URL.'dispatch-box-slip/'.$obj->pick_list_id; ?>" title="Print Barcode" target="_blank"><i class="fe fe-printer"></i>
												</td>
											</tr>
										<?php endforeach;
										else: echo "<tr><td colspan='8'>No record found!</td></tr>";
										endif; ?>										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->			
			</form>
		</div>
	</div>
</div>
<!-- End Main Content-->

<Style>
	.table>thead>tr>th{ font-size:13px; font-weight:500; text-transform:unset; }
	.no-border{ padding:0; border: unset; }
	.richText .richText-editor{ height:80px;}
	.hid-tbl,.h-id, .pick-list-tbl,.hid-btn{display:none;}	
</style>