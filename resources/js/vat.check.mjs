// Run with `npm test`
import assert from 'node:assert/strict';
import { applyVat, roundToPrecision } from './vat.mjs';

assert.equal(roundToPrecision(8.064516, 2), 8.06);
assert.equal(applyVat(100, 24, true, 2), 124);
assert.equal(applyVat(124, 24, false, 2), 100);
assert.equal(applyVat(10, 24, false, 4), 8.0645);
assert.equal(applyVat(0, 24, false, 2), 0);
assert.equal(applyVat('100.00', 24, true, 2), 124);
assert.equal(applyVat(1000, 24, true, 0), 1240);

for (const vat of [5, 9, 20, 22, 24, 25, 27]) {
  for (let cents = 0; cents <= 200000; cents++) {
    const price = cents / 100;

    // A price typed with VAT and stored without it at 4 decimals is shown as typed again
    assert.equal(applyVat(applyVat(price, vat, false, 4), vat, true, 2), price, `${price} with VAT at ${vat}%`);

    // A price stored without VAT survives being shown with VAT and saved again
    assert.equal(applyVat(applyVat(price, vat, true, 2), vat, false, 2), price, `${price} without VAT at ${vat}%`);
  }
}

console.log('vat.mjs OK');
