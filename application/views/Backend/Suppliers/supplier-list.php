<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h3 class="text-start" ><?php //echo UCFIRST($this->class_name); ?>Vendor List</h3>
				<div class="btn btn-list">
					<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>				
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'add-supplier'?>" title="Add Vendor"><i class="fe fe-plus"></i></a>
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
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Company Name</th>
											<th>Email</th>
											<th>Contact No.</th>
											<th>Address</th>											
											
										</tr>
									</thead>
									<tbody>
									<?php if(isset($this->supplier_list)):
										foreach($this->supplier_list as $obj): ?>
										<tr>
											<td><?php echo $obj->company_name ?></td>											
											<td><?php echo $obj->email ?></td>
											<td><?php echo $obj->mobile_no ?></td>
											<td><?php echo $obj->address ?></td>
											<!-- <td>
												<button id="bDel" type="button" class="btn  btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button>
											</td> -->
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='5'>No record found!</td></tr>";
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
<Style>
	.table.dataTable th, .table.dataTable td{ font-size:12px; font-weight:500; }
	.dataTables_length{ display:none;}
</style>