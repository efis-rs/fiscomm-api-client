<?php

namespace Fiscomm\Api\System;

use Fiscomm\Api\BaseApi;

class TaxRates extends BaseApi
{
    protected function getPath(): string
    {
        return '/system/tax-rates';
    }

    /**
     * Get all tax rates
     *
     * @return array
     */
    public function raw(): array
    {
        return $this->get($this->buildPath(''));
    }

    /**
     * Get the current tax rates
     *
     * @return array{
     *   validFrom: string,
     *   groupId: int,
     *   taxCategories: array<array{
     *     name: string,
     *     categoryType: int,
     *     orderId: int,
     *     taxRates: array<array{
     *       rate: int|float,
     *       label: string,
     *     }>
     *   }>
     * }
     */
    public function current(): array
    {
        return $this->raw()['currentTaxRates'];
    }

    /**
     * Get all tax rates.
     *
     * @return array{
     *   validFrom: string,
     *   groupId: int,
     *   taxCategories: array<array{
     *     name: string,
     *     categoryType: int,
     *     orderId: int,
     *     taxRates: array<array{
     *       rate: int|float,
     *       label: string,
     *     }>
     *   }>
     * }
     */
    public function all(): array
    {
        return $this->raw()['allTaxRates'];
    }
}
