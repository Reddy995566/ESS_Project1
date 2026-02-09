@extends('admin.layouts.app')

@section('title', 'Document Details')

@section('content')
<!-- Main Container -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.document-verification.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                    Document Verification
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $document->document_type_name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $document->document_type_name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $document->seller->business_name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if($document->verification_status === 'pending')
                    <button id="approveBtn" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve
                    </button>
                    <button id="rejectBtn" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                @endif
                <a href="{{ route('admin.document-verification.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-xl border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md animate-slideIn">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Document Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Document Information Card -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            Document Details
                        </h2>
                        <div>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '⏳', 'label' => 'Pending Review'],
                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => '✅', 'label' => 'Approved'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '❌', 'label' => 'Rejected']
                                ];
                                $config = $statusConfig[$document->verification_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                {{ $config['icon'] }} {{ $config['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Document Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Document Type:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $document->document_type_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Document Name:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $document->document_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">File Size:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $document->file_size_formatted }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">File Type:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Uploaded:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $document->created_at->format('M d, Y H:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Status -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Status</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                        {{ $config['icon'] }} {{ $config['label'] }}
                                    </span>
                                </div>
                                @if($document->verifiedBy)
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Verified By:</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $document->verifiedBy->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Verified At:</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $document->verified_at->format('M d, Y H:i A') }}</span>
                                    </div>
                                @endif
                                @if($document->rejection_reason)
                                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <h4 class="text-sm font-medium text-red-800 mb-2">Rejection Reason:</h4>
                                        <p class="text-sm text-red-700">{{ $document->rejection_reason }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Preview Card -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Document Preview
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $fileExtension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $isPdf = $fileExtension === 'pdf';
                    @endphp

                    @if($isImage)
                        <!-- Image Preview -->
                        <div class="text-center">
                            <div class="inline-block border-4 border-gray-200 rounded-xl overflow-hidden shadow-lg">
                                <img src="{{ $document->file_url ?: asset('storage/' . $document->file_path) }}" 
                                     alt="{{ $document->document_name }}" 
                                     class="max-w-full max-h-96 object-contain">
                            </div>
                            <div class="mt-4">
                                <a href="{{ $document->file_url ?: asset('storage/' . $document->file_path) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    View Full Size
                                </a>
                            </div>
                        </div>
                    @elseif($isPdf)
                        <!-- PDF Preview -->
                        <div class="text-center">
                            <div class="bg-gray-100 rounded-xl p-8 mb-4">
                                <svg class="w-24 h-24 mx-auto text-red-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">PDF Document</h3>
                                <p class="text-sm text-gray-600">{{ $document->document_name }}</p>
                            </div>
                            <div class="space-x-3">
                                <a href="{{ $document->file_url ?: asset('storage/' . $document->file_path) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Open PDF
                                </a>
                                <a href="{{ $document->file_url ?: asset('storage/' . $document->file_path) }}" 
                                   download 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Other File Types -->
                        <div class="text-center">
                            <div class="bg-gray-100 rounded-xl p-8 mb-4">
                                <svg class="w-24 h-24 mx-auto text-gray-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ strtoupper($fileExtension) }} Document</h3>
                                <p class="text-sm text-gray-600">{{ $document->document_name }}</p>
                            </div>
                            <a href="{{ $document->file_url ?: asset('storage/' . $document->file_path) }}" 
                               download 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Seller Information Card -->
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Seller Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center border-4 border-blue-200 mb-4">
                            <span class="text-2xl font-bold text-blue-700">{{ substr($document->seller->business_name, 0, 1) }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $document->seller->business_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $document->seller->contact_person }}</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $document->seller->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Phone:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $document->seller->phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Status:</span>
                            @php
                                $sellerStatusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '⏳', 'label' => 'Pending'],
                                    'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => '✅', 'label' => 'Approved'],
                                    'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '❌', 'label' => 'Rejected']
                                ];
                                $sellerConfig = $sellerStatusConfig[$document->seller->status] ?? $sellerStatusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $sellerConfig['bg'] }} {{ $sellerConfig['text'] }} border {{ $sellerConfig['border'] }}">
                                {{ $sellerConfig['icon'] }} {{ $sellerConfig['label'] }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Joined:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $document->seller->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.sellers.show', $document->seller) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            View Seller Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Verification Progress Card -->
            @if(isset($verificationProgress))
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Verification Progress
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Overall Progress</span>
                            <span>{{ $verificationProgress['completion_percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $verificationProgress['completion_percentage'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>{{ $verificationProgress['completed'] }} of {{ $verificationProgress['total'] }} completed</span>
                            @if($verificationProgress['is_complete'])
                                <span class="text-green-600 font-semibold">✅ Complete</span>
                            @else
                                <span class="text-yellow-600 font-semibold">⏳ In Progress</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($verificationProgress['progress'] as $type => $data)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">{{ $data['name'] }}</span>
                                @php
                                    $statusConfig = [
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => '✅', 'label' => 'Approved'],
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => '⏳', 'label' => 'Pending'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => '❌', 'label' => 'Rejected'],
                                        'not_started' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => '⚪', 'label' => 'Not Started']
                                    ];
                                    $config = $statusConfig[$data['status']] ?? $statusConfig['not_started'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                    {{ $config['icon'] }} {{ $config['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Other Documents Card -->
            @if($otherDocuments->count() > 0)
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-600 rounded-lg mr-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        Other Documents
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($otherDocuments as $otherDoc)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $otherDoc->document_type_name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $otherDoc->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @php
                                        $otherStatusConfig = [
                                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => '⏳'],
                                            'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => '✅'],
                                            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '❌']
                                        ];
                                        $otherConfig = $otherStatusConfig[$otherDoc->verification_status] ?? $otherStatusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $otherConfig['bg'] }} {{ $otherConfig['text'] }}">
                                        {{ $otherConfig['icon'] }}
                                    </span>
                                    <a href="{{ route('admin.document-verification.show', $otherDoc) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Approve Document</h3>
        </div>
        <form id="approveForm">
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="approveNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="approveNotes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any notes about the approval..."></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                <button type="button" id="cancelApprove" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Approve Document</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Reject Document</h3>
        </div>
        <form id="rejectForm">
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea id="rejectionReason" name="rejection_reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a clear reason for rejection..." required></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex justify-end space-x-3">
                <button type="button" id="cancelReject" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Reject Document</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/**
 * Document Verification Show Page Script
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeActionButtons();
    initializeModals();
});

// Action Buttons
function initializeActionButtons() {
    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');

    approveBtn?.addEventListener('click', function() {
        showModal('approveModal');
    });

    rejectBtn?.addEventListener('click', function() {
        showModal('rejectModal');
    });
}

// Modals
function initializeModals() {
    // Approve modal
    const cancelApprove = document.getElementById('cancelApprove');
    const approveForm = document.getElementById('approveForm');

    cancelApprove?.addEventListener('click', () => hideModal('approveModal'));
    approveForm?.addEventListener('submit', handleApproveSubmit);

    // Reject modal
    const cancelReject = document.getElementById('cancelReject');
    const rejectForm = document.getElementById('rejectForm');

    cancelReject?.addEventListener('click', () => hideModal('rejectModal'));
    rejectForm?.addEventListener('submit', handleRejectSubmit);

    // Close modals on outside click
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal(this.id);
            }
        });
    });

    // ESC key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModal('approveModal');
            hideModal('rejectModal');
        }
    });
}

// Modal Functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset forms
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
}

// Form Handlers
async function handleApproveSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(`{{ route('admin.document-verification.approve', $document) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            hideModal('approveModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    }
}

async function handleRejectSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch(`{{ route('admin.document-verification.reject', $document) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            hideModal('rejectModal');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    }
}

// Utility Functions
function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
    toast.innerHTML = `
        <span class="text-lg font-bold">${icon}</span>
        <span class="font-medium">${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, 5000);
}
</script>
@endpush
@endsection