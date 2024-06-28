<?php

declare(strict_types=1);

namespace App\Router\Wrapper;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    private string $protocolVersion = '1.1';
    private array $headers = [];
    private StreamInterface $body;
    private array $serverParams = [];
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $uploadedFiles = [];
    private mixed $parsedBody;
    private array $attributes = [];
    private ?UriInterface $uri = null;
    private string $method = '';

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = [$value];

        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;

        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);

        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getParsedBody(): mixed
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }

    public function getRequestTarget(): string
    {
        $uri = $this->uri;
        if ($uri === null) {
            return '/';
        }

        $target = $uri->getPath();
        if ($uri->getQuery() !== '') {
            $target .= '?' . $uri->getQuery();
        }

        return $target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $newUri = $new->uri;

        if ($newUri !== null) {
            $newUri = $newUri->withPath($requestTarget);
            $newUri = $newUri->withQuery('');
        }

        $new->uri = $newUri;

        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}