<?php

interface Observer {
    public function update($transaction);
}

class BillingService implements Observer {
    public function update($transaction) {
        // Code pour mettre à jour le service de facturation
    }
}

class InventoryService implements Observer {
    public function update($transaction) {
        // Code pour mettre à jour le service de gestion des stocks
    }
}

class PaymentNotifier {
    private $observers = [];

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notify($transaction) {
        foreach ($this->observers as $observer) {
            $observer->update($transaction);
        }
    }
}
