<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
    //include(BASEPATH.'/phpmailer/class.phpmailer.php');
	function account_opening_email($account_type = '' , $email = '')
	{
		$system_name	=	$this->db->get_where('settings' , array('type' => 'system_name'))->row()->description;
		
		$email_msg		=	"Welcome to ".$system_name."<br />";
		$email_msg		.=	"Your account type : ".$account_type."<br />";
		$email_msg		.=	"Your login password : ".$this->db->get_where($account_type , array('email' => $email))->row()->password."<br />";
		$email_msg		.=	"Login Here : ".base_url()."<br />";
		
		$email_sub		=	"Account opening email";
		$email_to		=	$email;
		
		$this->do_email($email_msg , $email_sub , $email_to);
	}
	
	function password_reset_email($new_password = '' , $account_type = '' , $email = '' , $token = '')
	{
		include(BASEPATH.'/phpmailer/class.phpmailer.php');
		$query			=	$this->db->get_where('admin' , array('email' => $email));
		//echo $this->db->last_query();exit;	
		if($query->num_rows() > 0)
		{
			$emailencode=base64_encode($_REQUEST['email']);
			$tokenencode=base64_encode($_REQUEST['token']);
			$linkclicktime=date('Y-m-d H:i:s');
			"<a href='http://localhost/SwadeshDarshan/trunk/code/index.php?login/resetpassword/".$emailencode."/".$tokenencode."'>Click here to reset password</a>";	
			$email_msg	=	"Your account type is : ".$account_type."<br />";
			$email_msg	.=	"Your password is : ".$new_password."<br />";
			
			$email_sub	=	"Password reset request";
			$email_to	=	$email;
			$this->mailsend($email_msg , $email_sub , $email_to);

			return true;
		}
		else
		{	
			return false;
		}
	}
	
	/***custom email sender****/
	/*function do_email($msg=NULL, $sub=NULL, $to=NULL, $from=NULL)
	{
		
		$config = array();
        $config['useragent']	= "CodeIgniter";
        $config['mailpath']		= "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']		= "smtp";
        $config['smtp_host']	= "localhost";
        $config['smtp_port']	= "25";
        $config['mailtype']		= 'html';
        $config['charset']		= 'utf-8';
        $config['newline']		= "\r\n";
        $config['wordwrap']		= TRUE;

        $this->load->library('email');

        $this->email->initialize($config);

		$system_name	=	$this->db->get_where('settings' , array('type' => 'system_name'))->row()->description;
		if($from == NULL)
			$from		=	$this->db->get_where('settings' , array('type' => 'system_email'))->row()->description;
		
		$this->email->from($from, $system_name);
		$this->email->from($from, $system_name);
		$this->email->to($to);
		$this->email->subject($sub);
		
		$msg	=	$msg."<br /><br /><br /><br /><br /><br /><br /><hr /><center><a href=\"http://codecanyon.net/item/fps-school-management-system-pro/6087521?ref=joyontaroy\">&copy; 2013 FPS School Management System Pro</a></center>";
		$this->email->message($msg);
		
		$this->email->send();
		
		//echo $this->email->print_debugger();
	}*/
	function mailsend($email,$message,$subject)
    {
        $mail = new PHPMailer;
        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.hstpl.com';                 // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'sheetal.rawat@hstpl.com';                // SMTP username
        $mail->Password = 'SheeTal@1234';                  // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->From = 'shubhika.jain@hstpl.com';
        $mail->FromName = 'sheetal';

        $mail->AddAddress($email, $email);  // Add a recipient
        $mail->IsHTML(true);                                 // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        if(!$mail->Send()) 
        {
           echo 'Message could not be sent.';
           echo 'Mailer Error: ' . $mail->ErrorInfo;
           exit;
        }
        echo 'Message has been sent';
    }
}

