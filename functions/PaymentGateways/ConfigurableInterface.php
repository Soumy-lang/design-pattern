<?php
namespace PaymentLibraryProject\PaymentGateways;
interface ConfigurableInterface {
    public function configure(array $config);
}
