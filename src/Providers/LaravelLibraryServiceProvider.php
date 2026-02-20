<?php

/**
 * @noinspection PhpUndefinedClassInspection
 * @noinspection PhpUndefinedNamespaceInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Providers;

use App\Exceptions\Handler as BaseHandler;
use Exhum4n\LaravelLibrary\Exceptions\Handler;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Exhum4n\LaravelLibrary\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class LaravelLibraryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->replaceExceptionHandler();
        $this->registerValidators();
    }

    public function register(): void
    {
        $this->app->bind(BaseAuthenticate::class, Authenticate::class);
    }

    protected function replaceExceptionHandler(): void
    {
        $this->app->bind(BaseHandler::class, Handler::class);
    }

    private function registerValidators(): void
    {
        Validator::extend('enum', Enum::class);
        Validator::extend('uuid', Uuid::class);
    }
}