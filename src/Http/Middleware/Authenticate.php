<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http\Middleware;

use App\Models\User;
use Closure;
use Exhum4n\LaravelLibrary\Enums\Request\Method;
use Exhum4n\LaravelLibrary\Http\Client;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpFoundation\Response as Code;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $authToken = $request->header('authorization');
        if ($authToken === null) {
            throw new AuthenticationException();
        }

        $client = $this->createHttpClient();

        $client->addHeaders(['authorization' => $authToken]);

        $response = $client->request('/');
        if ($response->status() !== Code::HTTP_OK) {
            throw new AuthenticationException();
        }

        $user = $this->makeUser($response);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    protected function createHttpClient(): Client
    {
        $client = new Client();

        $client->setBaseUrl(config('services.auth.scheme') . '://'. config('services.auth.domain'));

        return $client;
    }

    protected function makeUser(Response $response): User
    {
        return User::factory()->make(json_decode($response->body(), true));
    }
}