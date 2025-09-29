<?php $product_alias_list = $this->product_alias_list; ?>
<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo UCWORDS(str_replace('-',' ',$this->page_name));?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.strtolower(str_replace('_','-',$this->class_name)).'-list'?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<form action="<?php echo BASE_URL.$this->page_name.'/'.$product_detail->product_id?>" method="POST" data-parsley-validate="">
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Category Name: <span class="tx-danger">*</span></label>
										<select class="form-control select2" name="category_id" required id="category_id">
											<option value="">Select category</option>
											<?php echo $this->category_option; ?>
										</select>
									</div>	
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Product Name: <span class="tx-danger">*</span></label>
										<div id="product_dropdown">
											<select class="form-control select2" name="product_id" onchange="show_input(this.value);" id="product_id" required>
												<option value="">Select Product</option>
												<?php echo $this->product_list; ?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-md-3">
									<label class="form-label">Product Alias Name: <span class="tx-danger">*</span></label>
									<input class="form-control" name="new_prod_name" id="new_prod_name" value="" type="text" required>
								</div>
								
								<div class="col-md-3">
									<label class="form-label">&nbsp;</label>
									<button class="btn ripple btn-info" type="submit" name="save">Save</button>
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
								<table id="sales-order-tbl" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Category Name</th>
											<th>Product Name</th>
											<th>Product Alias Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if(isset($product_alias_list) && !empty($product_alias_list)){ 
											foreach ($product_alias_list as $obj) { ?>
												<tr>
													<td><?php echo $product_detail->category_name; ?></td>
													<td><?php echo $product_detail->product_name; ?></td>
													<td><?php echo $obj->product_alias_name; ?></td>
													<td>
														<?php echo get_delete_btn(BASE_URL.'delete-product-alias/'.$obj->product_alias_id); ?>
													</td>
												</tr>
											<?php }	}else{ ?>
											<tr><td colspan='4' align="center" class="tx-danger">No record found!</td></tr>
										<?php } ?>
									</tbody>
								</table>
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
	.table>thead>tr>th{ font-size:13px; font-weight:500; text-transform:unset; }
	.no-border{ padding:0; border: unset; }
</style>