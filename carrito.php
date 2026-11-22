<?php
// Frontend-only shopping cart; this demonstrator never sends orders or accepts payment.
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito de demostración | Tambito</title>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
.demo-cart{max-width:950px;margin:3rem auto;padding:1rem 1.5rem;min-height:55vh}
.demo-cart h1{margin:1rem 0}.cart-list{display:grid;gap:1rem}
.cart-row{display:grid;grid-template-columns:76px 1fr auto;gap:1rem;align-items:center;padding:1rem;border:1px solid #ddd;border-radius:12px;background:#fff;color:#222}
.cart-row img{width:76px;height:76px;object-fit:contain}
.cart-actions{display:flex;gap:.5rem;align-items:center;flex-wrap:wrap}
.cart-actions button{padding:.4rem .6rem;border:1px solid #bbb;border-radius:6px;cursor:pointer}
.cart-summary{margin-top:1.5rem;text-align:right;font-weight:700}
.cart-help{margin:1rem 0;color:#444}
@media(max-width:600px){.cart-row{grid-template-columns:60px 1fr}.cart-actions{grid-column:1/-1}}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<main class="demo-cart">
<h1>Tu carrito</h1>
<p class="cart-help">Carrito de práctica guardado en este navegador. <strong>No se realizan compras ni cobros.</strong></p>
<div id="cart-list" class="cart-list" aria-live="polite"></div>
<p id="cart-summary" class="cart-summary"></p>
<a href="categorias.php">Continuar explorando categorías</a>
</main>
<?php include 'footer.php'; ?>
<script src="js/cart-page.js" defer></script>
</body></html>
