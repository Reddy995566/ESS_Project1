<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#441227">
    <title>Signup - {{ $siteSettings['site_name'] ?? 'Fashion Store' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('website/assets/images/favicon.png') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cream': '#FAF5ED',
                        'wine': '#5C1F33', // Theme Primary
                        'wine-dark': '#4A1828',
                        'gold': '#D6B27A',
                        'dark-brown': '#2A1810',
                        'blue-flipkart': '#2874f0',
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        .font-serif-elegant {
            font-family: 'Playfair Display', serif;
        }

        .font-sans-premium {
            font-family: 'Instrument Sans', sans-serif;
        }

        /* Floating Label Styles */
        .floating-input:focus~label,
        .floating-input:not(:placeholder-shown)~label {
            top: -8px;
            font-size: 12px;
            color: #5C1F33;
            background-color: white;
            padding: 0 4px;
        }
    </style>
</head>

<body class="font-sans-premium bg-[#FAF5ED] min-h-screen flex items-center justify-center p-4">

    <!-- Main Container -->
    <div class="bg-white rounded-md shadow-lg flex overflow-hidden w-full max-w-[850px] min-h-[528px]"
        style="height: 528px;">

        <!-- Left Side (40%) - Theme Color -->
        <div class="w-2/5 bg-[#5C1F33] p-10 flex flex-col justify-between text-white relative">
            <div>
                <h2 class="text-[28px] font-semibold mb-4">Looks like you're new here!</h2>
                <p class="text-[#dbdbdb] text-[18px] leading-7 font-medium">Sign up with your mobile number to get started</p>
            </div>
            
            <!-- Decorative Element -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2">
                <h1 class="font-serif-elegant text-[#D6B27A] font-bold text-4xl tracking-widest opacity-30">{{ $siteSettings['site_name'] ?? 'Fashion Store' }}</h1>
            </div>
        </div>

        <!-- Right Side (60%) - White Form -->
        <div class="w-3/5 bg-white p-10 flex flex-col justify-between">
            <div class="flex-1 flex flex-col justify-center max-w-[400px] mx-auto w-full">
                
                <form class="space-y-6">
                    <!-- Input with Floating Label -->
                    <div class="relative">
                        <input type="tel" id="mobile" placeholder=" "
                            class="floating-input w-full border-b-2 border-gray-300 py-2 focus:outline-none focus:border-[#5C1F33] bg-transparent text-sm transition-colors"
                            required>
                        <label for="mobile"
                            class="absolute left-0 top-2 text-gray-500 text-sm transition-all duration-200 pointer-events-none">
                            Enter Mobile number
                        </label>
                    </div>

                    <p class="text-[10px] text-gray-500">
                        By continuing, you agree to {{ $siteSettings['site_name'] ?? 'Fashion Store' }}'s <a href="{{ route('terms-of-service') }}" class="text-[#5C1F33] font-medium">Terms of Use</a> and <a href="{{ route('privacy-policy') }}" class="text-[#5C1F33] font-medium">Privacy Policy</a>.
                    </p>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-[#FB641B] hover:bg-[#F4511E] text-white font-medium py-3 rounded-[2px] shadow-sm transition-colors text-[15px]">
                        Continue
                    </button>
                    
                </form>

            </div>

             <!-- Footer Link -->
             <div class="mt-auto text-center">
                <button onclick="window.location.href='{{ url('/login') }}'" class="bg-white border shadow-sm text-[#2874f0] font-medium text-sm py-3 px-8 hover:shadow-md transition-shadow rounded-[2px]">
                    Existing User? Log in
                </button>
            </div>
        </div>

    </div>

</body>

</html>
