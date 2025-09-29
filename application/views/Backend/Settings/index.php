<?php 
$this->load->view('Backend/Elements/head-tag.php');
$this->load->view('Backend/Elements/loader.php');
$this->load->view('Backend/Elements/header.php');
$this->load->view('Backend/Elements/left-menu.php');
$this->load->view('Backend/Settings/'.$page_name.'.php');
$this->load->view('Backend/Elements/footer.php');
?>