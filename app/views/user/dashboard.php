<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8 max-w-4xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center">My Orders</h1>

    <div class="text-center mb-8">
        <p class="text-xl text-gray-700">Welcome, <span class="font-semibold text-[--color-dark-blue]"><?php echo htmlspecialchars($username); ?></span>!</p>
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Your Order History</h2>

    <?php if (empty($orders)): ?>
        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-gray-600 text-lg mb-2">You haven't placed any orders yet.</p>
            <a href="/pcbuild/public/products" class="inline-block bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors">Start Shopping!</a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-gray-50">
                    <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Order #<?php echo htmlspecialchars($order['id']); ?></h3>
                        <span class="text-sm text-gray-500"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></span>
                    </div>
                    <div class="mb-3">
                        <p class="text-gray-700">Total: <span class="font-semibold">$<?php echo number_format($order['total_amount'], 2); ?></span></p>
                        <p class="text-gray-700">Payment: <span class="font-semibold"><?php echo htmlspecialchars($order['payment_method']); ?></span></p>
                        <p class="text-gray-700">Status: <span class="font-semibold text-green-600"><?php echo htmlspecialchars($order['status']); ?></span></p>
                    </div>

                    <?php if (!empty($order['items'])): ?>
                        <h4 class="text-md font-semibold text-gray-700 mb-2">Items:</h4>
                        <ul class="list-none space-y-1">
                            <?php foreach ($order['items'] as $item): ?>
                                <li class="flex justify-between text-sm text-gray-600">
                                    <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                                    <span><?php echo htmlspecialchars($item['quantity']); ?> x $<?php echo number_format($item['price_at_purchase'], 2); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 italic">No items found for this order.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
