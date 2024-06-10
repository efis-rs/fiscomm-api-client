<?php //phpcs:disable Generic.Files.LineLength.TooLong

namespace Fiscomm\Api;

use Fiscomm\Api\BaseApi;

/**
 * @phpstan-type FiscalReceipt array{
 *   id: string,
 *   invoice_type_id: int,
 *   transaction_type_id: int,
 *   journal: string,
 *   cashier: string|null,
 *   invoice_number: string,
 *   signed_by: string,
 *   invoice_counter: string,
 *   district: string,
 *   mrc: string,
 *   tin: string,
 *   buyer_id: string|null,
 *   uid: string,
 *   invoice_number_pos: string|null,
 *   referent_document_number: string|null,
 *   tax_group_revision: numeric-string,
 *   invoice_counter_extension: string,
 *   address: string,
 *   buyer_cost_center_id: string|null,
 *   requested_by: string,
 *   business_name: string,
 *   location_name: string,
 *   referent_document_dt: string,
 *   sdc_date_time: string,
 *   encrypted_internal_data: string,
 *   signature: string,
 *   messages: string,
 *   qr_code_file_url: string,
 *   invoice_pdf_url: string,
 *   verification_url: string,
 *   total_amount: numeric-string,
 *   transaction_type_counter: numeric-string,
 *   total_counter: numeric-string,
 *   created_at: string,
 *   updated_at: string|null,
 *   client_id: string|null,
 * }
 */
class Invoice extends BaseApi
{
    protected function getPath(): string
    {
        return '/invoices/%s';
    }

    /**
     * Get all invoices
     *
     * @param  'NORMAL'|'PROFORMA'|'COPY'|'TRAINING'|'ADVANCE' $invoiceType     Invoice type.
     * @param  'SALE'|'REFUND'                                 $transactionType Transaction type.
     * @param  int|null                                        $limit           Limit the number of invoices.
     * @return array
     */
    public function all(string $invoiceType = 'NORMAL', string $transactionType = 'SALE'): array
    {
        return $this->get($this->buildPath(''), [
            'invoiceTypeCanonical'     => $invoiceType,
            'transactionTypeCanonical' => $transactionType,
        ]);
    }

    /**
     * Find invoices
     *
     * @param  array{
     *  invoice_type?: 'NORMAL'|'PROFORMA'|'COPY'|'TRAINING'|'ADVANCE',
     *  transaction_type?: 'SALE'|'REFUND',
     *  item_name?: string,
     *  date_after?: string,
     *  date_before?: string,
     *  total_amount_more_then?: float,
     *  total_amount_less_then?: float,
     *  page?: int,
     *  per_page?: int,
     *  sort: 'sdc_date_time' | 'total_amount' | 'invoice_number_pos' | 'id' | '-sdc_date_time' | '-total_amount' | '-invoice_number_pos' | '-id'
     * } $params
     * @return array{
     *  current_page: int,
     *  data: array<int, FiscalReceipt|array{
     *    id: string,
     *    invoice_type_id: int,
     *    transaction_type_id: int,
     *    journal: string,
     *    cashier: string|null,
     *    invoice_number: string,
     *    signed_by: string,
     *    invoice_counter: string,
     *    district: string,
     *    mrc: string,
     *    tin: string,
     *    buyer_id: string|null,
     *    uid: string,
     *    invoice_number_pos: string|null,
     *    referent_document_number: string|null,
     *    tax_group_revision: numeric-string,
     *    invoice_counter_extension: string,
     *    address: string,
     *    buyer_cost_center_id: string|null,
     *    requested_by: string,
     *    business_name: string,
     *    business_name: string,
     *    location_name: string,
     *    referent_document_dt: string,
     *    sdc_date_time: string,
     *    encrypted_internal_data: string,
     *    signature: string,
     *    messages: string,
     *    qr_code_file_url: string,
     *    invoice_pdf_url: string,
     *    verification_url: string,
     *    total_amount: numeric-string,
     *    transaction_type_counter: numeric-string,
     *    total_counter: numeric-string,
     *    created_at: string,
     *    updated_at: string|null,
     *    client_id: string|null
     *  }>,
     *  first_page_url: string,
     *  from: int,
     *  next_page_url: string|null,
     *  path: string,
     *  per_page: int,
     *  prev_page_url: string|null,
     *  to: int,
     * }
     */
    public function find(array $params = []): array
    {
        $params['invoice_type'] ??= 'NORMAL';
        $params['transaction_type'] ??= 'SALE';

        return $this->get($this->buildPath('archive'), $params);
    }

    /**
     * Get invoice by number
     *
     * @param  string $invoiceNo Invoice number.
     * @return FiscalReceipt
     */
    public function show(string $invoiceNo): array
    {
        return $this->get($this->buildPath($invoiceNo));
    }

    /**
     * Create new fiscal invoice.
     *
     * @return Invoice\Create
     */
    public function create(): Invoice\Create
    {
        return new Invoice\Create($this->getClient());
    }
}
