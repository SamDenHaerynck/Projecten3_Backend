<?php

include_once APP_EXT_LIBRARIES . "/autoload.php";

class Email {

    /**
     * Sends an email to a user with a new randomly generated password
     *
     * @param String  $email  The email address to send the new password to
     * @param String  $newpassword  The new randomly generated password
     * 
     */
    public static function sendPasswordResetMail($email, $newpassword) {
        $text = "Wij hebben het wachtwoord voor uw Joetz account gereset.\nUw nieuwe wachtwoord is:</br><b>$newpassword";
        $html = "<html>
       <head></head>
       <body>
           <p>Wij hebben het wachtwoord voor uw Joetz account gereset.</p>
           <p>Uw nieuwe wachtwoord is:</br><b>$newpassword</b></p>
       </body>
       </html>";
        
        //The From email address
        $from = array('admin@project-groep6.azurewebsites.net' => 'Joetz Webapp');
        
        //Email recipients
        $to = array(
            $email
        );
        
        //Email subject
        $subject = 'Nieuw wachtwoord Joetz';

        //Login credentials
        $username = 'groep6';
        $password = 'bfd4whp8nc';

        //Setup Swift mailer parameters
        $transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
        $transport->setUsername($username);
        $transport->setPassword($password);
        $swift = Swift_Mailer::newInstance($transport);

        // Create a message (subject)
        $message = new Swift_Message($subject);

        //Attach the body of the email
        $message->setFrom($from);
        $message->setBody($html, 'text/html');
        $message->setTo($to);
        $message->addPart($text, 'text/plain');

        //Send message or throw RestException if sending fails
        if (!$recipients = $swift->send($message, $failures)) {
            Errorhandler::throwRestException();
        }
    }

}
