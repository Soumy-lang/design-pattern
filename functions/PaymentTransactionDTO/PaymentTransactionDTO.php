<?php

namespace PaymentLibraryProject\PaymentTransactionDTO;

class PaymentTransactionDTO {
    private $amount;
    private $currency;
    private $description;
    private $id;
    private $status;

    public function __construct(float $amount, string $currency, string $description, string $id, string $status) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->id = $id;
        $this->status = $status;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getCurrency(): string {
        return $this->currency;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }
}
