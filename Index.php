<?php

require_once( __DIR__ . '/functions/PaymentLibrary.php');
require_once (__DIR__ . '/functions/PaymentTransaction.php');
require_once (__DIR__ . '/functions/StripeGateway.php');
require_once (__DIR__ . '/functions/PaymentGateway.php');


// Configuration de la clé API de Stripe
$stripeApiKey = 'sk_test_51PX3DJKaeOSf3z3yxANNnPOQOoKreoE6f5zuuqaefXk6Lm4RTOXZlMsDQ26LAhjDBkVTrnqhmcz3ZUHa1P9aWTea00p9rDqvja';

// Initialisation de la bibliothèque de paiement
$paymentLibrary = new PaymentLibrary();

// Ajout de l'interface de paiement Stripe
$stripeGateway = new StripeGateway($stripeApiKey);
$paymentLibrary->addPaymentGateway($stripeGateway);

// Création d'une transaction de paiement
$transactionAmount = 50.00;
$transactionCurrency = 'EUR';
$transactionDescription = 'Paiement test';
$transaction = new PaymentTransaction($transactionAmount, $transactionCurrency, $transactionDescription);

// Exécution de la transaction de paiement avec Stripe
$paymentLibrary->executeTransaction($transaction, 'stripe');

// Affichage du statut de la transaction
echo 'Statut de la transaction : ' . $transaction->getStatus();
