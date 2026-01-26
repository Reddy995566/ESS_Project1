<div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
    <a href="<?php echo e(route('product.show', $product->slug)); ?>" class="block">
        <!-- Product Image -->
        <div class="relative aspect-square overflow-hidden bg-gray-100">
            <?php if($product->image_url): ?>
                <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            <?php endif; ?>
            
            <?php if($product->sale_price > 0): ?>
                <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    SALE
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="p-3 md:p-4">
            <h3 class="text-sm md:text-base font-medium mb-2 line-clamp-2 text-gray-900">
                <?php echo e($product->name); ?>

            </h3>
            
            <div class="flex items-center gap-2">
                <?php if($product->sale_price > 0): ?>
                    <span class="text-base md:text-lg font-bold" style="color: var(--color-accent-gold);">
                        ₹<?php echo e(number_format($product->sale_price, 0)); ?>

                    </span>
                    <span class="text-xs md:text-sm text-gray-500 line-through">
                        ₹<?php echo e(number_format($product->price, 0)); ?>

                    </span>
                <?php else: ?>
                    <span class="text-base md:text-lg font-bold" style="color: var(--color-accent-gold);">
                        ₹<?php echo e(number_format($product->price, 0)); ?>

                    </span>
                <?php endif; ?>
            </div>

            <?php if($product->category): ?>
                <p class="text-xs text-gray-500 mt-2"><?php echo e($product->category->name); ?></p>
            <?php endif; ?>
        </div>
    </a>
</div>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/website/partials/product-card.blade.php ENDPATH**/ ?>