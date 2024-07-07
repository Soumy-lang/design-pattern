<?php

namespace PaymentLibraryProject;

use PaymentLibraryProject\PaymentGateways\PaymentGatewayInterface;
use PaymentLibraryProject\Exceptions\PaymentException;
use PaymentLibraryProject\PaymentTransactionDTO\PaymentTransactionDTO;
use PaymentLibraryProject\Notifications\PaymentNotificationInterface;
use PaymentLibraryProject\Factories\PaymentFactory;

class PaymentLibrary {
    private $gateways = [];
    private $notifications = [];

    public function __construct(array $gatewaysConfig = []) {
        foreach ($gatewaysConfig as $gatewayName => $config) {
            $gateway = PaymentFactory::createPaymentGateway($gatewayName, $config['api_key'], $config['api_secret']);
            $this->addPaymentGateway($gateway, $gatewayName);
        }
    }

    public function addPaymentGateway(PaymentGatewayInterface $gateway, string $name) {
        $this->gateways[$name] = $gateway;
    }

    public function removePaymentGateway(string $gatewayName): void {
        if (isset($this->gateways[$gatewayName])) {
            unset($this->gateways[$gatewayName]);
        } else {
            throw new PaymentException("Payment gateway not found: $gatewayName");
        }
    }

    public function createTransaction(float $amount, string $currency, string $description, string $gatewayName): PaymentTransactionDTO {
        if (!isset($this->gateways[$gatewayName])) {
            throw new \InvalidArgumentException("Gateway $gatewayName not found");
        }
        $gateway = $this->gateways[$gatewayName];
        return $gateway->createTransaction($amount, $currency, $description);
    }

    public function executeTransaction(PaymentTransactionDTO $transaction, string $gatewayName): bool {
        if (!isset($this->gateways[$gatewayName])) {
            throw new PaymentException("Gateway $gatewayName not found");
        }
        $gateway = $this->gateways[$gatewayName];
        $result = $gateway->executeTransaction($transaction->getId());
        $transaction->setStatus($result ? 'succeeded' : 'failed');
        $this->notify($transaction->getId(), $transaction->getStatus());
        return $result;
    }

    public function cancelTransaction(PaymentTransactionDTO $transaction, string $gatewayName): bool {
        if (!isset($this->gateways[$gatewayName])) {
            throw new PaymentException("Gateway $gatewayName not found");
        }
        $gateway = $this->gateways[$gatewayName];
        $result = $gateway->cancelTransaction($transaction->getId());
        $transaction->setStatus($result ? 'canceled' : 'failed');
        $this->notify($transaction->getId(), $transaction->getStatus());
        return $result;
    }

    public function addNotification(PaymentNotificationInterface $notification) {
        $this->notifications[] = $notification;
    }

    public function notify(string $transactionId, string $status) {
        foreach ($this->notifications as $notification) {
            $notification->notify($transactionId, $status);
        }
    }
}
