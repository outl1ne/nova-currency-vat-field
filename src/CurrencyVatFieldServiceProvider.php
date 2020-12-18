<?php

namespace OptimistDigital\NovaCurrencyVatField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class CurrencyVatFieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('currency-vat', __DIR__ . '/../dist/js/field.js');
        });
    }

    public function register()
    {
        //
    }
}
