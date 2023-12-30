<?php

declare(strict_types=1);

namespace Exhum4n\LaravelLibrary\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseProvider;

abstract class ServiceProvider extends BaseProvider
{
    protected function mergeConfig($name): void
    {
        $this->mergeConfigFrom($this->getConfigPath($name), $name);
    }

    protected function getConfigPath(string $configName): string
    {
        return __DIR__ . "/../../config/$configName.php";
    }

    protected function registerRoutes(string $routesName): void
    {
        $routesPath = __DIR__ . "/../../routes/$routesName.php";
        if (file_exists($routesPath)) {
            Route::middleware('api')->group($routesPath);
        }
    }

    protected function publishConfigs(array $configsNames): void
    {
        foreach ($configsNames as $configsName) {
            $this->publishConfig($configsName);
        }
    }

    protected function publishConfig(string $fileName): void
    {
        $configsDir = base_path('config');

        $this->publishes([__DIR__ . "/../../config/$fileName.php" => "$configsDir/$fileName.php"], 'library-configs');
    }
}
