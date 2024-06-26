<?php

class PaymentFactory
{
    public static function createPayment($type) {
        switch($type) {
            case 'stripe':
                return new StripePayment();
            // Ajouter d'autres cas pour d'autres interfaces de paiement
            default:
                throw new Exception("Unknown payment type");
        }
    }
}