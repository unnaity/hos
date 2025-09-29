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
								<h3 class="text-start" style="float: left;"><?php echo UCFIRST($page_title); ?></h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>								
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-operations" data-bs-toggle="modal" title="Add New Tax Rate"><i class="fe fe-plus"></i></a>
								</div>
								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:45%;">Tax rate</th>
											<th style="width:50%;">Tax name</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($tax_rate_list):
										foreach($tax_rate_list as $obj): ?>
										<tr>
											<td><?php echo $obj->tax_rate ?> %</td>
											<td><?php echo $obj->tax_name ?></td>
											<td>
												<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'tax-rate-delete/'.$obj->tax_id ?>');" class="btn  btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button>
											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='3'>No record found!</td></tr>";
									endif;
									?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php if($tax_rate_list): ?>									
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">							
							<form action="<?php echo BASE_URL.'default-tax-rates'?>" method="POST" data-parsley-validate="">					
								<div class="form-group">
									<label class="form-label">Default tax on Sales order: <span class="tx-danger">*</span></label>
									<select class="form-control select2" name="sales_order">
									<?php foreach($tax_rate_list as $obj): ?>
										<option value="<?php echo $obj->tax_id;?>">
										<?php echo $obj->tax_name.' - '.$obj->tax_rate.' %';?>
										</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group">
									<label class="form-label">Default tax on Purchase order:</label>
									<select class="form-control select2" name="purchase_order">
									<?php foreach($tax_rate_list as $obj): ?>
										<option value="<?php echo $obj->tax_id;?>">
										<?php echo $obj->tax_name.' - '.$obj->tax_rate.' %';?>
										</option>
										<?php endforeach; ?>
									</select>
								</div>	
								<div class="form-group pull-right">
									<button class="btn ripple btn-primary" type="submit">Update</button>
								</div>	
							</form>
						</div>	
					</div>	
				</div>
				<?php endif; ?>
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->

<?php $this->load->view('Backend/Settings/add-tax-rate');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-operations').modal('show');
	});
</script>
<?php } ?>