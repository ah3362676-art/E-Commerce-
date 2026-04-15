import './bootstrap';
import Alpine from 'alpinejs';

// ===============================
// تفعيل Alpine
// ===============================
window.Alpine = Alpine;
Alpine.start();

// ===============================
// أصوات الإشعارات
// notificationSoundAdmin  => خاص بإشعارات الأدمن
// notificationSoundUser   => خاص بإشعارات المستخدم
// ===============================
const notificationSoundAdmin = new Audio('/sounds/notification.mp3');
const notificationSoundUser = new Audio('/sounds/notification.mp3');

// ===============================
// أول ما الصفحة تفتح وتكون جاهزة
// ===============================
document.addEventListener('DOMContentLoaded', () => {
    console.log('app.js loaded');
    console.log('userRole:', window.userRole);
    console.log('userId:', window.userId);
    console.log('Echo:', window.Echo);

    // =========================================
    // ده خاص بالأدمن فقط
    // قناة الأوردرات للأوردر الجديد
    // =========================================
    if (window.userRole === 'admin') {
        window.Echo.private('orders')
            .subscribed(() => {
                console.log('✅ subscribed to private orders channel');
            })
            .listen('.order.created', (e) => {
                console.log('New order 🔥', e);

                const order = e.order ?? e;

                // تشغيل صوت إشعار الأدمن
                notificationSoundAdmin.currentTime = 0;
                notificationSoundAdmin.play().catch(() => {});

                // إظهار إشعار الأوردر الجديد
                showNewOrderNotification(order);

                // إضافة الأوردر الجديد في الصفحة بدون ريفريش
                addOrderToPage(order);
            });
    }

    // =========================================
    // قناة المنتجات
    // الأدمن + اليوزر يسمعوا تحديثات المنتجات
    // =========================================
    window.Echo.channel('products')
        .subscribed(() => {
            console.log('✅ subscribed to products channel');
        })
   .listen('.product.deleted', (e) => {
    console.log('🗑️ product deleted event arrived:', e);

    const product = e.product ?? e;
    console.log('resolved deleted product:', product);

    if (!product || !product.id) {
        console.log('❌ deleted product id missing');
        return;
    }

    if (window.userRole === 'admin') {
        notificationSoundAdmin.currentTime = 0;
        notificationSoundAdmin.play().catch(() => {});
        showDeletedProductNotification(product);
    }

    removeProductFromPage(product);
})
        .listen('.product.updated', (e) => {
            console.log('🔥 product updated event arrived:', e);

            const product = e.product ?? e;

            // إشعار وصوت للأدمن فقط
            if (window.userRole === 'admin') {
                notificationSoundAdmin.currentTime = 0;
                notificationSoundAdmin.play().catch(() => {});
                showUpdatedProductNotification(product);
            }

            // تحديث المنتج في الصفحة بدون ريفريش
            updateProductOnPage(product);
        })
        .listen('.product.created', (e) => {
            console.log('🆕 product created event arrived:', e);

            const product = e.product ?? e;

            // إشعار وصوت للأدمن فقط
            if (window.userRole === 'admin') {
                notificationSoundAdmin.currentTime = 0;
                notificationSoundAdmin.play().catch(() => {});
                showCreatedProductNotification(product);
            }

            // إضافة المنتج في الصفحة بدون ريفريش
            addProductToPage(product);
        });

    // =========================================
    // ده خاص بالمستخدم العادي
    // يسمع تحديث حالة الأوردر الخاص به فقط
    // =========================================
    if (window.userId) {
        window.Echo.private(`user.${window.userId}`)
            .subscribed(() => {
                console.log(`✅ subscribed to private user.${window.userId} channel`);
            })
            .listen('.order.status.updated', (e) => {
                console.log('Order updated 🔥', e);

                const order = e.order ?? e;

                // تشغيل صوت إشعار المستخدم
                notificationSoundUser.currentTime = 0;
                notificationSoundUser.play().catch(() => {});

                // إظهار إشعار تحديث الحالة
                showStatusUpdateNotification(order);

                // تغيير الحالة في الصفحة بدون ريفريش
                updateOrderStatus(order);
            });
    }
});

