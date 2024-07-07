<?php

namespace PaymentLibraryProject\PaymentGateways;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use PaymentLibraryProject\Exceptions\PaymentException;
use PaymentLibraryProject\PaymentTransactionDTO\PaymentTransactionDTO;

class StripeGateway implements PaymentGatewayInterface {
    private $apiKey;
    private $apiSecret;
    private $client;

    public function __construct($apiKey, $apiSecret) {
        $this->setApiKey($apiKey);
        $this->setApiSecret($apiSecret);
        $this->client = new StripeClient($this->apiSecret);
        \Stripe\Stripe::setApiKey($this->apiSecret);
        echo "Stripe API key set to: " . $this->apiSecret . "\n";
    }

    public function configure(array $config) {
        if (isset($config['api_key'])) {
            $this->apiKey = $config['api_key'];
        } else {
            throw new PaymentException("API key is required to configure Stripe gateway");
        }
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

    public function initialize(array $config): void {
        if ($this->apiSecret) {
            \Stripe\Stripe::setApiKey($this->apiSecret);
            echo "Stripe API initialized with key: " . $this->apiSecret . "\n";
        } else {
            throw new PaymentException("API key is not configured");
        }
    }

    public function createTransaction(float $amount, string $currency, string $description): PaymentTransactionDTO {
        try {
            $charge = $this->client->charges->create([
                'amount' => $amount * 100, // Stripe utilise des centimes
                'currency' => $currency,
                'description' => $description,
                'source' => 'tok_visa', // Remplacer par une vraie source dans une vraie application
            ]);
            $transactionId = $charge->id;
            $status = $charge->status;
            return new PaymentTransactionDTO($amount, $currency, $description, $transactionId, $status);
        } catch (ApiErrorException $e) {
            throw new PaymentException('Erreur lors de la création de la transaction Stripe', 1001, $e);
        }
    }

    public function executeTransaction(string $transactionId): bool {
        try {
            echo "Tentative de récupération de la transaction Stripe...\n";
            $transaction = $this->client->charges->retrieve($transactionId);
            echo "Transaction Stripe récupérée : " . print_r($transaction, true) . "\n";
            $transaction->capture();
            return $transaction->status === 'succeeded';
        } catch (ApiErrorException $e) {
            echo "Erreur API Stripe : " . $e->getMessage() . "\n";
            throw new PaymentException('Erreur lors de l\'exécution de la transaction Stripe', 1002, $e);
        }
    }

    public function cancelTransaction(string $transactionId): bool {
        try {
            $transaction = $this->client->charges->retrieve($transactionId);

            if ($transaction->status !== 'succeeded') {
                $this->client->refunds->create([
                    'charge' => $transactionId,
                ]);
                return true;
            }
            return false;
        } catch (ApiErrorException $e) {
            echo "Erreur API Stripe : " . $e->getMessage() . "\n";
            throw new PaymentException('Erreur lors de l\'annulation de la transaction Stripe', 1003, $e);
        }
    }
}
