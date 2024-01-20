<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http;

use Exhum4n\LaravelLibrary\Enums\Request\Method;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\MessageInterface;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class Client extends PendingRequest
{
    protected string $method = Method::get->name;
    protected array $body = [];

    public function request(string $uri): Response
    {
        $this->beforeRequest();

        try {
            $response = $this->go($uri);
        } catch (RequestException $exception) {
            $this->handleException($exception);
        }

        $this->afterRequest();

        return new Response($response);
    }

    final public function setMethod(Method $method): self
    {
        $this->method = $method->name;

        return $this;
    }

    final public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    final public function setBody(array $body): self
    {
        $this->withBody(json_encode($body));

        return $this;
    }

    final public function setQueryParams(array $params): self
    {
        $this->withUrlParameters($params);

        return $this;
    }

    final public function setMultipartData(array $data): self
    {
        foreach ($data as $subKey => $value) {
            $this->body[] = [
                'name' => $subKey,
                'contents' => $value,
            ];
        }

        return $this;
    }

    final public function addHeaders(array $headers = []): self
    {
        $this->withHeaders($headers);

        return $this;
    }

    final public function setToken(string $token, ?string $type = null): self
    {
        $this->withToken($token, $type);

        return $this;
    }

    final public function setOptions(array $options): self
    {
        $this->withOptions($options);

        return $this;
    }

    /**
     * @throws RequestException
     */
    protected function go(string $uri): Response
    {
        return $this->{$this->method}($uri);
    }

    protected function beforeRequest(): void
    {
    }

    protected function afterRequest(): void
    {
    }

    protected function handleException(RequestException $exception): void
    {
        $message = $exception->getMessage();

        match ($exception->getCode()) {
            ResponseCode::HTTP_UNPROCESSABLE_ENTITY => throw ValidationException::withMessages((array) $message),
            ResponseCode::HTTP_UNAUTHORIZED => throw new AuthenticationException($message),
            ResponseCode::HTTP_FORBIDDEN => throw new AuthorizationException($message, ResponseCode::HTTP_FORBIDDEN),
            ResponseCode::HTTP_NOT_FOUND => throw new ModelNotFoundException($message, ResponseCode::HTTP_NOT_FOUND),
        };
    }
}
