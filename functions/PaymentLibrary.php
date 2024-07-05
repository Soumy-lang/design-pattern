<?php

class PaymentLibrary
{
    private $gateways = [];
    private $apiKeys = [
        'stripe' => ['api_key' => 'pk_test_51PX3DJKaeOSf3z3yTkUVtRx7fJ0Z8cAKMbRTZ5BJnSyWmynZtsAU34sZA6JrdNPJhIr50eG7Mup1T9keTGjSEThb00kFrpLLuW', 'api_secret' => 'sk_test_51PX3DJKaeOSf3z3yxANNnPOQOoKreoE6f5zuuqaefXk6Lm4RTOXZlMsDQ26LAhjDBkVTrnqhmcz3ZUHa1P9aWTea00p9rDqvja'],
    ];

    public function addPaymentGateway(PaymentGatewayInterface $gateway)
    {
        $this->gateways[] = $gateway;
    }

    public function removePaymentGateway(PaymentGatewayInterface $gateway)
    {
        $index = array_search($gateway, $this->gateways);
        if ($index !== false) {
            unset($this->gateways[$index]);
        }
    }

    public function createTransaction(float $amount, string $currency, string $description, $gatewayName)
    {
        $apiKey = $this->getApiKey($gatewayName);
        $apiSecret = $this->getApiSecret($gatewayName);
        $gateway = PaymentFactory::createPaymentGateway($gatewayName, $apiKey, $apiSecret);
        return new PaymentTransaction($gateway, $amount, $currency, $description);
    }

    private function getApiKey($gatewayName)
    {
        return $this->apiKeys[$gatewayName]['api_key'];
    }

    private function getApiSecret($gatewayName)
    {
        return $this->apiKeys[$gatewayName]['api_secret'];
    }

    public function getPaymentGateway($gatewayName)
    {
        $apiKey = $this->getApiKey($gatewayName);
        $apiSecret = $this->getApiSecret($gatewayName);
        return PaymentFactory::createPaymentGateway($gatewayName, $apiKey, $apiSecret);
    }
}
