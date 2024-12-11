<?php

namespace Fiscomm\Api\Invoice;

use Fiscomm\Api\BaseApi;

class Create extends BaseApi
{
    protected function getPath(): string
    {
        return '/invoices/%s/%s';
    }

    protected function remapCreateArgs(array $args): array
    {
        $map = [
            'buyerCcId' => 'buyerCostCenterId',
            'invoiceNo' => 'invoiceNumber',
            'refDocDt' => 'referentDocumentDT',
            'refDocNo' => 'referentDocumentNumber',
        ];

        $args = \array_combine(
            \array_map(static fn($k) => $map[$k] ?? $k, \array_keys($args)),
            \array_values($args),
        );

        return \array_filter($args, static fn($v) => null !== $v);
    }

    /**
     * Create new fiscal sale.
     *
     * @param  string        $invType   Invoice type.
     * @param  array<array{
     *   name: string,
     *   unitPrice: float,
     *   labels:array<string>,
     *   quantity: int,
     *   totalAmount: float,
     *   gtin?: string
     * }>                    $items     Invoiced items.
     * @param  array<array{
     *   amount: float,
     *   paymentType: string
     * }>                    $payment   Payment details.
     * @param  string|null   $invoiceNo Invoice number.
     * @param  string|null   $cashier   Cashier name.
     * @param  string|null   $buyerId   Buyer ID.
     * @param  string|null   $buyerCcId Buyer cost center ID.
     * @param  string|null   $refDocNo  Reference document number.
     * @param  string|null   $refDocDt  Reference document date.
     * @param  array|null    $options   Additional options.
     *
     * @return array{
     *   invoicePdfUrl: string,
     *   requestedBy: string,
     *   sdcDateTime: string,
     *   invoiceCounter: string,
     *   invoiceCounterExtension: string,
     *   invoiceNumber: string,
     *   taxItems: array<array{
     *     categoryType: int,
     *     label: string,
     *     amount: float,
     *     rate: int|float,
     *     categoryName: string,
     *   }>,
     *   verificationUrl: string,
     *   verificationQRCode: string,
     *   journal: string,
     *   messages: string,
     *   signedBy: string,
     *   encryptedInternalData: string,
     *   signature: string,
     *   totalCounter: int,
     *   transactionTypeCounter: int,
     *   totalAmount: float|int,
     *   taxGroupRevision: int,
     *   businessName: string,
     *   tin: string,
     *   locationName: string,
     *   address: string,
     *   district: string,
     *   mrc: string,
     *   qrCodeFileURL: string,
     *   invoiceNumberPos: string,
     * }
     */
    public function sale(
        string $invType,
        array $items,
        array $payment,
        ?string $invoiceNo = null,
        ?string $cashier = 'Продавац',
        ?string $buyerId = null,
        ?string $buyerCcId = null,
        ?string $refDocNo = null,
        ?string $refDocDt = null,
        ?array $options = null,
    ): array {
        $args = $this->remapCreateArgs(
            \compact(
                'items',
                'payment',
                'invoiceNo',
                'cashier',
                'buyerId',
                'buyerCcId',
                'refDocNo',
                'refDocDt',
                'options',
            ),
        );

        return $this->post($this->buildPath($invType, 'sale'), $args);
    }

    /**
     * Create new fiscal refund.
     *
     * @param  string        $invType   Invoice type.
     * @param  string        $refDocNo  Reference document number.
     * @param  string        $refDocDt  Reference document date.
     * @param  array<array{
     *   name: string,
     *   unitPrice: float,
     *   labels:array<string>,
     *   quantity: int,
     *   totalAmount: float,
     *   gtin?: string
     * }>                    $items     Invoiced items.
     * @param  array<array{
     *   amount: float,
     *   paymentType: string
     * }>                    $payment   Payment details.
     * @param  string|null   $invoiceNo Invoice number.
     * @param  string|null   $cashier   Cashier name.
     * @param  string|null   $buyerId   Buyer ID.
     * @param  string|null   $buyerCcId Buyer cost center ID.
     * @param  array|null    $options   Additional options.
     *
     * @return array{
     *   invoicePdfUrl: string,
     *   requestedBy: string,
     *   sdcDateTime: string,
     *   invoiceCounter: string,
     *   invoiceCounterExtension: string,
     *   invoiceNumber: string,
     *   taxItems: array<array{
     *     categoryType: int,
     *     label: string,
     *     amount: float,
     *     rate: int|float,
     *     categoryName: string,
     *   }>,
     *   verificationUrl: string,
     *   verificationQRCode: string,
     *   journal: string,
     *   messages: string,
     *   signedBy: string,
     *   encryptedInternalData: string,
     *   signature: string,
     *   totalCounter: int,
     *   transactionTypeCounter: int,
     *   totalAmount: float|int,
     *   taxGroupRevision: int,
     *   businessName: string,
     *   tin: string,
     *   locationName: string,
     *   address: string,
     *   district: string,
     *   mrc: string,
     *   qrCodeFileURL: string,
     *   invoiceNumberPos: string,
     * }
     */
    public function refund(
        string $invType,
        string $refDocNo,
        string $refDocDt,
        array $items,
        array $payment,
        ?string $invoiceNo = null,
        ?string $cashier = 'Продавац',
        ?string $buyerId = null,
        ?string $buyerCcId = null,
        ?array $options = null,
    ): array {
        $args = $this->remapCreateArgs(
            \compact(
                'items',
                'payment',
                'refDocNo',
                'refDocDt',
                'invoiceNo',
                'cashier',
                'buyerId',
                'buyerCcId',
                'options',
            ),
        );

        return $this->post($this->buildPath($invType, 'refund'), $args);
    }

    protected function getJsonEncodeArgs(): int
    {
        return 0;
    }
}
