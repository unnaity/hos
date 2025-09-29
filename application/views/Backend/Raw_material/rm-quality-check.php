<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo UCWORDS(str_replace('-',' ',$this->page_name));?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.'quality-check-list';?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<form action="<?php echo BASE_URL.$this->page_name?>" method="POST" data-parsley-validate="" onsubmit="return check_quality_form()">
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="row">	
								<div class="col-md-3">
									<div class="row">	
										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label">GRN No.: <span class="tx-danger">*</span></label>
												<select class="form-control select2" name="rm_grn_id" required id="rm_grn_id">
													<option value="">Select GRN Id</option>
													<?php if($grn_list): 
                                                        foreach($grn_list as $obj): ?>
                                                        <option value="<?php echo $obj->rm_grn_id;?>"><?php echo $obj->rm_grn_no;?></option>
                                                    <?php endforeach;
                                                        endif;
                                                    ?>    
												</select>
												
											</div>	
										</div>										
									</div>
								</div>
								<div class="col-md-12">
									<div class="table-responsive hid-tbl">
										<h5>GRN Details</h5>
										<table id="" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
											<thead>
                                                <tr>
                                                    <th>GRN No.</th>
                                                    <th>GRN Date</th>
													<th>Product type</th>
                                                    <th>Product name</th>
                                                    <th>Vendor</th>
                                                    <th>No. of Items</th>
													<th>No. of Boxes</th>
                                                    <th>Quality Checked Item</th>
													<th>Quality Checked Boxes</th>
													<th>Purchase Price Per Item</th>
                                                    <th>No. of Boxes</th>
													<th>Product Status</th>
                                                </tr>
											</thead>
											<tbody id="rm_grn_list">
											</tbody>
										</table>
									</div>									
								</div>
                                <div class="tx-center-f hid-tbl">
									<button class="btn ripple btn-primary" type="submit" id="add-btn">Save</button>
								</div>	
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