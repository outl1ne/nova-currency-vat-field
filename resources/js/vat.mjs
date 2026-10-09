export const roundToPrecision = (value, precision) => {
  const factor = Math.pow(10, precision);
  return Math.round(value * factor) / factor;
};

// Adds VAT to a price without it, or strips it from a price that includes it
export const applyVat = (value, vat, add, precision) => {
  const rate = 1 + vat / 100;
  return roundToPrecision(add ? value * rate : value / rate, precision);
};
