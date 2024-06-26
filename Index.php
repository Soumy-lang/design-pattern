<?php

require 'functions/PaymentFactory.php';
require 'functions/StripePayment.php';
require 'functions/Transaction.php';
require 'Observer.php';

// Initialisation de l'interface de paiement
$stripePayment = PaymentFactory::createPayment('stripe');
$stripePayment->initialize(['api_key' => 'your_stripe_api_key']);

// Création d'une transaction
$transaction = $stripePayment->createTransaction(100.0, 'USD', 'Achat de produit XYZ');

// Exécution de la transaction
$result = $stripePayment->executeTransaction($transaction->id);

// Gestion des observateurs
$notifier = new PaymentNotifier();
$notifier->addObserver(new BillingService());
$notifier->addObserver(new InventoryService());

$notifier->notify($transaction);

