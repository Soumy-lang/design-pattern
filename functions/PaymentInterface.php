<?php

interface PaymentInterface
{
    public function initialize(array $config);
    public function createTransaction(float $amount, string $currency, string $description);
    public function executeTransaction($transactionId);
    public function cancelTransaction($transactionId);
    public function getTransactionStatus($transactionId);
}