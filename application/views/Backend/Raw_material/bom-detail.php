<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header"style="min-height:unset;">
				<h4 class="text-start"><?php echo UCWORDS(str_replace('-',' ',$this->page_name)); ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.'bom-list';?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
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
											<th>FG Name</th>
											<th>Material Type</th>
                                            <th>Item Name</th>
											<th>Quantity</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($bom_list_detail)): $i=1;
										foreach($bom_list_detail as $obj): ?>
										<tr>
											<td style="width:5%"><?php echo $i; ?></td>
											<td><?php echo strtoupper($obj->fg_discription) ?></td>
											<td><?php echo strtoupper($obj->grn_type) ?></td>
                                            <td><?php echo strtoupper($obj->item_name) ?></td>
											<td><?php echo $obj->quantity ?></td>
											
										</tr>
									<?php $i++; endforeach;
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