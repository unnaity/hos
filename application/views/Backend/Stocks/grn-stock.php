<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header">
				<h3 class="text-start" ><?php echo "QC Pending List"; //echo UCWORDS(str_replace('-', ' ',$this->page_name)); ?></h3>
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
											<th>GRN No.</th>
											<th>GRN Date</th>
											<th>OEM</th>
											<th>Category</th>
											<th>Product name</th>
                                            <th>Total Item</th>											
										</tr>
									</thead>
									<tbody>
									<?php if(isset($grn_stock)):
										foreach($grn_stock as $obj): ?>
										<tr>
											<td><?php echo $obj->product_grn_no?></td>
											<td><?php echo $obj->grn_date?></td>
											<td><?php echo $obj->company_name?></td>
											<td><?php echo $obj->category_name ?></td>
											<td style="width:40%"><?php echo $obj->product_name ?></td>
											<td><?php echo $obj->no_of_items; ?></td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='11'>No record found!</td></tr>";
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