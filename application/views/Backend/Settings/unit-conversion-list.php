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
									<!--a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a-->								
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-unit-conversion" data-bs-toggle="modal" title="Add Unit Conversion"><i class="fe fe-plus"></i></a>
								</div>
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>From</th>
											<th>To</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($unit_conversion):
										foreach($unit_conversion as $obj): ?>
										<tr>
											<td><?php echo $obj->from_unit_value.' '.$obj->from_unit_text; ?></td>
											<td><?php echo $obj->to_unit_value.' '.$obj->to_unit_text; ?></td>
											<td>
												<?php echo get_delete_btn(BASE_URL.'delete-unit-conversion/'.$obj->unit_conversion_id); ?>
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
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->
<?php $this->load->view('Backend/Settings/add-unit-conversion');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-unit-conversion').modal('show');
	});
</script>
<?php } ?>