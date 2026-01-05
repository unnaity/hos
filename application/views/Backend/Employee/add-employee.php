<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Add ".UCFIRST($this->class_name); ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.$this->page_title.'-list'?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="panel panel-primary">								
								<div class="panel-body tabs-menu-body">
									<div class="tab-content">
										<form action="<?php echo BASE_URL.$this->page_name?>" method="POST" data-parsley-validate="">
											<div class="row">	
												<div class="col-md-6">							
													<div class="form-group">
														<label class="form-label">Employee name: <span class="tx-danger">*</span></label>
														<input class="form-control" name="employee_name" id="employee_name" value="<?php echo set_value('employee_name'); ?>" placeholder="Enter employee name" required="" type="text">
													</div>
													<div class="form-group">
														<label class="form-label">DOB: </label>
														<input class="form-control" name="employee_dob" id="employee_dob" value="<?php echo set_value('employee_dob'); ?>" placeholder="Select employee dob" type="date">
													</div>
													<div class="form-group">
														<label class="form-label">Gender: </label>
														<div class="row">
															<div class="col-lg-3">
																<label class="rdiobox">
																	<input type="radio" name="employee_gender" value="Male">
																	<span>Male</span>
																</label>
															</div>
															<div class="col-lg-3 mg-t-20 mg-lg-t-0">
																<label class="rdiobox">
																	<input type="radio" name="employee_gender" value="Female">
																	<span>Female</span>
																</label>
															</div>
															<div class="col-lg-3 mg-t-20 mg-lg-t-0">
																<label class="rdiobox">
																	<input type="radio" name="employee_gender" value="Other">
																	<span>Other</span>
																</label>
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="form-label">Address:</label>
														<textarea class="form-control" cols="40" name="employee_address"></textarea>
													</div>		
												</div>		
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Contact no.: <span class="tx-danger">*</span></label>
														<input class="form-control" name="mobile_no" id="mobile_no" value="<?php echo set_value('mobile_no'); ?>" placeholder="Enter mobile name" required="" type="number" >
													</div>
													<div class="form-group">
														<label class="form-label">Email: </label>
														<input class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="Enter email id" type="email">
													</div>
													<div class="form-group">
														<label class="form-label">Designation: </label>
														<input class="form-control" name="employee_designation" id="employee_designation" value="<?php echo set_value('employee_designation'); ?>" placeholder="Enter employee designation" type="text">
													</div>
													<div class="form-group">
														<label class="form-label">Department: <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search" name="department_id" required>
															<option value="">Select department</option>
															<?php if($department_list){ foreach($department_list as $obj): ?>
															<option value="<?php echo $obj->department_id;?>">
															<?php echo $obj->department_name;?>
															</option>
															<?php endforeach; } ?>
														</select>				
													</div>													
												</div>
												<div class="col-md-12 tx-center"><button class="btn ripple btn-primary" type="submit">Save</button></div>
											</div>											
											
										</form>
									</div>
								</div>
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
<style>
	.richText .richText-editor{ height:75px;}
</style>