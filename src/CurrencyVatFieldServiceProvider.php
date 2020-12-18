<?php

namespace OptimistDigital\NovaCurrencyVatField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\NovaTranslationsLoader\LoadsNovaTranslations;

class CurrencyVatFieldServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('currency-vat', __DIR__ . '/../dist/js/field.js');
        });

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-currency-vat-field');
    }

    public function register()
    {
        //
    }
}
