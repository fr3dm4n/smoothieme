<?php

class CheckoutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function preDispatch()
    {

        // Validiere Zugang
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Sie müssen sich zuerst anmelden.");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }

    }

    public function indexAction()
    {
        // action body
        list($orderprice, $smoothies) = $this->getOrders();
        $this->view->smoothies = $smoothies;
        $this->view->orderprice = $orderprice;
    }

    public function sendMailAction()
    {

        $config = array('auth' => 'login',
            'username' => 'smoothieme@itsh-solution.de',
            'password' => 'smoo1234',
            'ssl' => 'ssl',
            'port' => 465);

        $user = Zend_Auth::getInstance()->getStorage()->read();

        $mapper = new Application_Model_CustomerMapper();

        $customer = $mapper->getCustomerByAccId($user->ID);
        $lastname = $customer->getLastname();

        $email = $user->email;
        $this->view->email = $email;
        $name =  $user->name;

        $tr = new Zend_Mail_Transport_Sendmail('smoothieme@itsh-solution.de');
        Zend_Mail::setDefaultTransport($tr);
        $trsmtp = new Zend_Mail_Transport_Smtp('smtprelaypool.ispgateway.de',$config);
        Zend_Mail::setDefaultTransport($trsmtp);

        if($user != null) {
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyHtml('Bestellung von Herrn '.$lastname. '')
                ->setFrom('smoothieme@itsh-solution.de', 'SmoothieMe Shop')
                ->addTo($email, $name)
                ->setSubject('Ihre Bestellung bei Smoothieme')
                ->send();

            list($orderprice, $smoothies) = $this->getOrders();

            $html = $this->buildHtmlString($lastname, $smoothies, $orderprice);

            $mail2 = new Zend_Mail('UTF-8');
            //$mail2->setBodyHtml('Bestellung von Herrn '.$lastname. '<br> Vielen Dank für Ihre Bestellung bei Smoothieme. <br> Dies ist eine Automatisch generierte Email, bitte anworten Sie nicht darauf. <br> Mit freundlichen Grüßen <br> Ihr Smoothieme Team')
            $mail2->setBodyHtml($html)
                ->setFrom('smoothieme@itsh-solution.de', 'Bestellung eingegangen')
                ->addTo('bastian.elfert@outlook.com', 'Bastian Elfert')
                ->setSubject('Ihre Bestellung bei Smoothieme')
                ->send();

            $db = Zend_Db_Table_Abstract::getDefaultAdapter();

            try {
                $db->beginTransaction();

                $orderMapper = new Application_Model_OrderMapper();
                $order = new Application_Model_Order();
                $order->setDeliveryMethod('bile');
                $order->setPaymentMethod('rechnung');
                $order->setAccountId($user->ID);

                foreach ($smoothies as $smoothie) {
                    $order->addSmoothie($smoothie['amount'], $smoothie['smoothie']);
                }

                $orderMapper->save($order);

                $cart = Zend_Registry::get("cart");
                $cart->clear();
                $db->commit();
            } catch(Exception $e) {
                $db->rollBack();
                throw new Exception($e);
            }
        }
        else {
            echo "Die Email konnte nicht versendet werden!";
        }

    }

    /**
     * @return array
     * @throws Zend_Exception
     */
    private function getOrders()
    {
        $orderprice = 0;
        $cart = Zend_Registry::get("cart");
        $cartcontent = $cart->getCartContents();
        $mapper = new Application_Model_SmoothieMapper();
        $smoothies = array();
        foreach ($cartcontent as $id => $amount) {
            $smoothie = new Application_Model_Smoothie();
            $mapper->find($id, $smoothie);
            $price = $smoothie->getPrice();
            $smoothies[] = array(
                "smoothie" => $smoothie,
                "amount" => $amount,
                "pricetotal" => $price * $amount
            );
            $orderprice += $price * $amount;
        }
        return array($orderprice, $smoothies);
    }

    /**
     * @param $name
     * @param $smoothies
     * @param $orderprice
     * @return string
     */
    private function buildHtmlString($name, $smoothies, $orderprice)
    {
        $html = '<p>Bestellung von Herrn ' . $name . '</p><table border="1">
                <thead>
                <tr>
                    <th>Smoothiename</th>
                    <th>Menge</th>
                    <th>Stückpreis</th>
                    <th>Preis</th>
                </tr>
                </thead>
                <tbody>';

        foreach ($smoothies as $row) {
            $html .= '<tr>'
                . '<td>' . $row["smoothie"]->getName() . '</td>'
                . '<td>' . $row["amount"] . ' Stk</td>'
                . '<td>' . number_format($row["smoothie"]->getPrice(), 2, ',', ' ') . ' €</td>'
                . '<td>' . number_format($row["pricetotal"], 2, ',', ' ') . ' €</td>'
                . '</tr>';
        }

        $html .= '<tr>'
            . '<td><strong>Gesamtpreis</strong></td>'
            . '<td></td>'
            . '<td></td>'
            . '<td><strong>' . number_format($orderprice, 2, ',', ' ') . ' €</strong></td>'
            . '</tr>'
            . '</tbody>'
            . '</table>';
        return $html;
    }


}



