<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;"></div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h4 class="text-start" style="float: left;"><?php echo ucfirst(str_replace('-',' ',$page_title))?></h4>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-success btn-sm" href="#" data-bs-target="#add-invoice" data-bs-toggle="modal" title="Add Invoice"><i class="fe fe-plus"></i></a>
								</div>								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:10%;">Sl. No.</th>
											<th style="width:40%;">Invoice Name</th>
											<th style="width:20%;">Action</th>
										</tr>
									</thead>
									<tbody>
									 <?php if($invoice_list): $i=1;
										foreach($invoice_list as $obj): ?>
										<tr>
											<td><?php echo $i; $i++; ?></td>
											<td><?php echo $obj->invoice_name ?></td>
											<td>
											<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit Sales Type" onclick="show_invoice_delete_popup('<?php echo $obj->invoice_id ?>','<?php echo $obj->invoice_name ?>')"><i class="fe fe-edit"></i></a>
												<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'delete-invoice/'.$obj->invoice_id ?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button>
											</td>
										</tr>
									<?php endforeach;									
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
<?php $this->load->view('Backend/Settings/add-invoice');?>


<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-invoice').modal('show');
	});
</script>
<?php } ?>