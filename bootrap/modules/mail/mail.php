<?php
	global $languageid, $template;
	$action      = mosGetParam( $_REQUEST, 'mode', '');

	if (!isset($template))
		$template = new Template();	
	
	$template->assign_vars(array(
		'ROOT'		=> $root_path,		
		'funname'	=> 'mail/mail',
		'LANGUAGEID'=> $languageid,
		
	));		

	switch( $action )
	{	
		case 'info'			:	mosInfo(); break;
		case 'send'			:  	mosSend(); break;	
		default:
			mosInvalidURL();
			exit;
	}
function mosInfo()
	{	
		global $db, $root_path, $skin, $languageid, $template;
		$template->set_filenames_new(array(
			'mail' => 'mail/mail_info.tpl')
		);
		$template->pparse('mail');
	}
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mosSend()
	{		
		global $db, $root_path, $skin, $languageid, $template;	
		$subject 		= mosGetParam( $_REQUEST, 'subject', '');
		$message		= mosGetParam( $_REQUEST, 'description' , '', 0x0003);
		$isTest			= mosGetParam( $_REQUEST, 'test', 0 );
		$tinhtrang		= mosGetParam( $_REQUEST, 'tinhtrang', 0 );
		
		require '../includes/class.phpmailer.php';
		if ( $isTest == 1 )
		{
			$to = "nguyen1the1hung@yahoo.com";
			sendmail($subject,$message,$to);
		}else
		{	exit;
			if ( $tinhtrang == 0 )
			{
				$sql = "select * from tbl_user";
			}
			elseif ( $tinhtrang == 1 )
			{
				$sql = "select * from tbl_user where active = 1";
			}elseif ( $tinhtrang = 2 )
			{
				$cond = "";
				$tomail	= mosGetParam( $_REQUEST, 'tomail', '');
				$cond = ($tomail)?" and email = '".$tomail."'":'';
				$sql = "select * from tbl_user where active = 0 $cond order by user_id DESC limit 10";
				$subject = 'Re:Activation of your account at Hangcu.net';
			}
			$sql = "select * from tbl_customer where email <> ''";
			$order = 0;
			if ( !($result = $db->sql_query($sql)) ) die( SERVER_BUSY );
			while ( $row = $db->sql_fetchrow($result) )
			{
				$to		=	$row['email'];
				if ( $tinhtrang == 2 )
				{
					$username = $row['username'];
					$password = $row['password'];
					$message = "<p>Dear : $username than men,  Buc thu nay duoc gui tu <a href=http://hangcu.net/>http://hangcu.net/</a>    Ban nhan duoc thu nay vi ban (hoac ai do) da su dung dia chi email cua   ban de dang ki thanh vien cua HangCu.net. Neu nhu ban khong phai la   nguoi dang ky, xin hay bo qua buc thu nay. Ban khong phai huy dang ki hay   lam them bat cu dieu gi.    <br>======================================== <br> Huong dan de kich hoat tai khoan cua ban  <br>========================================  <br>  De khang dinh dang ki va active account, ban chi can an vao lien ket sau: <a href=http://hangcu.net/index.php?title=".$password."&value=".$username."&mode=active >http://hangcu.net/index.php?title=".$password."&value=".$username."&mode=active</a><br>  Rat cam on ban da dang ki va chuc ban may man ! </p>";
				}
				sendmail($subject,$message,$to);
				$order ++;
			}
		}	
		$template->assign_vars(array(
			'MESSAGE'	=>	SEND_SUCCESS,
			'order'		=>	$order,
		));
		mosInfo();
	}
	
//---------------------------------------------
function sendmail($subject,$message,$to)
{
try {
	$mail = new PHPMailer(true);
	$body = $message;
	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "mail.bopda.net"; // SMTP server
	$mail->Username   = "info@bopda.net";     // SMTP server username
	$mail->Password   = "thehung";            // SMTP server password
	//$mail->IsSendmail();  // tell the class to use Sendmail
	$mail->AddReplyTo("info@bopda.net","BopDa.net");
	$mail->From       = "info@bopda.net";
	$mail->FromName   = "BopDa.net";
	$mail->AddAddress($to);
	$mail->Subject  = $subject;
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap
	$mail->MsgHTML($body);
	$mail->IsHTML(true); // send as HTML
	$mail->Send();
} catch (phpmailerException $e) {
	echo $e->errorMessage();
}
}
?>