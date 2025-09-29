<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header"">
				<h4 class=" text-start mb-0"><?php echo "Create" . ' ' . UCWORDS(str_replace('_', ' ', $this->class_name)); ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . strtolower(str_replace('_', '-', $this->class_name)) . '-list' ?>">
						<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>
			</div>
			<!-- End Page Header -->
			<!-- Row -->
			<form action="<?php echo BASE_URL . $this->page_name ?>" method="POST" data-parsley-validate="">
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<?php $this->load->view('Backend/Elements/Alert.php'); ?>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Sales Order No: <span class="tx-danger">*</span></label>
											<input class="form-control" name="sales_order_no" id="sales_order_no" value="<?php echo $this->sales_order_no; ?>" required="" readonly type="text">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Customer Name: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="customer_id" required id="customer_id">
												<option value="">Select customer</option>
												<?php echo $this->customer_option; ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">SO Date:</label>
											<input class="form-control" name="po_date" id="po_date" value="" type="date">
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Delivery Date:</label>
											<input class="form-control" name="delivery_date" id="delivery_date" value="" type="date">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->

				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">FG Name: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="category_id" required id="category_id">
												<option value="">Select category</option>
												<?php echo $this->category_option; ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">SFG Name: <span class="tx-danger">*</span></label>
											<div id="product_dropdown">
												<select class="form-control select2" name="product_id" onchange="show_input(this.value);" id="product_id" required>
													<option value="">Select Product</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Available Quantity: <span class="tx-danger">*</span></label>
											<input class="form-control" name="total_item" id="total_item" value="" required="" type="text" readonly>
										</div>
									</div>
									<div class="col-md-3">
										<label class="form-label">Quantity: <span class="tx-danger">*</span></label>
										<input class="form-control" name="quantity" id="quantity" value="" type="number" min="1">
									</div>
									<div class="col-md-12 text-end">
										<input name="category_name" id="category_name" value="" required="" type="hidden">
										<input name="product_name" id="product_name" value="" required="" type="hidden">

										<button class="btn ripple btn-info" type="button" id="create-sales-order">Add</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->

				<!-- Row -->
				<div class="row hid-tbl">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div class="table-responsive">
									<table id="sales-order-tbl" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>
												<th>Category Name</th>
												<th>Product Name</th>
												<th>Product Alias Name</th>
												<th>Quantity</th>
												<th>Unit</th>
												<th>Price per item</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<?php echo get_additional_info(); ?>
								<div class="tx-center-f">
									<button class="btn ripple btn-primary" type="submit" id="add-btn">Save</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!-- End Row -->
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
	.h-id {
		display: none;
	}
</style>