<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start" >Quality Check List</h4>
				<div class="btn btn-list">
					<!--a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a-->
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'product-quality-check'?>" title="Product Quality Check"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>

            <!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<!--div class="card-body">							
							<?php //$this->load->view('Backend/Elements/Alert.php'); ?>							
                            <form action="" id="create-grn" method="POST" data-parsley-validate="">
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
                                            <label class="form-label">GRN from Date:</label>
                                            <input class="form-control" name="from_date" id="from_date" value="" type="date">
                                        </div>	
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">GRN to Date:</label>
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
						</div-->
					
						<div class="card-body">							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
                                            <th>GRN No.</th>											
                                            <th>Product name</th>
											<th>Category</th>
											<th>Vendor</th>
                                            <th>No. of Boxes</th>
                                            <th>No. of Items</th>
											<th>Pur. Price<br> per Item</th>
											<th>Date</th>
                                            <th>Barcode</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($quality_check_list)):
										foreach($quality_check_list as $obj): ?>
										<tr>
                                            <td><?php echo $obj->product_grn_no ?></td>
                                            <td style="width:130px !important;"><?php echo $obj->product_name ?></td>
                                            <td><?php echo $obj->category_name ?></td>
											<td><?php echo $obj->company_name ?></td>
											<td><?php echo $obj->no_of_boxes?></td>
											<td><?php echo $obj->quality_checked_item .' '.$obj->unit;?></td>
											<td><?php if($obj->purchase_price_per_item > 0){
														echo $obj->purchase_price_per_item;
												}else{ ?>
													<input type="text" value="<?php echo $obj->purchase_price_per_item; ?>" name="purchase_price_per_item" id="purchase_price_per_item_<?php echo $obj->product_grn_detail_id?>" style="width:100px !important;" onchange="update_purchase_price('<?php echo $obj->product_grn_detail_id?>')" >
												<?php } ?>	
											</td>											
											<td><?php echo date('Y-m-d',strtotime($obj->grn_date))?></td>
											<td>
                                                <a class="btn btn-sm btn-info" href="<?php echo BASE_URL.'box-slip/'.$obj->product_grn_detail_id; ?>" title="Print Barcode" target="_blank"><i class="fe fe-printer"></i>
											</td>
										</tr>
										<?php endforeach;
										else: echo "<tr><td colspan='8'>No record found!</td></tr>";
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