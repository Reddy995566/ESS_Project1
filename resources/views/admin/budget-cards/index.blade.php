@extends('admin.layouts.app')

@section('title', 'Shop By Budget Cards')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-zinc-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Shop By Budget</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage budget cards displayed on homepage</p>
                </div>
                <button onclick="openModal()" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Budget Card
                </button>
            </div>
        </div>

        <!-- Budget Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="budget-cards-container">
            @forelse($budgetCards as $card)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group" data-id="{{ $card->id }}">
                <!-- Preview Card -->
                <div class="relative h-48 flex items-center justify-center" style="background: linear-gradient(to bottom right, var(--color-primary, #5C1F33), var(--color-primary-light, #7A2D45), var(--color-primary, #5C1F33));">
                    <div class="w-32 h-32 flex items-center justify-center rotate-45 border-2" style="border-color: var(--color-accent-gold, #E6B873);">
                        <div class="flex flex-col items-center justify-center -rotate-45">
                            <p class="text-white uppercase font-semibold text-sm">{{ $card->title }}</p>
                            <p class="text-white uppercase font-medium text-xs mt-1">{{ $card->subtitle }}</p>
                            <p class="font-bold text-xl mt-1" style="color: var(--color-accent-gold, #E6B873);">₹ {{ number_format($card->price) }}</p>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $card->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $card->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <!-- Card Info -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $card->title }} {{ $card->subtitle }} ₹{{ number_format($card->price) }}</h3>
                            <p class="text-xs text-gray-500">Sort: {{ $card->sort_order }}</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <button onclick="editCard({{ $card->id }}, '{{ $card->title }}', '{{ $card->subtitle }}', {{ $card->price }}, '{{ $card->link }}', {{ $card->sort_order }}, {{ $card->is_active ? 'true' : 'false' }})" 
                            class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                            Edit
                        </button>
                        <button onclick="toggleStatus({{ $card->id }})" 
                            class="px-3 py-2 {{ $card->is_active ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} rounded-lg transition text-sm font-medium">
                            {{ $card->is_active ? 'Disable' : 'Enable' }}
                        </button>
                        <button onclick="deleteCard({{ $card->id }})" 
                            class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Budget Cards Yet</h3>
                    <p class="text-gray-500 mb-6">Create your first budget card to display on the homepage</p>
                    <button onclick="openModal()" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
                        Add First Card
                    </button>
                </div>
            </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Add/Edit Modal -->
<div id="cardModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Add Budget Card</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="cardForm" class="p-6 space-y-4">
            <input type="hidden" id="cardId" value="">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="cardTitle" placeholder="e.g., SAREES, SHIRTS" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Main text (SAREES, KURTAS, etc.)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" id="cardSubtitle" placeholder="e.g., UNDER, STARTING AT" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Secondary text (UNDER, FROM, etc.)</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
                    <input type="number" id="cardPrice" placeholder="999" min="1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" id="cardSortOrder" placeholder="0" min="0"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Custom Link (Optional)</label>
                <input type="text" id="cardLink" placeholder="Leave empty for auto price filter"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Leave empty to auto-link to shop with max_price filter</p>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="checkbox" id="cardActive" checked class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <label for="cardActive" class="text-sm text-gray-700">Active (Show on website)</label>
            </div>
            
            <!-- Live Preview -->
            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                <p class="text-xs font-medium text-gray-500 mb-3">PREVIEW</p>
                <div class="h-32 flex items-center justify-center rounded-lg" style="background: linear-gradient(to bottom right, #5C1F33, #7A2D45, #5C1F33);">
                    <div class="w-24 h-24 flex items-center justify-center rotate-45 border-2" style="border-color: #E6B873;">
                        <div class="flex flex-col items-center justify-center -rotate-45">
                            <p id="previewTitle" class="text-white uppercase font-semibold text-xs">SHOP</p>
                            <p id="previewSubtitle" class="text-white uppercase font-medium text-[10px] mt-0.5">UNDER</p>
                            <p id="previewPrice" class="font-bold text-sm mt-0.5" style="color: #E6B873;">₹ 999</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <div class="px-6 py-4 bg-gray-50 flex items-center justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</button>
            <button onclick="saveCard()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Save Card</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let editingId = null;

    function openModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'Add Budget Card';
        document.getElementById('cardForm').reset();
        document.getElementById('cardActive').checked = true;
        updatePreview();
        document.getElementById('cardModal').classList.remove('hidden');
        document.getElementById('cardModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('cardModal').classList.add('hidden');
        document.getElementById('cardModal').classList.remove('flex');
    }

    function editCard(id, title, subtitle, price, link, sortOrder, isActive) {
        editingId = id;
        document.getElementById('modalTitle').textContent = 'Edit Budget Card';
        document.getElementById('cardId').value = id;
        document.getElementById('cardTitle').value = title;
        document.getElementById('cardSubtitle').value = subtitle;
        document.getElementById('cardPrice').value = price;
        document.getElementById('cardLink').value = link || '';
        document.getElementById('cardSortOrder').value = sortOrder;
        document.getElementById('cardActive').checked = isActive;
        updatePreview();
        document.getElementById('cardModal').classList.remove('hidden');
        document.getElementById('cardModal').classList.add('flex');
    }

    function updatePreview() {
        const title = document.getElementById('cardTitle').value || 'SHOP';
        const subtitle = document.getElementById('cardSubtitle').value || 'UNDER';
        const price = document.getElementById('cardPrice').value || '999';
        
        document.getElementById('previewTitle').textContent = title.toUpperCase();
        document.getElementById('previewSubtitle').textContent = subtitle.toUpperCase();
        document.getElementById('previewPrice').textContent = '₹ ' + Number(price).toLocaleString('en-IN');
    }

    // Live preview updates
    document.getElementById('cardTitle').addEventListener('input', updatePreview);
    document.getElementById('cardSubtitle').addEventListener('input', updatePreview);
    document.getElementById('cardPrice').addEventListener('input', updatePreview);

    function saveCard() {
        const data = {
            title: document.getElementById('cardTitle').value,
            subtitle: document.getElementById('cardSubtitle').value,
            price: document.getElementById('cardPrice').value,
            link: document.getElementById('cardLink').value,
            sort_order: document.getElementById('cardSortOrder').value || 0,
            is_active: document.getElementById('cardActive').checked ? 1 : 0,
        };

        const url = editingId 
            ? `/admin/budget-cards/${editingId}` 
            : '/admin/budget-cards';
        
        const method = editingId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Error: ' + (result.message || 'Something went wrong'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving card');
        });
    }

    function deleteCard(id) {
        if (!confirm('Are you sure you want to delete this budget card?')) return;

        fetch(`/admin/budget-cards/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            }
        });
    }

    function toggleStatus(id) {
        fetch(`/admin/budget-cards/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
@endsection
