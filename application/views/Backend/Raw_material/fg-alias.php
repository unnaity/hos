<!-- Main Content-->
<div class="main-content pt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <!-- Page Header -->
            <div class="page-header" style="min-height:unset;">
                <h4 class="text-start mb-0">FG Alias</h4>
                <div class="btn btn-list">
                    <a href="<?php echo BASE_URL . 'fg-list'; ?>">
                        <button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
                </div>
            </div>
            <!-- End Page Header -->
            <!-- Row -->
            <form action="<?php echo BASE_URL . 'fg-alias/' . $this->fg_id ?>" method="POST">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card custom-card">
                            <div class="card-body">
                                <?php $this->load->view('Backend/Elements/Alert.php'); ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">FG Name: <span class="tx-danger">*</span></label>
                                            <div id="product_dropdown">
                                                <select class="form-control select2" name="fg_id" id="fg_id" required>
                                                    <option value="">Select Product</option>
                                                    <?php foreach ($this->fg_list as $key) { ?>
                                                        <option value="<?php echo $key->fg_id ?>"
                                                            <?php echo (!empty($this->fg_id) && $this->fg_id == $key->fg_id) ? 'selected' : ''; ?>>
                                                            <?php echo $key->fg_code. ' / ' . $key->fg_discription; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Vendor: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="supplier" id="supplier">
												<option value="">Select vendor</option>
												<?php echo $this->supplier_option; ?>
											</select>
											<input type="hidden" name="supplier_id" id="supplier_id" value="">
											<input type="hidden" name="supplier_name" id="supplier_name" value="">
										</div>
									</div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">FG Alias Name:<span class="tx-danger">*</span></label>
                                        <input class="form-control" name="new_prod_name" id="new_prod_name" value="" type="text" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button class="btn ripple btn-info" type="submit" name="save">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->

                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
                                        <thead>
                                            <tr>
                                                <th>FG Code</th>
                                                <th>Vendor</th>
                                                <th>FG Alias Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($this->product_alias_list) && !empty($this->product_alias_list)) {
                                                foreach ($this->product_alias_list as $obj) { ?>
                                                    <tr>
                                                        <td><?php echo $obj->fg_code; ?></td>
                                                        <td><?php echo $obj->company_name; ?></td>
                                                        <td><?php echo $obj->product_alias_name; ?></td>
                                                        <td>
                                                            <?php echo get_delete_btn(BASE_URL . 'delete-fg-alias/' . $obj->product_alias_id); ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td colspan='4' align="center" class="tx-danger">No record found!</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- End Row -->
        </div>
    </div>
</div>
<!-- End Main Content-->

<Style>
    .table>thead>tr>th {
        font-size: 13px;
        font-weight: 500;
        text-transform: unset;
    }

    .no-border {
        padding: 0;
        border: unset;
    }
</style>