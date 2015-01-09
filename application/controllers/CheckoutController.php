<?php

class CheckoutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function sendMailAction()
    {

        $config = array('auth' => 'login',
            'username' => 'user',
            'password' => 'password',
            'ssl' => 'tls',
            'port' => 587);

        $user = Zend_Auth::getInstance()->getStorage()->read();
        $email = $user->email;
        $name =  $user->name;

        $tr = new Zend_Mail_Transport_Sendmail('sadeq1989@gmail.com');
        Zend_Mail::setDefaultTransport($tr);
        $trsmtp = new Zend_Mail_Transport_Smtp('smtp.gmail.com',$config);
        Zend_Mail::setDefaultTransport($trsmtp);

        if($user != null) {
            $mail = new Zend_Mail();
            $mail->setBodyText('Vielen Dank für Ihre Bestellung bei Smoothieme. Dies ist eine Bestätigungsemail.')
                ->setFrom('sadeq1989@gmail.com', 'SmoothieMe Shop')
                ->addTo($email, $name)
                ->setSubject('Ihre Bestellung bei Smoothieme')
                ->send();
        }
        else
            echo "Die Email konnte nicht versendet werden!";
    }

}



