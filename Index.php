<?php

require 'vendor/autoload.php'; // Assurez-vous que l'autoload de Composer est inclus

use PaymentLibraryProject\PaymentLibrary;
use PaymentLibraryProject\PaymentGateways\StripeGateway;
use PaymentLibraryProject\Exceptions\PaymentException;
use PaymentLibraryProject\Notifications\EmailNotification;
use Dotenv\Dotenv; // Importez correctement le namespace

// Charger les variables d'environnement depuis le fichier .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configuration de la passerelle de paiement Stripe
$config = [
    'stripe' => [
        'api_key' => $_ENV['STRIPE_API_KEY'], // Utilisez la clé secrète ici
        'api_secret' => $_ENV['STRIPE_API_SECRET'] // Utilisez la clé secrète ici aussi
    ]
];

$paymentLibrary = new PaymentLibrary($config);

// Ajouter une notification par e-mail
$emailNotification = new EmailNotification('neffatinadime@gmail.com');
$paymentLibrary->addNotification($emailNotification);

// Traiter un paiement
$amount = 100.00;
$currency = 'usd';
$description = 'Test Payment';
$gatewayName = 'stripe';

try {
    // Créer une transaction
    echo "Tentative de création de transaction...\n";
    $transaction = $paymentLibrary->createTransaction($amount, $currency, $description, $gatewayName);
    echo "Transaction créée avec succès.\n";

    // Exécuter la transaction
    echo "Tentative d'exécution de la transaction...\n";
    $success = $paymentLibrary->executeTransaction($transaction, $gatewayName);

    if ($success) {
        echo 'Paiement réussi';
    } else {
        echo 'Échec du paiement';
    }
} catch (PaymentException $e) {
    echo 'Erreur : ' . $e->getMessage();
}

// Supprimer la passerelle Stripe après toutes les opérations
try {
    echo "Tentative de suppression de la passerelle Stripe...\n";
    $paymentLibrary->removePaymentGateway('stripe');
    echo 'Passerelle Stripe supprimée';
} catch (PaymentException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
