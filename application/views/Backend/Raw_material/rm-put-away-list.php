<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start" >Put Away List</h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'rm-create-put-away'?>" title="Add RM Put Away"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>

            <!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php //$this->load->view('Backend/Elements/Alert.php'); ?>							
                            <!-- <form action="" id="create-grn" method="POST" data-parsley-validate="">
                                <div class="row">	
                                    <div class="col-md-3">	
                                        <div class="form-group">
                                            <label class="form-label">Product GRN No:</label>
                                            <input class="form-control" name="grn_no" id="grn_no" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Product name:</label>
                                            <input class="form-control" name="product_name" id="product_name" value="" type="text">
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
                                </div>	
                                <div class="row">
                                    <div class="col-md-12 tx-center-f">                                        
                                        <button class="btn ripple btn-info" type="submit" >Find</button>
                                    </div>
                                </div>	
                            </form> 							
						</div> -->
					
						<div class="card-body">							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
                                            <th>Rack No.</th>
                                            <th>Location Name</th>
											<th>Category</th>
											<th>Product Name</th>
                                            <th>Box No.</th>
                                            <th>Box Slip</th>
											<th>GRN Date</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($put_away_list)):
										foreach($put_away_list as $obj): ?>
										<tr>
                                            <td><?php echo $obj->location_no ?></td>
                                            <td><?php echo $obj->location_name ?></td>
                                            <td><?php echo $obj->category_name ?></td>
											<td><?php echo $obj->product_name ?></td>
											<td><?php echo $obj->box_no ?></td>
											<td>
                                                <a class="btn btn-sm btn-info" href="<?php echo BASE_URL.'box-slip/'.$obj->product_grn_detail_id; ?>" title="Print Barcode" target="_blank"><i class="fe fe-printer"></i>
											</td>
											<td><?php echo date('Y-m-d',strtotime($obj->put_away_date))?></td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='7' align='center'>No record found!</td></tr>";
									endif;
									?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->