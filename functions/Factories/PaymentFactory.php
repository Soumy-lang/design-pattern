<?php

namespace PaymentLibraryProject\Factories;

use PaymentLibraryProject\PaymentGateways\StripeGateway;

class PaymentFactory
{
    const UNSUPPORTED_PAYMENT_GATEWAY_MESSAGE = 'Interface de paiement non prise en charge';

    public static function createPaymentGateway($gatewayName, $apiKey, $apiSecret)
    {
        switch ($gatewayName) {
            case 'stripe':
                return new StripeGateway($apiKey, $apiSecret);
            default:
                throw new \Exception(self::UNSUPPORTED_PAYMENT_GATEWAY_MESSAGE);
        }
    }
}
