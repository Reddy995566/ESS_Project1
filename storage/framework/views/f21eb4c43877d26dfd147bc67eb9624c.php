<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name')); ?> Seller</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Gradient backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .gradient-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        /* Sidebar transition */
        .sidebar {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        
        /* Card hover effect */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Topbar Loading Progress */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #4f46e5, #4338ca);
            z-index: 9999;
            transition: width 0.3s ease;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }
        
        #page-loader.loading {
            animation: loading 2s ease-in-out infinite;
        }
        
        @keyframes loading {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Page Loader -->
    <div id="page-loader"></div>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php echo $__env->make('seller.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <?php echo $__env->make('seller.components.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    
    <script>
        // Page loader for navigation
        const loader = document.getElementById('page-loader');
        
        // Show loader on link clicks only
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            // Only handle anchor tags, skip everything else
            if (link && link.href && !link.target && !link.hasAttribute('download') && e.target.tagName !== 'BUTTON') {
                try {
                    const currentDomain = window.location.hostname;
                    const linkDomain = new URL(link.href).hostname;
                    
                    // Only show loader for internal navigation
                    if (currentDomain === linkDomain) {
                        loader.style.width = '0';
                        loader.style.opacity = '1';
                        loader.classList.add('loading');
                    }
                } catch (e) {
                    // Ignore URL parsing errors
                }
            }
        });
        
        // Hide loader when page loads
        window.addEventListener('load', function() {
            loader.classList.remove('loading');
            loader.style.width = '100%';
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.width = '0';
                }, 300);
            }, 200);
        });
        
        // Show loader on page unload
        window.addEventListener('beforeunload', function() {
            loader.style.width = '0';
            loader.style.opacity = '1';
            loader.classList.add('loading');
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ESS_Project1\resources\views/seller/layouts/app.blade.php ENDPATH**/ ?>