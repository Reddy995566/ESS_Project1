@extends('admin.layouts.app')

@section('title', 'Payout Details - ' . $payout->payout_number)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 p-6">
        
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.seller-payouts.index') }}" class="flex items-center justify-center w-10 h-10 bg-white text-gray-600 rounded-xl border-2 border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Payout Details</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $payout->payout_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if($payout->status === 'pending')
                        <button onclick="approvePayout({{ $payout->id }})" class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button onclick="rejectPayout({{ $payout->id }})" class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    @elseif($payout->status === 'processing')
                        <button onclick="completePayout({{ $payout->id }})" class="inline-flex items-center px-5 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Mark Complete
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl shadow-md">
                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl shadow-md">
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Payout Information -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Payout Overview -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Payout Overview</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Payout Number</label>
                                <p class="text-lg font-bold text-gray-900">{{ $payout->payout_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                <p class="text-lg font-bold text-green-600">₹{{ number_format($payout->amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Net Amount</label>
                                <p class="text-lg font-bold text-gray-900">₹{{ number_format($payout->net_amount, 2) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        'completed' => 'bg-green-100 text-green-800 border-green-300',
                                        'failed' => 'bg-red-100 text-red-800 border-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold border {{ $statusColors[$payout->status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Requested Date</label>
                                <p class="text-sm text-gray-900">{{ $payout->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            @if($payout->processed_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Processed Date</label>
                                <p class="text-sm text-gray-900">{{ $payout->processed_at->format('M d, Y h:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bank Details -->
                @if(!empty($bankDetails))
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Bank Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($bankDetails['account_holder']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder</label>
                                <p class="text-sm text-gray-900">{{ $bankDetails['account_holder'] }}</p>
                            </div>
                            @endif
                            @if(isset($bankDetails['bank_account']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $bankDetails['bank_account'] }}</p>
                            </div>
                            @endif
                            @if(isset($bankDetails['ifsc_code']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">IFSC Code</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $bankDetails['ifsc_code'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Transaction Details -->
                @if($payout->transaction_id)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Transaction Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                                <p class="text-sm text-gray-900 font-mono">{{ $payout->transaction_id }}</p>
                            </div>
                            @if($payout->transaction_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Date</label>
                                <p class="text-sm text-gray-900">{{ $payout->transaction_date->format('M d, Y h:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Seller Information -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Seller Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr($payout->seller->business_name ?? 'S', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $payout->seller->business_name ?? 'Unknown Business' }}</p>
                                <p class="text-sm text-gray-500">{{ $payout->seller->user->name ?? 'No User' }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                                <p class="text-sm text-gray-900">{{ $payout->seller->user->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</label>
                                <p class="text-sm text-gray-900">{{ $payout->seller->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Status</label>
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-800">
                                    {{ ucfirst($payout->seller->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.sellers.show', $payout->seller->id) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View Seller Profile
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Processing Information -->
                @if($payout->processedBy)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Processing Info</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Processed By</label>
                                <p class="text-sm text-gray-900">{{ $payout->processedBy->name ?? 'System' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Processed At</label>
                                <p class="text-sm text-gray-900">{{ $payout->processed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($payout->notes)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Notes</h2>
                    </div>
                    <div class="p-6">
                        @php
                            // Parse notes - check if first line is JSON (bank details)
                            $notesLines = explode("\n", $payout->notes);
                            $bankDetails = null;
                            $otherNotes = [];
                            
                            foreach ($notesLines as $line) {
                                $line = trim($line);
                                if (empty($line)) continue;
                                
                                // Try to decode as JSON
                                $decoded = json_decode($line, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $bankDetails = $decoded;
                                } else {
                                    $otherNotes[] = $line;
                                }
                            }
                        @endphp
                        
                        @if($bankDetails)
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h3 class="text-sm font-bold text-blue-900 mb-2">Bank Account Details</h3>
                            <div class="space-y-1 text-sm text-blue-800">
                                @if(isset($bankDetails['account_holder']))
                                    <p><span class="font-semibold">Account Holder:</span> {{ $bankDetails['account_holder'] }}</p>
                                @endif
                                @if(isset($bankDetails['bank_account']))
                                    <p><span class="font-semibold">Account Number:</span> {{ $bankDetails['bank_account'] }}</p>
                                @endif
                                @if(isset($bankDetails['ifsc_code']))
                                    <p><span class="font-semibold">IFSC Code:</span> {{ $bankDetails['ifsc_code'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if(count($otherNotes) > 0)
                        <div class="text-sm text-gray-700 space-y-2">
                            @foreach($otherNotes as $note)
                                <p class="leading-relaxed">{{ $note }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.seller-payouts.modals')
@endsection

@push('scripts')
    @include('admin.seller-payouts.scripts')
@endpush