<?php
namespace PaymentLibraryProject\PaymentGateways;

use PaymentLibraryProject\PaymentGateways;
use PaymentLibraryProject\PaymentTransactionDTO\PaymentTransactionDTO;

interface PaymentGatewayInterface extends ConfigurableInterface {
    public function setApiKey($apiKey);
    public function getApiKey();
    public function setApiSecret($apiSecret);
    public function getApiSecret();
    public function initialize(array $config): void;
    public function createTransaction(float $amount, string $currency, string $description): PaymentTransactionDTO;
    public function executeTransaction(string $transactionId);
    public function cancelTransaction(string $transactionId);
}
