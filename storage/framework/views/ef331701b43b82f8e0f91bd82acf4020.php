<?php $__env->startSection('title', 'Order Details - #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main Container -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center justify-center w-12 h-12 bg-white rounded-xl shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Order #<?php echo e($order->order_number); ?></h1>
                        <p class="mt-1 text-sm text-gray-600">Placed on <?php echo e($order->created_at->format('d M, Y h:i A')); ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <?php if(!$order->shiprocket_order_id): ?>

                        <button onclick="shipOrder(<?php echo e($order->id); ?>)" id="shipBtn" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span id="shipBtnText">Ship with Shiprocket</span>
                        </button>
                    <?php else: ?>
                        <?php if(!$order->awb_code): ?>
                             <button onclick="fetchCouriers()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                Select Courier & Generate AWB
                            </button>
                        <?php else: ?>
                            <span class="inline-flex items-center px-5 py-2.5 bg-green-100 text-green-700 text-sm font-semibold rounded-xl border border-green-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                AWB: <?php echo e($order->awb_code); ?>

                            </span>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    
                    <a href="<?php echo e(route('admin.orders.invoice', $order->id)); ?>" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-semibold rounded-xl hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Print Invoice
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md animate-slideIn">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800"><?php echo e(session('success')); ?></p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Order Items Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </span>
                            Order Items (<?php echo e($order->items->count()); ?>)
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200 hover:shadow-md transition-all">
                                <div class="flex-shrink-0 w-20 h-20 bg-white rounded-lg overflow-hidden border-2 border-gray-200">
                                    <?php
                                        // Try to get variant image first if variant exists
                                        $productImage = null;
                                        
                                        if ($item->variant && $item->variant->images) {
                                            $images = $item->variant->images;
                                            
                                            // Decode if it's a JSON string
                                            if (is_string($images)) {
                                                $images = json_decode($images, true);
                                            }
                                            
                                            // Check if we have valid array with images
                                            if (is_array($images) && count($images) > 0) {
                                                $firstImg = $images[0];
                                                $productImage = is_array($firstImg) ? ($firstImg['url'] ?? $firstImg['path'] ?? null) : $firstImg;
                                            }
                                        }
                                        
                                        // Fallback to getImageUrl method
                                        if (!$productImage) {
                                            $productImage = $item->getImageUrl();
                                        }
                                        
                                        // Fallback to product image
                                        if (!$productImage && $item->product) {
                                            $productImage = $item->product->image_url;
                                        }
                                        
                                        // Fallback to product images array
                                        if (!$productImage && $item->product && $item->product->images_array && count($item->product->images_array) > 0) {
                                            $productImage = $item->product->images_array[0];
                                        }
                                    ?>
                                    <?php if($productImage): ?>
                                        <img src="<?php echo e($productImage); ?>" alt="<?php echo e($item->product_name); ?>" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><svg class=\'w-8 h-8 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg></div>'">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-bold text-gray-900 truncate"><?php echo e($item->product_name); ?></h3>
                                    <?php if($item->variant): ?>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?php if($item->variant->size): ?> Size: <span class="font-semibold"><?php echo e($item->variant->size->name); ?></span> <?php endif; ?>
                                            <?php if($item->variant->color): ?> <?php if($item->variant->size): ?> | <?php endif; ?> Color: <span class="font-semibold"><?php echo e($item->variant->color->name); ?></span> <?php endif; ?>
                                        </p>
                                    <?php elseif($item->variant_name): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($item->variant_name); ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-600 mt-1">Qty: <span class="font-bold"><?php echo e($item->quantity); ?></span> × ₹<?php echo e(number_format($item->price, 2)); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-blue-600">₹<?php echo e(number_format($item->quantity * $item->price, 2)); ?></p>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Shipping Address
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <p class="text-sm font-bold text-gray-900"><?php echo e($order->first_name); ?><?php echo e($order->last_name ? ' ' . $order->last_name : ''); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($order->address); ?></p>
                            <?php if($order->address_line_2): ?>
                                <p class="text-sm text-gray-600"><?php echo e($order->address_line_2); ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600"><?php echo e($order->city); ?>, <?php echo e($order->state); ?> - <?php echo e($order->zipcode); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($order->country); ?></p>
                            <p class="text-sm text-gray-600">Phone: <span class="font-semibold"><?php echo e($order->phone); ?></span></p>
                            <p class="text-sm text-gray-600">Email: <span class="font-semibold"><?php echo e($order->email); ?></span></p>
                        </div>
                    </div>
                </div>

                <!-- Billing Address Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-pink-100 text-pink-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Billing Address
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            <p class="text-sm font-bold text-gray-900"><?php echo e(($order->billing_first_name ?? $order->first_name)); ?><?php echo e(($order->billing_last_name ?? $order->last_name) ? ' ' . ($order->billing_last_name ?? $order->last_name) : ''); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($order->billing_address ?? $order->address); ?></p>
                            <?php if($order->billing_address_line_2 || $order->address_line_2): ?>
                                <p class="text-sm text-gray-600"><?php echo e($order->billing_address_line_2 ?? $order->address_line_2); ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600"><?php echo e($order->billing_city ?? $order->city); ?>, <?php echo e($order->billing_state ?? $order->state); ?> - <?php echo e($order->billing_zipcode ?? $order->zipcode); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($order->billing_country ?? $order->country); ?></p>
                            <p class="text-sm text-gray-600">Phone: <span class="font-semibold"><?php echo e($order->billing_phone ?? $order->phone); ?></span></p>
                            <p class="text-sm text-gray-600">Email: <span class="font-semibold"><?php echo e($order->billing_email ?? $order->email); ?></span></p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                
                <!-- Order Status Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Update Status
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="<?php echo e(route('admin.orders.update-status', $order->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Order Status</label>
                                    <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <option value="pending" <?php echo e($order->status === 'pending' ? 'selected' : ''); ?>>⏳ Pending</option>
                                        <option value="processing" <?php echo e($order->status === 'processing' ? 'selected' : ''); ?>>🔄 Processing</option>
                                        <option value="shipped" <?php echo e($order->status === 'shipped' ? 'selected' : ''); ?>>🚚 Shipped</option>
                                        <option value="delivered" <?php echo e($order->status === 'delivered' ? 'selected' : ''); ?>>✅ Delivered</option>
                                        <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>❌ Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    Update Status
                                </button>
                            </div>
                        </form>
                        
                        <?php if(in_array($order->status, ['pending', 'processing', 'shipped'])): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <button onclick="showCancelModal()" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-bold rounded-xl hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                Cancel Order
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Order Summary
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900">₹<?php echo e(number_format($order->subtotal, 2)); ?></span>
                            </div>
                            <?php if($order->discount > 0): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount <?php if($order->coupon_code): ?>(<?php echo e($order->coupon_code); ?>)<?php endif; ?></span>
                                <span class="font-semibold text-green-600">-₹<?php echo e(number_format($order->discount, 2)); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold text-gray-900">₹<?php echo e(number_format($order->shipping, 2)); ?></span>
                            </div>
                            <?php if($order->tax > 0): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-semibold text-gray-900">₹<?php echo e(number_format($order->tax, 2)); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="border-t-2 border-gray-200 pt-3 mt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-black text-blue-600">₹<?php echo e(number_format($order->total, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Payment Info
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Method</span>
                                <?php if($order->payment_method === 'cod'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-700 border border-yellow-300">
                                        💵 Cash on Delivery
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-300">
                                        💳 Online Payment
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Status</span>
                                <?php if($order->payment_status === 'paid'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-300">
                                        ✅ Paid
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 border border-orange-300">
                                        ⏳ Pending
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Info Card (if cancelled) -->
                <?php if($order->status === 'cancelled'): ?>
                <div class="bg-white rounded-2xl shadow-2xl border border-red-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-red-200">
                        <h2 class="text-xl font-bold text-red-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Cancellation Info
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Cancelled By</span>
                                <span class="text-gray-900 font-semibold capitalize"><?php echo e($order->cancelled_by ?? 'N/A'); ?></span>
                            </div>
                            <?php if($order->cancelled_at): ?>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 font-medium">Cancelled At</span>
                                <span class="text-gray-900 font-semibold"><?php echo e($order->cancelled_at->format('d M, Y h:i A')); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if($order->cancellation_reason): ?>
                            <div class="pt-3 border-t border-red-100">
                                <p class="text-xs text-gray-500 font-medium mb-2">Reason:</p>
                                <p class="text-sm text-gray-700 bg-red-50 p-3 rounded-lg border border-red-100"><?php echo e($order->cancellation_reason); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<!-- Shiprocket Courier Modal -->
<div id="courierModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCourierModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Courier Service</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">Choose a courier partner to ship this order.</p>
                            <div id="courierList" class="space-y-3 max-h-96 overflow-y-auto">
                                <!-- Loading State -->
                                <div class="flex justify-center py-4">
                                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeCourierModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const shiprocketOrderUrl = "<?php echo e(route('admin.orders.shiprocket', $order->id)); ?>";
    const shiprocketCouriersUrl = "<?php echo e(route('admin.orders.shiprocket.couriers', $order->id)); ?>";
    const shiprocketAwbUrl = "<?php echo e(route('admin.orders.shiprocket.awb', $order->id)); ?>";
    const csrfToken = "<?php echo e(csrf_token()); ?>";

    function shipOrder(orderId) {
        const btn = document.getElementById('shipBtn');
        const btnText = document.getElementById('shipBtnText');
        
        // Loading State
        btn.disabled = true;
        btnText.innerHTML = 'Creating Order...';
        
        fetch(shiprocketOrderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                 window.location.reload(); 
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btnText.innerHTML = 'Ship with Shiprocket';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong!');
            btn.disabled = false;
            btnText.innerHTML = 'Ship with Shiprocket';
        });
    }

    function fetchCouriers() {
        const modal = document.getElementById('courierModal');
        const list = document.getElementById('courierList');
        
        modal.classList.remove('hidden');
        list.innerHTML = '<div class="flex justify-center py-4"><svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
        
        fetch(shiprocketCouriersUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCouriers(data.couriers);
            } else {
                list.innerHTML = `<div class="text-red-500 text-center p-4">Error: ${data.message}</div>`;
            }
        })
        .catch(error => {
            list.innerHTML = `<div class="text-red-500 text-center p-4">Failed to load couriers.</div>`;
        });
    }

    function renderCouriers(couriers) {
        const list = document.getElementById('courierList');
        if (couriers.length === 0) {
            list.innerHTML = '<div class="text-gray-500 text-center p-4">No couriers available.</div>';
            return;
        }

        let html = '';
        couriers.forEach(courier => {
            html += `
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50 transition-all cursor-pointer" onclick="selectCourier('${courier.courier_company_id}', '${courier.courier_name}')">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center font-bold text-gray-500">
                            ${courier.courier_name.charAt(0)}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">${courier.courier_name}</h4>
                            <p class="text-xs text-gray-500">Rate: ₹${courier.rate} | ETA: ${courier.etd} days</p>
                        </div>
                    </div>
                    <button class="px-3 py-1 bg-white border border-indigo-200 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition-colors">
                        Select
                    </button>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    function selectCourier(courierId, courierName) {
        if (!confirm(`Generate AWB with ${courierName}?`)) return;

        const list = document.getElementById('courierList');
        list.innerHTML = '<div class="flex flex-col items-center justify-center py-8"><svg class="animate-spin h-10 w-10 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="text-gray-600 font-medium">Generating AWB...</p></div>';

        const shipmentId = "<?php echo e($order->shiprocket_shipment_id); ?>";

        fetch(shiprocketAwbUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                courier_id: courierId,
                shipment_id: shipmentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('AWB Generated Successfully: ' + data.awb_code);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                closeCourierModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to generate AWB.');
            closeCourierModal();
        });
    }

    function closeCourierModal() {
        document.getElementById('courierModal').classList.add('hidden');
    }

    // Cancel Order Functions
    function showCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelReason').value = '';
    }

    function cancelOrder() {
        const reason = document.getElementById('cancelReason').value.trim();
        
        if (!reason) {
            alert('Please provide a reason for cancellation');
            return;
        }
        
        const btn = document.getElementById('confirmCancelBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        fetch('<?php echo e(route('admin.orders.cancel', $order->id)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order cancelled successfully');
                window.location.reload();
            } else {
                alert(data.message || 'Failed to cancel order');
                btn.disabled = false;
                btn.textContent = 'Cancel Order';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            btn.disabled = false;
            btn.textContent = 'Cancel Order';
        });
    }
</script>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCancelModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Cancel Order</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">Are you sure you want to cancel this order? This action cannot be undone.</p>
                            <form id="cancelForm">
                                <?php echo csrf_field(); ?>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation</label>
                                <textarea id="cancelReason" name="reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for cancelling this order..."></textarea>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="cancelOrder()" id="confirmCancelBtn" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel Order
                </button>
                <button type="button" onclick="closeCancelModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Keep Order
                </button>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>