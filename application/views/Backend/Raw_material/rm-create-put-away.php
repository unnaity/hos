<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start">FG Stock IN</h4>
                <div class="btn btn-list">
					<a href="<?php echo BASE_URL.'rm-put-away-list';?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>

            <!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
                           
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row row-xs align-items-center mg-b-20">
                                            <div class="col-md-3">
                                                <label class="mg-b-0">Location QR:</label>
                                            </div>
                                            <div class="col-md-5 mg-t-5 mg-md-t-0">
                                                <input class="form-control" name="location_no" id="location_no" type="text" required value="">
                                                <span style="display:none" class="parsley-errors-list error_msg" id="error_msg"></span>
                                            </div>
                                        </div>
                                        
                                        <div class="row row-xs align-items-center mg-b-20 l_name" style="display:none" >
                                            <div class="col-md-3">
                                                <label class="mg-b-0">Location Name:</label>
                                            </div>
                                            <div class="col-md-5 mg-t-5 mg-md-t-0">
                                                <input class="form-control" name="location_name" id="location_name" type="text" readonly value="">
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-md-6">	
                                        <div class="row row-xs align-items-center mg-b-20">
                                            <div class="col-md-2">
                                                <label class="mg-b-0">Product QR:</label>
                                            </div>
                                            <div class="col-md-5 mg-t-5 mg-md-t-0">
                                                <input class="form-control" type="text" name="rm_box_no" id="rm_box_no" required value="">
                                            </div>
                                        </div>
                                        <div id="p_detail"></div>
                                    </div>    
                                </div>	
                                <!-- <div class="row">
                                    <div class="col-md-12 tx-center-f">                                        
                                        <button class="btn ripple btn-primary" type="submit">Save</button>
                                    </div>
                                </div>	 -->
                            						
						</div>					
					</div>
				</div>
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->