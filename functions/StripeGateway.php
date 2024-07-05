<?php

require_once __DIR__ . '/PaymentGateway.php';


class StripeGateway extends PaymentGateway
{
    private $client;

    // Code d'erreur pour une création de transaction échouée
    const CREATE_TRANSACTION_ERROR_CODE = 1001;
    const CREATE_TRANSACTION_ERROR_MESSAGE = 'Erreur lors de la création de la transaction Stripe';

    // Code d'erreur pour une exécution de transaction échouée
    const EXECUTE_TRANSACTION_ERROR_CODE = 1002;
    const EXECUTE_TRANSACTION_ERROR_MESSAGE = 'Erreur lors de l\'exécution de la transaction Stripe';

    // Code d'erreur pour une annulation de transaction échouée
    const CANCEL_TRANSACTION_ERROR_CODE = 1003;
    const CANCEL_TRANSACTION_ERROR_MESSAGE = 'Erreur lors de l\'annulation de la transaction Stripe';

    public function __construct($apiKey)
    {
        $this->client = new \Stripe\Client(['api_key' => $apiKey]);
    }

    /**
     * Crée une transaction Stripe avec les informations données
     *
     * @param float $amount Montant de la transaction
     * @param string $currency Devise de la transaction
     * @param string $description Description de la transaction
     *
     * @return array Tableau associatif contenant l'identifiant et le statut de la transaction
     *
     * @throws PaymentException En cas d'échec de la création de la transaction
     */
    public function createTransaction(float $amount, string $currency, string $description): array
    {
        try {
            $transaction = $this->client->charges->create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'description' => $description,
            ]);

            return [
                'id' => $transaction->id,
                'status' => $transaction->status,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new PaymentException(self::CREATE_TRANSACTION_ERROR_MESSAGE, self::CREATE_TRANSACTION_ERROR_CODE, $e);
        }
    }

    /**
     * Exécute une transaction Stripe existante
     *
     * @param string $transactionId Identifiant de la transaction à exécuter
     *
     * @return array Tableau associatif contenant l'identifiant et le statut de la transaction
     *
     * @throws PaymentException En cas d'échec de l'exécution de la transaction
     */
    public function executeTransaction(string $transactionId): array
    {
        try {
            $transaction = $this->client->charges->retrieve($transactionId);

            return [
                'id' => $transaction->id,
                'status' => $transaction->status,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new PaymentException(self::EXECUTE_TRANSACTION_ERROR_MESSAGE, self::EXECUTE_TRANSACTION_ERROR_CODE, $e);
        }
    }

    /**
     * Annule une transaction Stripe existante
     *
     * @param string $transactionId Identifiant de la transaction à annuler
     *
     * @return void
     *
     * @throws PaymentException En cas d'échec de l'annulation de la transaction
     */
    public function cancelTransaction(string $transactionId): void
    {
        try {
            $transaction = $this->client->charges->retrieve($transactionId);

            if ($transaction->status !== 'succeeded') {
                $transaction->refund();
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new PaymentException(self::CANCEL_TRANSACTION_ERROR_MESSAGE, self::CANCEL_TRANSACTION_ERROR_CODE, $e);
        }
    }
}
