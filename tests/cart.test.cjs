const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

function makeCart() {
  const values = new Map();
  const context = {
    window: { dispatchEvent() {} },
    localStorage: {
      getItem: key => values.get(key) ?? null,
      setItem: (key, value) => values.set(key, value),
    },
    document: { querySelectorAll: () => [], addEventListener() {} },
    Event: class { constructor(name) { this.type = name; } },
    URL,
    console,
  };
  const code = fs.readFileSync(path.resolve(__dirname, '../js/cart.js'), 'utf8');
  vm.runInNewContext(code, context);
  return { cart: context.window.TambitoCart, values };
}

test('adds, updates and removes items without external checkout', () => {
  const { cart, values } = makeCart();
  const item = { codigo: 'P100', nombre: 'Producto demo', precio: 12.5, imagen_url: 'img/test.png' };
  assert.equal(cart.add(item, 2), true);
  assert.equal(cart.items()[0].cantidad, 2);
  cart.add(item, 2);
  assert.equal(cart.items()[0].cantidad, 4);
  cart.setQuantity('P100', 7);
  assert.equal(cart.items()[0].cantidad, 7);
  assert.ok(values.get('tambito_demo_cart_v1'));
  cart.remove('P100');
  assert.equal(cart.items().length, 0);
});

test('rejects invalid data and constrains quantities', () => {
  const { cart } = makeCart();
  assert.equal(cart.add({ codigo: 'P1', nombre: 'Fake', precio: 9, imagen_url: 'javascript:alert(1)' }), false);
  assert.equal(cart.add({ codigo: 'P1', nombre: 'Fake', precio: -10, imagen_url: 'img/a.png' }), false);
  assert.equal(cart.add({ codigo: 'P1', nombre: 'Producto', precio: 9, imagen_url: './img/a.png' }, 999), true);
  assert.equal(cart.items()[0].cantidad, 99);
  cart.setQuantity('P1', 0);
  assert.equal(cart.items().length, 0);
});
