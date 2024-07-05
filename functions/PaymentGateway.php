<?php


  abstract class PaymentGateway {
    protected $apiKey;
    protected $apiSecret;

    public function __construct($apiKey, $apiSecret) {
      $this->setApiKey($apiKey);
      $this->setApiSecret($apiSecret);
    }

    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getApiKey() {
        return $this->apiKey;
    }

    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;
    }

    public function getApiSecret() {
        return $this->apiSecret;
    }

    abstract public function createTransaction(float $amount, string $currency, string $description);
    abstract public function executeTransaction(string $transactionId);
    abstract public function cancelTransaction(string $transactionId);
}
