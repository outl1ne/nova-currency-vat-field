<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="o1-flex o1-flex-col">
        <div class="o1-flex o1-flex-wrap o1-items-stretch o1-w-full o1-relative">
          <div class="o1-flex o1--mr-px">
            <span
              class="o1-flex o1-items-center o1-leading-normal o1-rounded o1-border o1-border-r-0 o1-border-gray-300 o1-px-3 o1-whitespace-no-wrap o1-text-80 o1-text-sm o1-font-bold dark:o1-border-gray-700 o1-rounded-r-none"
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

        <CheckboxWithLabel
          :disabled="false"
          class="vat-checkbox o1-mt-2 o1-text-xs"
          v-if="field.vat"
          :checked="vatChecked"
          @input="vatChanged"
        >
          {{ __('currencyVatField.priceIncludesVat') }} ({{ field.vat }}%)
        </CheckboxWithLabel>
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

    getValueWithAdjustedVAT(value) {
      const precision = this.field.step ? this.field.step.split('.')[1].length : 2;
      if (!value || isNaN(value)) return void 0;

      if (!this.field.vat || isNaN(this.field.vat)) return value;

      // Same as original, no need to do anything
      if (this.vatChecked && this.field.storedWithVat) return value;
      if (!this.vatChecked && !this.field.storedWithVat) return value;

      let newValue = value;

      // If VAT is checked, it means the original should be without VAT
      if (this.vatChecked) {
        newValue = value / (1 + this.field.vat / 100);
      } else {
        newValue = value * (1 + this.field.vat / 100);
      }

      newValue *= Math.pow(10, precision);
      newValue = Math.round(newValue);
      newValue /= Math.pow(10, precision);

      return newValue;
    },
  },

  computed: {
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
