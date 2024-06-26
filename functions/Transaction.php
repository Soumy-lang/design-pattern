<?php

class Transaction
{
    private $id;
    private $amount;
    private $currency;
    private $description;
    private $status;

    public function __construct($id, $amount, $currency, $description) {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->status = TransactionStatus::PENDING;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}

class TransactionStatus {
    const PENDING = 'Opperation en attente';
    const SUCCESS = 'Opperation reussie';
    const FAILED = 'Opperation echouée';
    const CANCELLED = 'Opperation annulée';
}