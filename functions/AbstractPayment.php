<?php

abstract class AbstractPayment
{
    protected $config;

    public function initialize(array $config) {
        $this->config = $config;
    }

    // Méthodes communes peuvent être implémentées ici

    abstract public function createTransaction(float $amount, string $currency, string $description);
    abstract public function executeTransaction($transactionId);
    abstract public function cancelTransaction($transactionId);
    abstract public function getTransactionStatus($transactionId);
}