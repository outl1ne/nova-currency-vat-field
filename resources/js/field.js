import IndexField from './components/IndexField';
import DetailField from './components/DetailField';
import FormField from './components/FormField';

Nova.booting((Vue, router) => {
  Vue.component('index-currency-vat-field', IndexField);
  Vue.component('detail-currency-vat-field', DetailField);
  Vue.component('form-currency-vat-field', FormField);
});
