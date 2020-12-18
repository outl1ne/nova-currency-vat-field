<template>
  <default-field :field="field">
    <template slot="field">
      <div class="inline-flex mb-2">
        <div
          class="rounded-l-lg border-r-0 h-100 border border-60"
          :style="{ backgroundColor: value, width: '36px' }"
        />

        <input
          :id="field.name"
          type="text"
          class="w-25 form-control form-input form-input-bordered rounded-l-none"
          :class="errorClasses"
          :placeholder="placeholder"
          v-model="value"
        />
      </div>

      <p v-if="hasError" class="my-2 text-danger">
        {{ firstError }}
      </p>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
    setInitialValue() {
      this.value = this.field.value || '';
    },
    fill(formData) {
      formData.append(this.field.attribute, this.value || '');
    },
    handleChange(value) {
      this.value = value;
    },
  },

  computed: {
    placeholder() {
      if (this.field.extraAttributes === undefined) {
        return this.field.name;
      }
      return this.field.extraAttributes.placeholder || this.field.name;
    },
  },
};
</script>

<style lang="scss" scoped></style>
