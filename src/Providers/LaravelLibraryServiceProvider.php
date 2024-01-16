<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Providers;

use App\Exceptions\Handler as BaseHandler;
use Exhum4n\LaravelLibrary\Exceptions\Handler;

class LaravelLibraryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->replaceExceptionHandler();
    }

    public function register(): void
    {
        //
    }

    protected function replaceExceptionHandler(): void
    {
        $this->app->bind(BaseHandler::class, Handler::class);
    }
}