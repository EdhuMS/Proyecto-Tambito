// Escape data-derived text before placing it into HTML templates.
const tambitoEscape = value => String(value ?? '').replace(/[&<>"']/g, char => ({
  '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
}[char]));
document.addEventListener('DOMContentLoaded', function () {
    fetch('api/categorias.php')
        .then(res => res.json())
        .then(data => {
            const contenedor = document.querySelector('.hero-content');

            for (const categoria in data) {
                const seccion = document.createElement('section');
                seccion.classList.add('category-section');

                const header = `
                    <div class="category-header">
                        <h2 class="category-title">${tambitoEscape(categoria)}</h2>
                        <a href="productos.php" class="view-all" data-category="${tambitoEscape(categoria)}">Ver más <i class="fas fa-chevron-right"></i></a>
                    </div>
                `;

                const btnVerMas = `
                    <a href="productos.php" class="vermas-card" data-category="${tambitoEscape(categoria)}">
                        <div class="icon-container">
                            <div class="boton-mas"></div>
                        </div>
                        <div class="text-vermas">Ver más</div>
                    </a>
                `;

                let productosHTML = '';

                const productosMostrados = data[categoria].productos.slice(0, 7);

                productosMostrados.forEach(prod => {
                    const discountBadge = prod.descuento ? `<div class="product-badge">-${prod.descuento}%</div>` : '';

                    productosHTML += `
                        <div class="product-card" data-product='${encodeURIComponent(JSON.stringify(prod))}'> ${discountBadge}
                            <div class="product-image">
                                <img src="${tambitoEscape(prod.imagen_url)}?v=${Date.now()}" alt="${tambitoEscape(prod.nombre)}">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${tambitoEscape(prod.nombre)}</h3>
                                <div class="product-footer">
                                    <div class="product-price">
                                        <span class="current-price">S/ ${parseFloat(prod.precio).toFixed(2)}</span>
                                    </div>
                                    <button class="add-to-cart">+</button>
                                </div>
                            </div>
                        </div>
                    `;
                });

                seccion.innerHTML = header + `<div class="product-carousel">${productosHTML}${btnVerMas} </div>`;
                contenedor.appendChild(seccion);
            }

            document.querySelectorAll('.view-all, .vermas-card').forEach(button => {
                button.addEventListener('click', function(event) {
                    const category = this.dataset.category;
                    localStorage.setItem('selectedCategory', category);
                });
            });

            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function(event) {
                    if (event.target.classList.contains('add-to-cart')) {
                        return;
                    }
                    const productData = JSON.parse(decodeURIComponent(this.dataset.product));
                    window.openProductModal(productData);
                });
            });

        })
        .catch(err => {
            console.error("❌ Error cargando categorías:", err);
        });
});