<?php
require '../vendor/mandrill/mandrill/src/Mandrill.php';
class Email
{
    public $manrill;
    public $message;
    public $key;
    public $sender;


    public function __construct($data)
    {
        //return $this->manrill = new Mandrill('key');
        $this->key = $data['key'];
        $this->sender = $data['email'];
        $this->manrill = new Mandrill($this->key);
    }


    public function sendMail($subject, $to, $htmlContent)
    {
        $fromName = 'SEO MOnitoring'; // Sender Name
        $from = $this->sender; //sender email
        $this->message = array(
            'subject' => $subject,
            'from_email' => '.' . $from,
            'from_name' => $fromName,
            'html' => $htmlContent,
            'to' => array(
                array(
                    'email' => $to,
                    'type' => 'to'
                )
            ),
            'merge_vars' => array(
                array(
                    'rcpt' => $to,
                    'vars' =>
                        array(
                            array(
                                'name' => 'FIRSTNAME',
                                'content' => 'Recipient 1 first name'),
                            array(
                                'name' => 'LASTNAME',
                                'content' => 'Last name')
                        )
                )
            )
        );
        $result = $this->manrill->messages->send($this->message, $async = false, $ip_pool = '', $send_at = '');
        return $result;
    }

}

/*
$queryMail = "SELECT * FROM SETTING_EMAIL WHERE MAILER = 'GMAIL' AND STATUS = 'A'";
$rows = $dbObj->fetchArray( $dbObj->doQuery($queryMail));
$host = $rows[0]['HOST'];
$port = $rows[0]['PORT'];
$userName = $rows[0]['USERNAME'];
$password = $rows[0]['PASSWORD'];
$fromName = $rows[0]['FROM_NAME'];
$formMail = $rows[0]['FROM_MAIL'];


//$mail->SMTPDebug = 3;
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->SMTPAuth = true;
$mail->Host = $host; // "ssl://smtp.gmail.com" didn't worked
$mail->SMTPSecure = 'tls'; // or 'ssl'
$mail->Port = $port; ;  //587 for tls 465 for ssl

$mail->Username = $userName;
$mail->Password = $password;

$mail->IsHTML(true); // if you are going to send HTML formatted emails
$mail->SingleTo = true; // if you want to send a same email one-by-one to multiple users in .

$mail->From = $formMail;
$mail->FromName = $fromName;

$mail->addAddress("mahedi2014@gmail.com","User 1");
$mail->addAddress("mds_fahad@yahoo.com","User 2");

$mail->addCC("mahedi2014@gmail.com","User 3");
$mail->addBCC("mahedi2014@gmail.com","User 4");

//$mail->addAttachment('/var/tmp/file.tar.gz');// Add attachments
//$mail->addAttachment('/tmp/image.jpg','new.jpg');  // Optional name
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->Subject = "Testing PHPMailer with localhost";
$mail->Body = "Hi,<br /><br />This system is working perfectly.";

if(!$mail->Send())
    echo "Message was not sent <br />PHPMailer Error: " . $mail->ErrorInfo;
else
    echo "Message has been sent";*/