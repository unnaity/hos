<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Detail Issues List"; ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . 'general-issues-list'; ?>">
						<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>
			</div>
			<!-- End Page Header -->
			<!-- Row -->
			<form action="<?php echo BASE_URL . $this->page_name ?>" method="POST" data-parsley-validate="">
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<!--div class="card-body">							
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
								<?php $this->load->view('Backend/Elements/Alert.php'); ?>
								<div class="table-responsive">
									<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>
												<th>Sl No.</th>
												<th>Item Name</th>
                                                <th>Total Quantity</th>
												<th>Scannned Qty</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($fg_detail)):
												$i = 1;
												foreach ($fg_detail as $obj): ?>
													<tr>
														<td><?php echo $i++; ?></td>
														<td><?php echo $obj->item_name; ?></td>
														<td><?php echo $obj->fg_quantity * $obj->quantity ?></td>
														<td><?php echo $obj->scanned_qty; ?></td>
														
													</tr>
											<?php endforeach;
											else: echo "<tr><td colspan='8' align='center'>No record found!</td></tr>";
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
	.table>thead>tr>th {
		font-size: 13px;
		font-weight: 500;
		text-transform: unset;
	}

	.no-border {
		padding: 0;
		border: unset;
	}

	.richText .richText-editor {
		height: 80px;
	}

	.hid-tbl,
	.h-id,
	.pick-list-tbl,
	.hid-btn {
		display: none;
	}
</style>