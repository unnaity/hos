<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header">
				<h3 class="text-start"><?php echo UCWORDS(str_replace('-', ' ',$this->page_name)); ?></h3>
				
			</div>
				
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>Material Type</th>
											<th>Item Name</th>
											<th>No. of Boxes</th>
                                            <th>Total Items</th>
											<th>Remaining Items</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($total_stock_value)): $i=1;
										foreach($total_stock_value as $obj): 
										?>
										<tr>
											<td style="width:10%;"><?php echo $i;?></td>
											<td><?php echo strtoupper($obj->grn_type); ?></a></td>
											<td><?php echo strtoupper($obj->name );?></td>
											<td><?php echo $obj->number_of_boxes; ?></td>
											<td><?php echo $obj->total_item; ?></td>
											<td><?php echo $obj->remaining_item; ?></td>
										</tr>
									<?php $i++; endforeach;
									else: echo "<tr><td colspan='3'>No record found!</td></tr>";
									endif;
									?>
										
									</tbody>
								</table>
								<input type="hidden" value="<?php echo number_format($total_value,2);?>" name="total_value" id="total_value">
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