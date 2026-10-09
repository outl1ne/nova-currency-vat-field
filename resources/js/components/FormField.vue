<template>
  <DefaultField :field="currentField" :errors="errors" :show-help-text="showHelpText">
    <template #field>
      <div class="o1-flex o1-flex-col">
        <div class="o1-flex o1-flex-wrap o1-items-stretch o1-w-full o1-relative">
          <div class="o1-flex o1--mr-px">
            <span
              class="o1-flex o1-items-center o1-leading-normal o1-rounded o1-border o1-border-r-0 o1-border-gray-300 o1-px-3 o1-whitespace-nowrap o1-text-gray-600 o1-text-sm o1-font-bold dark:o1-border-gray-700 dark:o1-text-gray-400 o1-rounded-r-none"
            >
              {{ currentField.currency }}
            </span>
          </div>

          <input
            class="o1-flex-shrink o1-flex-grow o1-flex-auto o1-leading-normal o1-w-px o1-rounded-l-none form-control form-input form-control-bordered"
            :id="field.attribute"
            :dusk="field.attribute"
            v-bind="extraAttributes"
            :disabled="currentlyIsReadonly"
            @input="handleChange"
            :value="value"
          />
        </div>

        <div v-if="currentField.vat" class="o1-mt-2 o1-flex o1-items-center o1-justify-between o1-text-xs">
          <CheckboxWithLabel
            :disabled="currentlyIsReadonly"
            class="vat-checkbox"
            :checked="vatChecked"
            @input="vatChanged"
          >
            <!-- Wrapped in an element so Nova's `space-x-2` on CheckboxWithLabel applies, as it only spaces element siblings -->
            <span>{{ __('currencyVatField.priceIncludesVat') }} ({{ currentField.vat }}%)</span>
          </CheckboxWithLabel>

          <span v-if="vatPreview" class="o1-whitespace-nowrap">
            {{ __(vatPreview.label) }}: {{ vatPreview.value }} {{ currentField.currency }}
          </span>
        </div>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';
import { applyVat, roundToPrecision } from '../vat.mjs';

export default {
  mixins: [DependentFormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      vatChecked: this.field.storedWithVat || this.field.displayedWithVat,

      // The input's value and the checkbox state as derived from the stored value
      pristine: {},
    };
  },

  methods: {
    // Overrides DependentFormField, runs on creation and after a `dependsOn` sync
    setInitialValue() {
      const stored = this.currentField.value;
      if (stored === undefined || stored === null) return;

      this.value = this.toDisplayValue(stored);
      this.pristine = { value: this.value, vatChecked: this.vatChecked };
    },

    vatChanged(e) {
      const untouched = this.untouched;
      this.vatChecked = e.target.checked;

      if (!this.currentField.updatesWithCheckbox) return;

      // Still showing the stored price, so derive it again instead of rounding the shown number once more
      if (untouched) return this.setInitialValue();

      if (this.hasVat && this.isNumeric(this.value)) {
        // Checking adds VAT to the shown price, unchecking strips it
        this.value = this.applyVat(this.value, this.vatChecked).toFixed(this.precision);
      }
    },

    fill(formData) {
      // An untouched field sends the stored value back as-is, as converting
      // it for the input and back again must never alter it
      const valueToSend = this.untouched ? this.currentField.value : this.getValueWithAdjustedVAT(this.value);

      // NB! Always fall back to null
      formData.append(this.field.attribute, valueToSend ?? '');
    },

    applyVat(value, add, precision = this.precision) {
      return applyVat(value, this.currentField.vat, add, precision);
    },

    isNumeric(value) {
      return value !== null && value !== undefined && value !== '' && !isNaN(value);
    },

    // The input's value as it gets stored
    getValueWithAdjustedVAT(value) {
      if (!this.isNumeric(value)) return void 0;

      // Same as original, no need to do anything
      if (!this.needsConversion) return value;

      // If VAT is checked, it means the original should be without VAT
      return this.applyVat(value, !this.vatChecked, this.storedPrecision);
    },

    // The stored value as it gets shown in the input
    toDisplayValue(stored) {
      if (!this.isNumeric(stored)) return stored;

      if (this.needsConversion) return this.applyVat(stored, this.vatChecked).toFixed(this.precision);

      // The input's step rejects values with more decimals than its own
      return this.storedPrecision > this.precision
        ? roundToPrecision(stored, this.precision).toFixed(this.precision)
        : stored;
    },
  },

  computed: {
    hasVat() {
      return !!this.currentField.vat && !isNaN(this.currentField.vat);
    },

    // The checkbox says the opposite of how the value is stored
    needsConversion() {
      return this.hasVat && Boolean(this.vatChecked) !== Boolean(this.currentField.storedWithVat);
    },

    untouched() {
      return this.value === this.pristine.value && this.vatChecked === this.pristine.vatChecked;
    },

    storedPrecision() {
      return this.currentField.storedDecimals ?? this.precision;
    },

    precision() {
      if (!this.currentField.step) return 2;

      // Zero-decimal currencies have a step without a fraction (eg. `1`)
      const step = String(this.currentField.step);
      return step.includes('.') ? step.split('.')[1].length : 0;
    },

    // The inverse of the entered value: the price without VAT when the input
    // already includes it, and the price with VAT when it does not
    vatPreview() {
      if (!this.hasVat || !this.isNumeric(this.value)) return null;

      return {
        label: this.vatChecked ? 'currencyVatField.withoutVat' : 'currencyVatField.withVat',
        value: this.applyVat(this.value, !this.vatChecked).toFixed(this.precision),
      };
    },

    defaultAttributes() {
      return {
        type: 'number',
        min: this.currentField.min,
        max: this.currentField.max,
        step: this.currentField.step,
        pattern: this.currentField.pattern,
        placeholder: this.placeholder,
        class: this.errorClasses,
      };
    },

    extraAttributes() {
      return {
        ...this.defaultAttributes,
        ...this.currentField.extraAttributes,
      };
    },
  },
};
</script>
