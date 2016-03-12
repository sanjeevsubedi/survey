<?php

/**
 * Helper to send mail via php mailing function
 * 
 * @author Bidur B.K
 * @version 1.0
 * @date Dec 12, 2012
 * @copyright Copyright (c) 2012, neolinx.com.np
 * 
 */

if ( ! function_exists('_sendmail'))
{
    function  _sendmail($email, $subject, $message, $from_name, $from_email)
    {
        $header  = "MIME-Version: 1.0\r\n".
                   "Content-type: text/html; charset=UTF-8\r\n".
                   "From: ".$from_name." <".$from_email.">\r\n".
                   "Reply-To: ".$from_name." <".$from_email.">\r\n".
                   "X-Mailer: PHP/" . phpversion();
        try{
            mail($email, $subject, $message, $header);
            return true;
        } catch (Exception $ex){
           return false; 
        }
    }
}

if ( ! function_exists('__sendmail'))
{
    function  __sendmail($email, $subject, $message, $from_name, $from_email)
    { 
        //$ci =& get_instance();
        //$ci->load->library('phpmailer/phpmailer.inc');
        require_once APPPATH.'libraries/phpmailer/phpmailer.inc.php'; 
        $mail = new PHPMailer;
        $mail->IsSMTP();  
        //$mail->IsMail();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'sanjeev@neolinx.com.np';                            // SMTP username
        $mail->Password = 'sanjeev@123';                           // SMTP password
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = '587'; 
        
        $mail->CharSet="utf-8";
        $mail->From = $from_email;
        $mail->FromName = $from_name;
        //$mail->AddAddress('josh@example.net', 'Josh Adams');  // Add a recipient
        $mail->AddAddress($email);               // Name is optional
        //$mail->AddReplyTo('info@example.com', 'Information');
        $mail->AddCC('info@moenius.com');
        //$mail->AddBCC('bcc@example.com');
        $mail->IsHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $message;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        try{
            $mail->Send();
            return true;
        } catch (Exception $ex){
           //echo 'Mailer Error: ' . $mail->ErrorInfo;die;
           return false; 
        }
    }
}
/* End of file sendmail_helper.php */
/* Location: application/helpers/sendmail_helper.php */
?>
