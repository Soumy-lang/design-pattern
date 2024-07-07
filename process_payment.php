<?php

require 'vendor/autoload.php';

use PaymentLibraryProject\PaymentLibrary;
use PaymentLibraryProject\PaymentGateways\StripeGateway;
use PaymentLibraryProject\Exceptions\PaymentException;
use PaymentLibraryProject\Notifications\EmailNotification;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'stripe' => [
        'api_key' => $_ENV['STRIPE_API_KEY'],
        'api_secret' => $_ENV['STRIPE_API_SECRET']
    ]
];

$paymentLibrary = new PaymentLibrary($config);

// Ajouter une notification par e-mail
$emailNotification = new EmailNotification('neffatinadime@gmail.com');
$paymentLibrary->addNotification($emailNotification);

// Recevoir le token de paiement depuis le frontend
$input = json_decode(file_get_contents('php://input'), true);
$source = $input['token'];

$amount = 100.00; // Exemple de montant
$currency = 'usd';
$description = 'Test Payment';
$gatewayName = 'stripe';

try {
    // Créer une transaction
    echo "Tentative de création de transaction...\n";
    $transaction = $paymentLibrary->createTransaction($amount, $currency, $description, $source);
    echo "Transaction créée avec succès.\n";

    // Exécuter la transaction
    echo "Tentative d'exécution de la transaction...\n";
    $success = $paymentLibrary->executeTransaction($transaction, $gatewayName);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Paiement réussi']);
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'Échec du paiement']);
    }
} catch (PaymentException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Supprimer la passerelle Stripe après toutes les opérations
try {
    echo "Tentative de suppression de la passerelle Stripe...\n";
    $paymentLibrary->removePaymentGateway('stripe');
    echo 'Passerelle Stripe supprimée';
} catch (PaymentException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
