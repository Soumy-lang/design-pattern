<?php

namespace PaymentLibraryProject\Notifications;

class EmailNotification implements PaymentNotificationInterface {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function notify(string $transactionId, string $status) {
        mail($this->email, "Statut de la transaction $transactionId", "Le statut de votre transaction est maintenant : $status");
    }
}
