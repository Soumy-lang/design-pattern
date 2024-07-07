<?php

namespace PaymentLibraryProject\PaymentTransaction;

use PaymentLibraryProject\PaymentGateways\PaymentGatewayInterface;
use PaymentLibraryProject\Exceptions\PaymentException;
use PaymentLibraryProject\PaymentLibrary;

class PaymentTransaction {
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';

    private $gateway;
    private $amount;
    private $currency;
    private $description;
    private $status;
    private $transactionId;
    private $library;

    private $statusTransitions = [
        self::STATUS_PENDING => [self::STATUS_SUCCESS, self::STATUS_FAILED, self::STATUS_CANCELED],
        self::STATUS_SUCCESS => [],
        self::STATUS_FAILED => [],
        self::STATUS_CANCELED => [],
    ];

    public function __construct(PaymentLibrary $library, PaymentGatewayInterface $gateway, float $amount, string $currency, string $description) {
        $this->library = $library;
        $this->gateway = $gateway;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->status = self::STATUS_PENDING;
    }

    public function createTransaction() {
        $transaction = $this->gateway->createTransaction($this->amount, $this->currency, $this->description);
        $this->transactionId = $transaction->getId();
    }

    public function execute() {
        try {
            $result = $this->gateway->executeTransaction($this->transactionId);
            $this->status = $result ? self::STATUS_SUCCESS : self::STATUS_FAILED;
            $this->notify();
            return $result;
        } catch (PaymentException $e) {
            $this->status = self::STATUS_FAILED;
            $this->notify();
            return false;
        }
    }

    public function cancel() {
        if ($this->status !== self::STATUS_PENDING) {
            throw new PaymentException('Cannot cancel transaction: transaction is not pending.');
        }

        try {
            $result = $this->gateway->cancelTransaction($this->transactionId);
            $this->status = $result ? self::STATUS_CANCELED : self::STATUS_FAILED;
            $this->notify();
        } catch (PaymentException $e) {
            $this->status = self::STATUS_FAILED;
            $this->notify();
        }
    }

    private function notify() {
        $this->library->notify($this->transactionId, $this->status);
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        if (!in_array($status, $this->statusTransitions[$this->status])) {
            throw new PaymentException("Invalid transition from {$this->status} to {$status}");
        }

        $this->status = $status;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getId() {
        return $this->transactionId;
    }
}
