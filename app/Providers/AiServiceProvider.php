<?php

namespace App\Providers;

use App\Services\Ai\Contracts\AIProviderInterface;
use App\Services\Ai\Providers\OpenAiProvider;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AIProviderInterface::class, function () {
            return new OpenAiProvider(
                apiKey: (string) config('services.ai.api_key'),
                baseUrl: (string) config('services.ai.base_url'),
                model: (string) config('services.ai.model'),
            );
        });
    }
}
