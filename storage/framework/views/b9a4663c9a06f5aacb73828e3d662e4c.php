<?php if(isset($testimonials) && $testimonials->count() > 0): ?>
<section class="w-full py-12 md:py-16 px-0 md:px-12 lg:px-20" style="background-color: var(--color-bg-primary);">
    <!-- Heading -->
    <div class="flex justify-center mb-8 md:mb-12 px-4 md:px-0">
        <h2 class="text-xl md:text-[28px] font-medium px-4 py-2 text-center shadow-sm" style="background-color: var(--color-bg-secondary); color: var(--color-text-primary);">
            Let Our Customers Speak For Us
        </h2>
    </div>

    <!-- Swiper Container -->
    <div class="swiper testimonialSwiper relative px-0 md:px-4">
        <div class="swiper-wrapper pb-12">
            <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="swiper-slide h-auto">
                <div class="bg-transparent md:rounded-md p-5 text-center flex flex-col items-center h-full mx-0 md:mx-0" style="border: 1px solid var(--color-primary);">
                    <div class="flex gap-1 mb-3 justify-center">
                        <?php for($i=0; $i<5; $i++): ?>
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color: <?php echo e($i < $testimonial->rating ? 'var(--color-accent-gold)' : '#d1d5db'); ?>;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-[13px] italic leading-relaxed mb-4" style="color: var(--color-primary);">
                        "<?php echo e($testimonial->review); ?>"
                    </p>
                    <p class="text-[14px] font-medium mt-auto" style="color: var(--color-primary);">— <?php echo e($testimonial->name); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Swiper Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.testimonialSwiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                    centeredSlides: false,
                },
                1280: {
                    slidesPerView: 5,
                    spaceBetween: 24,
                    centeredSlides: false,
                }
            }
        });
    });
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/website/includes/testimonials.blade.php ENDPATH**/ ?>