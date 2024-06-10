<?php

namespace Fiscomm;

use Fiscomm\Api\BaseApi;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin as PsrPlugin;
use Http\Discovery\Psr17FactoryDiscovery as PsrFinder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Fiscomm Client
 *
 * @method Api\System  system()  Systems API endpoints.
 * @method Api\Invoice invoice() Invoice API endpoints.
 */
class Client
{
    /**
     * Base URL for the API
     *
     * @var string
     */
    private const BASE_URL = 'https://us-central1-fiscal-38558.cloudfunctions.net';

    /**
     * Default headers
     *
     * @var array
     */
    private const HEADERS = array(
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    );

    private string $prefix;

    private Builder $builder;

    /**
     *
     *
     * @var Plugin\History
     */
    private PsrPlugin\Journal $history;

    public function __construct(
        Builder $builder = null,
        string $apiKey = null,
        string $baseUrl = null,
        string $prefix = null,
    ) {
        $this->history = new Plugin\History();
        $this->builder = $builder ?? new Builder();
        $this->prefix = $prefix ?? 'api';

        $this->builder->addPlugin(new PsrPlugin\HistoryPlugin($this->history));
        $this->builder->addPlugin(new PsrPlugin\RedirectPlugin());
        $this->builder->addPlugin(new PsrPlugin\AddHostPlugin($this->getBaseUrl($baseUrl)));
        $this->builder->addPlugin(new PsrPlugin\HeaderDefaultsPlugin(Client::HEADERS));
        $this->builder->addPlugin(new Plugin\Authentication($apiKey ?? ''));
    }

    public static function withHttpClient(ClientInterface $client): static
    {
        return new static((new Builder($client)));
    }

    protected function getBaseUrl(?string $baseUrl): \Psr\Http\Message\UriInterface
    {
        return PsrFinder::findUriFactory()->createUri($baseUrl ?? static::BASE_URL);
    }

    /**
     * Call an API endpoint
     *
     * @param  string       $name The name of the API endpoint
     * @return BaseApi|null       The API endpoint
     */
    protected function api(string $name): ?BaseApi
    {
        return match ($name) {
            'system'      => new Api\System($this),
            'invoice'     => new Api\Invoice($this),
            default       => throw new \Exception('tbd'),
        };
    }

    public function __call(string $name, array $args): BaseApi
    {
        try {
            return $this->api($name);
        } catch (\Exception $e) {
            throw new \BadMethodCallException($e->getMessage());
        }
    }

    public function auth(string $apiKey): static
    {
        $this->getBuilder()->removePlugin(Plugin\Authentication::class);
        $this->getBuilder()->addPlugin(new Plugin\Authentication($apiKey));

        return $this;
    }

    protected function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getBuilder()->getHttpClient();
    }

    public function getApiPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get the last response
     *
     * @return null|ResponseInterface
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->history->getLastResponse();
    }
}
