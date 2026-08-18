<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="o1-flex o1-flex-col">
        <div class="o1-flex o1-flex-wrap o1-items-stretch o1-w-full o1-relative">
          <div class="o1-flex o1--mr-px">
            <span
              class="o1-flex o1-items-center o1-leading-normal o1-rounded o1-border o1-border-r-0 o1-border-gray-300 o1-px-3 o1-whitespace-nowrap o1-text-gray-600 o1-text-sm o1-font-bold dark:o1-border-gray-700 dark:o1-text-gray-400 o1-rounded-r-none"
            >
              {{ field.currency }}
            </span>
          </div>

          <input
            class="o1-flex-shrink o1-flex-grow o1-flex-auto o1-leading-normal o1-w-px o1-flex-1 o1-rounded-l-none form-control form-input form-input-bordered"
            :id="field.attribute"
            :dusk="field.attribute"
            v-bind="extraAttributes"
            :disabled="isReadonly"
            @input="handleChange"
            :value="value"
          />
        </div>

        <div v-if="field.vat" class="o1-mt-2 o1-flex o1-items-center o1-justify-between o1-text-xs">
          <CheckboxWithLabel :disabled="false" class="vat-checkbox" :checked="vatChecked" @input="vatChanged">
            <!-- Wrapped in an element so Nova's `space-x-2` on CheckboxWithLabel applies, as it only spaces element siblings -->
            <span>{{ __('currencyVatField.priceIncludesVat') }} ({{ field.vat }}%)</span>
          </CheckboxWithLabel>

          <span v-if="vatPreview" class="o1-whitespace-nowrap">
            {{ __(vatPreview.label) }}: {{ vatPreview.value }} {{ field.currency }}
          </span>
        </div>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';

export default {
  mixins: [DependentFormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      vatChecked: this.field.storedWithVat,
    };
  },

  methods: {
    vatChanged(e) {
      this.vatChecked = e.target.checked;

      if (this.field.updatesWithCheckbox) {
        this.value = this.getValueWithAdjustedVAT(this.value);
      }
    },

    fill(formData) {
      const valueToSend = this.getValueWithAdjustedVAT(this.value);

      // NB! Always fall back to null
      formData.append(this.field.attribute, valueToSend || '');
    },

    roundToPrecision(value) {
      const factor = Math.pow(10, this.precision);
      return Math.round(value * factor) / factor;
    },

    getValueWithAdjustedVAT(value) {
      if (!value || isNaN(value)) return void 0;

      if (!this.field.vat || isNaN(this.field.vat)) return value;

      // Same as original, no need to do anything
      if (this.vatChecked && this.field.storedWithVat) return value;
      if (!this.vatChecked && !this.field.storedWithVat) return value;

      // If VAT is checked, it means the original should be without VAT
      const newValue = this.vatChecked ? value / (1 + this.field.vat / 100) : value * (1 + this.field.vat / 100);

      return this.roundToPrecision(newValue);
    },
  },

  computed: {
    precision() {
      if (!this.field.step) return 2;

      // Zero-decimal currencies have a step without a fraction (eg. `1`)
      const step = String(this.field.step);
      return step.includes('.') ? step.split('.')[1].length : 0;
    },

    // The inverse of the entered value: the price without VAT when the input
    // already includes it, and the price with VAT when it does not
    vatPreview() {
      if (this.value === null || this.value === undefined || this.value === '' || isNaN(this.value)) return null;
      if (!this.field.vat || isNaN(this.field.vat)) return null;

      const value = Number(this.value);
      const rate = 1 + this.field.vat / 100;
      const preview = this.vatChecked ? value / rate : value * rate;

      return {
        label: this.vatChecked ? 'currencyVatField.withoutVat' : 'currencyVatField.withVat',
        value: this.roundToPrecision(preview).toFixed(this.precision),
      };
    },

    defaultAttributes() {
      return {
        type: 'number',
        min: this.field.min,
        max: this.field.max,
        step: this.field.step,
        pattern: this.field.pattern,
        placeholder: this.field.placeholder || this.field.name,
        class: this.errorClasses,
      };
    },

    extraAttributes() {
      return {
        ...this.defaultAttributes,
        ...this.field.extraAttributes,
      };
    },
  },
};
</script>
