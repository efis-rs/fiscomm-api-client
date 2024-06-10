<?php

namespace Fiscomm\Api;

class System extends BaseApi
{
    protected function getPath(): string
    {
        return '/system/%s';
    }

    public function show(): array
    {
        return $this->get($this->buildPath(''));
    }

    /**
     * Undocumented function
     *
     * @return array{
     *  organizationName: string,
     *  serverTimeZone: string,
     *  street: string,
     *  city: string,
     *  country: string,
     *  endpoints: array{
     *   taxpayerAdminPortal: string,
     *   taxCoreApi: string,
     *   vsdc: string,
     *   root: string,
     *  },
     *  environmentName: string,
     *  logo: string,
     *  ntpServer: string,
     *  supportedLanguages: array<string>
     * }
     */
    public function taxEnvironment(): array
    {
        return $this->get($this->buildPath('tax-enviroment'));
    }

    public function taxEnviroment(): array
    {
        return $this->taxEnvironment();
    }

    public function availability(): bool
    {
        return 'true' === $this->get($this->buildPath('availability'));
    }

    public function taxRates(): System\TaxRates
    {
        return new System\TaxRates($this->getClient());
    }

    /**
     * Get all available registered payment methods supported by local authority
     *
     * @return array<int, string>
     */
    public function paymentMethods(): array
    {
        return $this->get($this->buildPath('payment-methods'));
    }
}
