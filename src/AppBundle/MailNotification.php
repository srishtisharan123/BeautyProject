<?php

namespace AppBundle;

class MailNotification
{
    public function sendMail($username) {
        $params = array('username' => $username);
        $mail = new \Pimcore\Mail();
        $document = \Pimcore\Model\Document::getById(8);
        $mail->setDocument($document);
        $mail->setParams($params);
        // $emailDocument = $mail->getDocument();
        
        // $body = $document->getElement('body');
        // $bodyText = $body->text;
        $mail->send();
    }

    public function sendImportMail() {
        $mail = new \Pimcore\Mail();
        $document = \Pimcore\Model\Document::getById(9);
        $mail->setDocument($document);
        // $emailDocument = $mail->getDocument();
        
        // $body = $document->getElement('body');
        // $bodyText = $body->text;
        $mail->send();
    }
}
