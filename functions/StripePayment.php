<?php

class StripePayment extends AbstractPayment
{
    public function createTransaction(float $amount, string $currency, string $description) {
        // Code pour créer une transaction Stripe
    }

    public function executeTransaction($transactionId) {
        // Code pour exécuter une transaction Stripe
    }

    public function cancelTransaction($transactionId) {
        // Code pour annuler une transaction Stripe
    }

    public function getTransactionStatus($transactionId) {
        // Code pour obtenir le statut d'une transaction Stripe
    }
}