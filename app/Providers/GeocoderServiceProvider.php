<?php

namespace App\Providers;

use App\Facades\Geocoder as GeocoderFacade;
use App\Services\GeocoderService;
use Illuminate\Support\ServiceProvider;

class GeocoderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('geocoder', function () {
            return new GeocoderService();
        });

        if (! class_exists('Geocoder')) {
            class_alias(GeocoderFacade::class, 'Geocoder');
        }
    }
}
