<?php

namespace Outl1ne\NovaCurrencyVatField;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class CurrencyVAT extends Currency
{
    public $component = 'currency-vat-field';

    protected $storedWithVat = true;

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $novaRequest = app()->make(NovaRequest::class);
        if ($novaRequest->isResourceIndexRequest() || $novaRequest->isResourceDetailRequest()) {
            $this->component = 'currency-field';
        }

        $this->VAT(0);
        $this->storedWithVAT();
        $this->updatesWithCheckbox(false);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $model->{$attribute} = $request->input($requestAttribute) ?? null;
    }

    public function storedWithVAT($storedWithVat = true)
    {
        return $this->withMeta(['storedWithVat' => $storedWithVat]);
    }

    public function storedWithoutVAT($storedWithoutVat = true)
    {
        return $this->withMeta(['storedWithVat' => !$storedWithoutVat]);
    }

    public function VAT($vatPercentage = 0)
    {
        return $this->withMeta(['vat' => $vatPercentage]);
    }

    public function updatesWithCheckbox($updatesWithCheckbox = true)
    {
        return $this->withMeta(['updatesWithCheckbox' => $updatesWithCheckbox]);
    }
}
