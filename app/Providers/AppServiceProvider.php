<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default JSON encoding options to ensure proper handling of Unicode characters
        \Illuminate\Http\JsonResponse::macro('setEncodingOptions', function ($options) {
            $this->encodingOptions = $options;
            return $this;
        });
        
        \Illuminate\Routing\ResponseFactory::macro('json', function ($data = [], $status = 200, array $headers = [], $options = 0) {
            return response()->json($data, $status, $headers, $options | JSON_UNESCAPED_UNICODE);
        });
    }
}
