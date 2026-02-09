@extends('seller.layouts.app')

@section('title', 'Document Verification')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-file-alt text-indigo-600 mr-3"></i>
                            Document Verification
                        </h1>
                        <p class="mt-2 text-gray-600">Upload and manage your verification documents</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">{{ $verificationProgress['completion_percentage'] }}%</div>
                            <div class="text-sm text-gray-500">Complete</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Progress -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Verification Progress</h2>
                
                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ $verificationProgress['completed'] }} of {{ $verificationProgress['total'] }} completed</span>
                        <span>{{ $verificationProgress['completion_percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-300" 
                             style="width: {{ $verificationProgress['completion_percentage'] }}%"></div>
                    </div>
                </div>

                <!-- Verification Steps -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($verificationProgress['progress'] as $type => $info)
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 
                            @if($info['status'] === 'approved') border-green-500
                            @elseif($info['status'] === 'rejected') border-red-500
                            @elseif($info['status'] === 'pending') border-yellow-500
                            @else border-gray-300 @endif">
                            
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-medium text-gray-900">{{ $info['name'] }}</h3>
                                @if($info['status'] === 'approved')
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @elseif($info['status'] === 'rejected')
                                    <i class="fas fa-times-circle text-red-500"></i>
                                @elseif($info['status'] === 'pending')
                                    <i class="fas fa-clock text-yellow-500"></i>
                                @else
                                    <i class="fas fa-circle text-gray-400"></i>
                                @endif
                            </div>
                            
                            <div class="text-sm">
                                @if($info['status'] === 'approved')
                                    <span class="text-green-600 font-medium">Verified</span>
                                @elseif($info['status'] === 'rejected')
                                    <span class="text-red-600 font-medium">Rejected</span>
                                @elseif($info['status'] === 'pending')
                                    <span class="text-yellow-600 font-medium">Under Review</span>
                                @else
                                    <span class="text-gray-500">Not Started</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Document Upload Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($requiredDocuments as $type => $info)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-white">{{ $info['name'] }}</h3>
                                <p class="text-sm text-indigo-100">{{ $info['description'] }}</p>
                            </div>
                            <div class="flex items-center">
                                @if($info['required'])
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Required</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Optional</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @php
                            $status = $documentStatuses[$type] ?? 'not_uploaded';
                            $latestDocument = $uploadedDocuments[$type]->first() ?? null;
                        @endphp
                        
                        @if($status === 'not_uploaded')
                            <!-- Upload Form -->
                            <form class="document-upload-form" data-document-type="{{ $type }}">
                                @csrf
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-4">Drag and drop your file here, or click to browse</p>
                                    <input type="file" name="document" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <input type="hidden" name="document_type" value="{{ $type }}">
                                    <button type="button" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors" onclick="this.parentElement.querySelector('input[type=file]').click()">
                                        Choose File
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                                </div>
                            </form>
                        @else
                            <!-- Document Status -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-alt text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $latestDocument->document_name ?? 'Document' }}</p>
                                            <p class="text-sm text-gray-500">{{ $latestDocument->file_size_formatted ?? '' }} • Uploaded {{ $latestDocument->created_at->format('M d, Y') ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-clock mr-1"></i>Pending Review
                                            </span>
                                        @elseif($status === 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                        @elseif($status === 'rejected')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                                <i class="fas fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($status === 'rejected' && $latestDocument && $latestDocument->rejection_reason)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                                            <div>
                                                <p class="text-sm font-medium text-red-800">Rejection Reason:</p>
                                                <p class="text-sm text-red-700 mt-1">{{ $latestDocument->rejection_reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex space-x-3">
                                    @if($latestDocument && $latestDocument->file_url)
                                        <button onclick="viewDocument('{{ $latestDocument->file_url }}')" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>View
                                        </button>
                                    @endif
                                    
                                    @if($status === 'rejected')
                                        <button onclick="showReuploadForm('{{ $type }}', {{ $latestDocument->id ?? 0 }})" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                            <i class="fas fa-upload mr-2"></i>Re-upload
                                        </button>
                                    @endif
                                    
                                    @if($status !== 'approved')
                                        <button onclick="deleteDocument({{ $latestDocument->id ?? 0 }})" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Help Section -->
        <div class="mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Document Guidelines</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Ensure all documents are clear and readable</li>
                            <li>• Upload documents in PDF, JPG, or PNG format (max 5MB)</li>
                            <li>• Documents should be recent (within last 6 months for statements)</li>
                            <li>• All information should match your business registration details</li>
                            <li>• Verification typically takes 24-48 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-4xl max-h-[90vh] w-full mx-4 overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold">Document Viewer</h3>
            <button onclick="closeDocumentModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4 overflow-auto max-h-[80vh]">
            <iframe id="documentFrame" class="w-full h-96 border rounded"></iframe>
        </div>
    </div>
</div>

<!-- Re-upload Modal -->
<div id="reuploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4">Re-upload Document</h3>
            <form id="reuploadForm">
                @csrf
                <input type="hidden" id="reuploadDocumentId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select New File</label>
                    <input type="file" name="document" class="w-full px-3 py-2 border border-gray-300 rounded-lg" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeReuploadModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Document upload handling
document.querySelectorAll('.document-upload-form').forEach(form => {
    const fileInput = form.querySelector('input[type="file"]');
    const documentType = form.querySelector('input[name="document_type"]').value;
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            uploadDocument(form, this.files[0], documentType);
        }
    });
});

function uploadDocument(form, file, documentType) {
    const formData = new FormData();
    formData.append('document', file);
    formData.append('document_type', documentType);
    formData.append('_token', form.querySelector('input[name="_token"]').value);
    
    // Show loading state
    const uploadArea = form.querySelector('.border-dashed');
    uploadArea.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <span class="ml-3 text-gray-600">Uploading...</span>
        </div>
    `;
    
    fetch('{{ route("seller.documents.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
            // Reset upload area
            resetUploadArea(uploadArea);
        }
    })
    .catch(error => {
        showNotification('Upload failed. Please try again.', 'error');
        resetUploadArea(uploadArea);
    });
}

function resetUploadArea(uploadArea) {
    uploadArea.innerHTML = `
        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
        <p class="text-gray-600 mb-4">Drag and drop your file here, or click to browse</p>
        <button type="button" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            Choose File
        </button>
        <p class="text-xs text-gray-500 mt-2">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
    `;
}

function viewDocument(url) {
    document.getElementById('documentFrame').src = url;
    document.getElementById('documentModal').classList.remove('hidden');
    document.getElementById('documentModal').classList.add('flex');
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
    document.getElementById('documentModal').classList.remove('flex');
}

function showReuploadForm(documentType, documentId) {
    document.getElementById('reuploadDocumentId').value = documentId;
    document.getElementById('reuploadModal').classList.remove('hidden');
    document.getElementById('reuploadModal').classList.add('flex');
}

function closeReuploadModal() {
    document.getElementById('reuploadModal').classList.add('hidden');
    document.getElementById('reuploadModal').classList.remove('flex');
}

// Re-upload form handling
document.getElementById('reuploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const documentId = document.getElementById('reuploadDocumentId').value;
    const formData = new FormData(this);
    
    fetch(`/seller/documents/${documentId}/reupload`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeReuploadModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Re-upload failed. Please try again.', 'error');
    });
});

function deleteDocument(documentId) {
    if (!confirm('Are you sure you want to delete this document?')) {
        return;
    }
    
    fetch(`/seller/documents/${documentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Delete failed. Please try again.', 'error');
    });
}

// Notification function (reuse from settings)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-2xl max-w-sm transition-all duration-500 transform translate-x-full border-l-4`;
    
    if (type === 'success') {
        notification.className += ' bg-white border-green-500 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    } else if (type === 'error') {
        notification.className += ' bg-white border-red-500 text-gray-800';
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 500);
    }, 5000);
}
</script>
@endpush
@endsection