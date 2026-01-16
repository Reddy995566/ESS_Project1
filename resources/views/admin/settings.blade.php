@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <!-- Toast Notification (Outside Livewire) -->
    <div id="successToast" class="fixed top-20 right-4 z-50 max-w-sm" style="display: none; opacity: 0; transition: all 0.3s ease;">
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p id="toastMessage" class="text-green-900 font-medium"></p>
            </div>
        </div>
    </div>

    @include('admin.settings.index')
@endsection
