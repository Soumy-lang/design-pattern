<?php

namespace PaymentLibraryProject\Notifications;

interface PaymentNotificationInterface
{
    public function notify(string $transactionId, string $status);
}
