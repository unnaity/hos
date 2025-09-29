<?php if($this->session->flashdata('error_message')): ?>
    <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
        <span class="alert-inner--icon"><i class="fa fa-exclamation-triangle"></i></span>
        <span class="alert-inner--text"><strong>Error ! </strong> <?php echo $this->session->flashdata('error_message'); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
<?php endif; ?>

<?php if(validation_errors()): ?>
    <label class="tx-danger"><?php echo validation_errors(); ?></label>
<?php endif; ?>