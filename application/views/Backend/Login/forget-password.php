<!-- Page -->
<div class="page main-signin-wrapper">
    <!-- Row -->
    <div class="row text-center ps-0 pe-0 ms-0 me-0">
        <div class="col-xl-5 col-lg-5 col-md-5  d-block mx-auto">
            <?php $this->load->view('Backend/Login/logo.php'); ?>
            <div class="card custom-card">
                <div class="card-body pd-45">
                    <h4 class="text-center">Forgot Password</h4>
                    <form>
                        <div class="form-group text-start">
                            <label>Email</label>
                            <input class="form-control" placeholder="Enter your email" type="email" value="" require>
                        </div>
                        <button class="btn ripple btn-main-primary btn-block">Request reset link</button>
                    </form>
                </div>
                <div class="card-footer border-top-0 text-center">
                    <p>Did you remembered your password?</p>
                    <p class="mb-0"><a href="<?php echo BASE_URL ?>">Try to Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row-->
</div>    