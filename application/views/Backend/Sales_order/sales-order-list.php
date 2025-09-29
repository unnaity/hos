<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start" >Sales Order List</h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>				
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'create-sales-order'?>" title="Create Sales Order"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>

            <!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>							
                            <!-- <form action="" id="create-grn" method="POST" data-parsley-validate=""> -->
                                <!-- <div class="row">	
                                    <div class="col-md-3">	
                                        <div class="form-group">
                                            <label class="form-label">Sales Order No:</label>
                                            <input class="form-control" name=sales_order_no" id="sales_order" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Customer Name: </label>
											<select class="form-control select2" name="customer_id" required id="customer_id">
												<option value="">Select customer</option>
												<?php echo $this->customer_option; ?>
											</select>
										</div>	
									</div>
 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Sales Order from Date:</label>
                                            <input class="form-control" name="from_date" id="from_date" value="" type="date">
                                        </div>	
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Sales Order to Date:</label>
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
                                            <th>Sales Order No.</th>
                                            <th>PO No.</th>
											<th>PO Date</th>
											<th>Customer Name</th>
                                            <th>Delivery Date</th>											
                                            <th>Detail</th>											
										</tr>
									</thead>
									<tbody>
									<?php if(isset($this->sales_order_list)):
										foreach($this->sales_order_list as $obj): ?>
										<tr>
                                            <td><?php echo $obj->sales_order_no ?></td>
                                            <td><?php echo $obj->purchase_order_id ?></td>
                                            <td><?php echo $obj->po_date ?></td>											
											<td><?php echo $obj->company_name ?></td>
											<td><?php echo $obj->delivery_date?></td>
											<td>
														<a class="btn btn-sm btn-info" href="<?php echo BASE_URL . 'sales-order/' . $obj->sales_order_id; ?>" title="View Detail" target="_blank"><i class="fe fe-eye"></i></a>
														<a href="<?php echo BASE_URL.'sales-order-edit/' . $obj->sales_order_id; ?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> 
												</a>
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