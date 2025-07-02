<div class="container mx-auto p-10 bg-white shadow-lg rounded-lg mt-28 mb-24 max-w-5xl">
<h1 class="text-4xl font-extrabold text-gray-900 mb-12 mt-6 text-center">My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200 shadow-md">
            <p class="text-gray-600 text-xl mb-4">You haven't placed any orders yet. Successful checkout of order will appear here.</p>
        </div>
    <?php else: ?>
        <div class="space-y-8">
            <?php
            $orderCountForDisplay = count($orders); 
            foreach ($orders as $order):
            ?>
                <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Order #<?php echo htmlspecialchars($orderCountForDisplay); ?></h3>
                            <p class="text-sm text-gray-600">Placed on: <?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></p>
                        </div>
                        <div class="text-right sm:ml-4 mt-3 sm:mt-0">
                            <p class="text-lg font-semibold text-gray-800">Total: <span class="text-[--color-primary-orange]">$<?php echo number_format($order['total_amount'], 2); ?></span></p>
                            <p class="text-md text-gray-700">Payment: <?php echo htmlspecialchars($order['payment_method']); ?></p>
                            <p class="text-md font-semibold <?php echo ($order['status'] === 'Pending') ? 'text-blue-600' : 'text-green-600'; ?>">Status: <?php echo htmlspecialchars($order['status']); ?></p>
                        </div>
                    </div>

                    <?php if (!empty($order['items'])): ?>
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Items in this Order:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex items-center bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                        <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://placehold.co/60x60/e2e8f0/475569?text=No+Image'); ?>" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                             class="w-16 h-16 object-contain rounded-md mr-4 flex-shrink-0">
                                        <div>
                                            <p class="text-md font-medium text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></p>
                                            <p class="text-sm text-gray-600">Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                            <p class="text-sm font-semibold text-gray-700">Price: $<?php echo number_format($item['price_at_purchase'], 2); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500 italic">No items found for this order.</div>
                    <?php endif; ?>
                </div>
            <?php 
            $orderCountForDisplay--;
            endforeach; 
            ?>
        </div>
    <?php endif; ?>
</div>