// =========================================
// دي خاصة بإشعار الأدمن لما ييجي أوردر جديد
// =========================================
function showNewOrderNotification(order) {
    const container = document.getElementById('notifications');

    if (!container) return;

    const notification = document.createElement('div');

    notification.className =
        'bg-white border-l-4 border-green-500 shadow-lg rounded-lg p-4 w-80';

    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">🛒 New Order #${order.id}</p>
                <p class="text-sm text-gray-600">By: ${order.user_name ?? 'Deleted User'}</p>
                <p class="text-sm text-gray-600">Total: $${Number(order.total ?? 0).toFixed(2)}</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">&times;</button>
        </div>
    `;

    notification.querySelector('button').onclick = () => {
        notification.remove();
    };

    container.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// =========================================
// دي خاصة بإضافة الأوردر الجديد في صفحة الأدمن
// بدون ريفريش
// =========================================
function addOrderToPage(order) {
    const container = document.getElementById('orders-container');

    if (!container) return;

    const newOrder = document.createElement('div');

    newOrder.className =
        'overflow-hidden rounded-2xl bg-white shadow transition hover:shadow-lg';

    newOrder.innerHTML = `
        <div class="border-b bg-gradient-to-r from-gray-50 to-white px-6 py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Order #${order.id}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Customer: ${order.user_name ?? 'Deleted User'}
                    </p>
                </div>

                <span class="inline-flex w-fit items-center rounded-full border px-4 py-1.5 text-xs font-semibold bg-yellow-100 text-yellow-700 border-yellow-200">
                    ${(order.status ?? 'pending').charAt(0).toUpperCase() + (order.status ?? 'pending').slice(1)}
                </span>
            </div>
        </div>

        <div class="px-6 py-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">User</p>
                    <p class="mt-2 text-sm font-medium text-gray-700 break-words">
                        ${order.user_name ?? 'Deleted User'}
                    </p>
                </div>

                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Total</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        $${Number(order.total ?? 0).toFixed(2)}
                    </p>
                </div>

                <div class="rounded-2xl bg-gray-50 px-4 py-4 border border-gray-100">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">New Order</p>
                    <p class="mt-2 text-sm text-gray-700">
                        Refresh if you want full actions for this order.
                    </p>
                </div>
            </div>
        </div>
    `;

    container.prepend(newOrder);
}

// =========================================
// دي خاصة بإشعار الأدمن لما منتج يتم إنشاؤه
// =========================================
function showCreatedProductNotification(product) {
    const container = document.getElementById('notifications');

    if (!container) return;

    const notification = document.createElement('div');

    notification.className =
        'bg-white border-l-4 border-green-500 shadow-lg rounded-lg p-4 w-80';

    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">🆕 Product Created</p>
                <p class="text-sm text-gray-600">ID: ${product.id}</p>
                <p class="text-sm text-gray-600">Title: ${product.title}</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">&times;</button>
        </div>
    `;

    notification.querySelector('button').onclick = () => {
        notification.remove();
    };

    container.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// =========================================
// دي خاصة بإشعار الأدمن لما منتج يتم حذفه
// =========================================
function showDeletedProductNotification(product) {
    const container = document.getElementById('notifications');

    if (!container) return;

    const notification = document.createElement('div');

    notification.className =
        'bg-white border-l-4 border-red-500 shadow-lg rounded-lg p-4 w-80';

    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">🗑️ Product Deleted</p>
                <p class="text-sm text-gray-600">ID: ${product.id}</p>
                <p class="text-sm text-gray-600">Title: ${product.title}</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">&times;</button>
        </div>
    `;

    notification.querySelector('button').onclick = () => {
        notification.remove();
    };

    container.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// =========================================
// دي خاصة بحذف المنتج من الصفحة مباشرة
// بدون ريفريش
// =========================================
function removeProductFromPage(product) {
    console.log('trying to remove:', product);

    const productElement = document.getElementById(`product-${product.id}`);

    if (!productElement) {
        console.log(`❌ product-${product.id} not found in current page`);
        return;
    }

    productElement.remove();
    console.log(`✅ product-${product.id} removed from DOM`);
}

// =========================================
// دي خاصة بإشعار تعديل المنتج
// =========================================
function showUpdatedProductNotification(product) {
    const container = document.getElementById('notifications');

    if (!container) return;

    const notification = document.createElement('div');

    notification.className =
        'bg-white border-l-4 border-blue-500 shadow-lg rounded-lg p-4 w-80';

    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">✏️ Product Updated</p>
                <p class="text-sm text-gray-600">ID: ${product.id}</p>
                <p class="text-sm text-gray-600">Title: ${product.title}</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">&times;</button>
        </div>
    `;

    notification.querySelector('button').onclick = () => {
        notification.remove();
    };

    container.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// =========================================
// دي خاصة بتحديث المنتج في الصفحة مباشرة
// بدون ريفريش
// =========================================
function updateProductOnPage(product) {
    const productCard = document.getElementById(`product-${product.id}`);
    const titleElement = document.getElementById(`product-title-${product.id}`);
    const priceElement = document.getElementById(`product-price-${product.id}`);
    const stockElement = document.getElementById(`product-stock-${product.id}`);
    const statusElement = document.getElementById(`product-status-${product.id}`);
    const categoryElement = document.getElementById(`product-category-${product.id}`);
    const imageElement = document.getElementById(`product-image-${product.id}`);
    const addToCartBtn = document.getElementById(`add-to-cart-${product.id}`);

    if (!productCard) {
        console.log(`❌ product-${product.id} not found in current page`);
        return;
    }

    if (titleElement) {
        titleElement.innerText = product.title ?? '';
    }

    if (priceElement) {
        priceElement.innerText = `$${Number(product.price ?? 0).toFixed(2)}`;
    }

    if (stockElement) {
        if (Number(product.stock) > 0) {
            stockElement.innerText = `In Stock (${product.stock})`;
            stockElement.className = 'text-green-600 font-medium';
        } else {
            stockElement.innerText = 'Out of Stock';
            stockElement.className = 'text-red-600 font-medium';
        }
    }

    if (categoryElement) {
        categoryElement.innerText = product.category ?? 'No Category';
    }

    if (statusElement) {
        statusElement.innerHTML = product.is_active
            ? `<span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>`
            : `<span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>`;
    }

    if (imageElement && product.image) {
        imageElement.src = product.image;
    }

    if (addToCartBtn) {
        if (Number(product.stock) > 0) {
            addToCartBtn.disabled = false;
            addToCartBtn.innerText = 'Add to Cart';
            addToCartBtn.className =
                'w-full rounded-full bg-yellow-400 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-yellow-500 transition flex items-center justify-center gap-2';
        } else {
            addToCartBtn.disabled = true;
            addToCartBtn.innerText = 'Out of Stock';
            addToCartBtn.className =
                'w-full rounded-full bg-gray-300 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed flex items-center justify-center gap-2';
        }
    }

    console.log(`✅ product-${product.id} updated in DOM`);
}

// =========================================
// دي خاصة بإضافة المنتج في الصفحة مباشرة
// بدون ريفريش
// =========================================
function addProductToPage(product) {
    const productsGrid = document.getElementById('product_grid');

    if (!productsGrid) {
        console.log('❌ products grid not found');
        return;
    }

    if (document.getElementById(`product-${product.id}`)) {
        console.log(`ℹ️ product-${product.id} already exists`);
        return; // "لو المنتج ده موجود في الصفحة بالفعل → متضيفوش تاني"
    }

    const productCard = document.createElement('div');
    productCard.id = `product-${product.id}`;
    productCard.className = 'bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col';

    const imageHtml = product.image
        ? `
            <img
                id="product-image-${product.id}"
                src="${product.image}"
                alt="${product.title}"
                class="h-full w-full object-cover hover:scale-105 transition duration-300"
            >
        `
        : `<div class="text-gray-400 text-sm">No Image</div>`;

    const stockText = Number(product.stock) > 0
        ? `In Stock (${product.stock})`
        : 'Out of Stock';

    const stockClass = Number(product.stock) > 0
        ? 'text-green-600 font-medium'
        : 'text-red-600 font-medium';

    const statusHtml = product.is_active
        ? `<span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Active</span>`
        : `<span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Inactive</span>`;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const addToCartHtml = window.userRole === 'user'
        ? `
            <form action="/cart" method="POST">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="product_id" value="${product.id}">
                <input type="hidden" name="quantity" value="1">
                <button
                    id="add-to-cart-${product.id}"
                    ${Number(product.stock) > 0 ? '' : 'disabled'}
                    class="${Number(product.stock) > 0
                        ? 'w-full rounded-full bg-yellow-400 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-yellow-500 transition flex items-center justify-center gap-2'
                        : 'w-full rounded-full bg-gray-300 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed flex items-center justify-center gap-2'}"
                >
                    ${Number(product.stock) > 0 ? 'Add to Cart' : 'Out of Stock'}
                </button>
            </form>
        `
        : '';

    const adminActionsHtml = window.userRole === 'admin'
        ? `
            <div class="flex gap-2">
                <a href="/products/${product.id}/edit"
                   class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-medium text-white hover:bg-blue-700 transition">
                    Edit
                </a>

                <form action="/products/${product.id}" method="POST" class="flex-1">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">

                    <button
                        class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        `
        : '';

    productCard.innerHTML = `
        <div class="h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
            ${imageHtml}
        </div>

        <div class="p-4 flex flex-col flex-1">
            <p class="text-xs text-gray-400 mb-1" id="product-category-${product.id}">
                ${product.category ?? 'No Category'}
            </p>

            <a href="/products/${product.id}" id="product-title-${product.id}" class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[56px]">
                ${product.title}
            </a>

            <div class="mb-2">
                <span id="product-price-${product.id}" class="text-2xl font-bold text-gray-900">
                    $${Number(product.price ?? 0).toFixed(2)}
                </span>
            </div>

            <div class="mb-3 text-sm">
                <span id="product-stock-${product.id}" class="${stockClass}">
                    ${stockText}
                </span>
            </div>

            <div class="mb-4" id="product-status-${product.id}">
                ${statusHtml}
            </div>

            <div class="mt-auto space-y-2">
                ${addToCartHtml}
                ${adminActionsHtml}
            </div>
        </div>
    `;

    productsGrid.prepend(productCard);

    console.log(`✅ product-${product.id} added to DOM`);
}

// =========================================
// دي خاصة بإشعار المستخدم لما حالة الأوردر تتغير
// =========================================
function showStatusUpdateNotification(order) {
    const container = document.getElementById('notifications');

    if (!container) return;

    const notification = document.createElement('div');

    notification.className =
        'bg-white border-l-4 border-blue-500 shadow-lg rounded-lg p-4 w-80';

    notification.innerHTML = `
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">Order #${order.id} Status Updated</p>
                <p class="text-sm text-gray-600">New Status: ${order.status}</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">&times;</button>
        </div>
    `;

    notification.querySelector('button').onclick = () => {
        notification.remove();
    };

    container.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// =========================================
// دي خاصة بالمستخدم
// لتحديث حالة الأوردر في الصفحة لايف
// وتغيير اللون كمان
// =========================================
function updateOrderStatus(order) {
    const statusElement = document.getElementById(`order-status-${order.id}`);

    if (!statusElement) return;

    statusElement.innerText =
        order.status.charAt(0).toUpperCase() + order.status.slice(1);

    statusElement.className =
        'inline-flex w-fit items-center rounded-full border px-4 py-1.5 text-xs font-semibold';

    if (order.status === 'pending') {
        statusElement.classList.add('bg-yellow-100', 'text-yellow-700', 'border-yellow-200');
    } else if (order.status === 'shipped') {
        statusElement.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-200');
    } else if (order.status === 'delivered') {
        statusElement.classList.add('bg-green-100', 'text-green-700', 'border-green-200');
    } else if (order.status === 'cancelled') {
        statusElement.classList.add('bg-red-100', 'text-red-700', 'border-red-200');
    } else {
        statusElement.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
    }
}
