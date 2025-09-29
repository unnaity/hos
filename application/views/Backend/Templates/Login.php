<?php 
$this->load->view('Backend/Elements/login_header.php');
$this->load->view('Backend/Elements/loader.php');
$this->load->view('Backend/Login/'.$page_name.'.php');
$this->load->view('Backend/Elements/login_footer.php');
?>