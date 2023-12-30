<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 * @noinspection PhpDynamicFieldDeclarationInspection
 */

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Http\Controllers;

use Exhum4n\LaravelLibrary\Services\Service;
use Exhum4n\LaravelLibrary\Traits\Controller\HasService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @property Service $service
 *
 * @method string service()
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $traits = class_uses_recursive(static::class);

        if (in_array(HasService::class, $traits, true)) {
            $this->initializeService();
        }

        $this->applyMiddleware();
    }

    private function initializeService(): void
    {
        $this->service = app(static::service());
    }

    protected function applyMiddleware(): void
    {
    }
}
