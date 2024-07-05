<?php

class PaymentTransaction
{
    private $gateway;
    private $amount;
    private $currency;
    private $description;
    private $status;

    // Ajout d'une constante pour le statut en attente
    const STATUS_PENDING = 'pending';

    public function __construct(PaymentGatewayInterface $gateway, float $amount, string $currency, string $description)
    {
        $this->gateway = $gateway;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->status = self::STATUS_PENDING;
    }

    public function execute()
    {
        try {
            $this->gateway->pay($this->amount, $this->currency, $this->description);
            $this->status = 'success';
        } catch (Exception $e) {
            $this->status = 'failed';
        }
    }

    public function cancel()
    {
        $this->status = 'canceled';
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Ajout des méthodes pour récupérer le montant, la devise et la description
    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
