<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerDocument;
use App\Models\SellerVerification;
use App\Models\Seller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DocumentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = SellerDocument::with(['seller', 'verifiedBy'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Search by seller name or business name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('seller', function($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => SellerDocument::count(),
            'pending' => SellerDocument::where('verification_status', 'pending')->count(),
            'approved' => SellerDocument::where('verification_status', 'approved')->count(),
            'rejected' => SellerDocument::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.document-verification.index', compact('documents', 'stats'));
    }

    public function show(SellerDocument $document)
    {
        $document->load(['seller', 'verifiedBy']);
        
        // Get seller's other documents
        $otherDocuments = $document->seller->documents()
            ->where('id', '!=', $document->id)
            ->latest()
            ->get();

        // Get seller's verification progress
        $verificationProgress = $document->seller->getVerificationProgress();

        return view('admin.document-verification.show', compact(
            'document', 
            'otherDocuments', 
            'verificationProgress'
        ));
    }

    public function approve(Request $request, SellerDocument $document)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $adminId = Auth::guard('admin')->id();
        
        // Approve the document
        $document->approve($adminId, $request->notes);

        // Update corresponding verification
        $verificationType = $this->mapDocumentToVerificationType($document->document_type);
        $verification = SellerVerification::where([
            'seller_id' => $document->seller_id,
            'verification_type' => $verificationType,
        ])->first();

        if ($verification) {
            $verification->approve($adminId, $request->notes);
        }

        // Send notification to seller
        $document->seller->sendNotification(
            'document_approved',
            'Document Approved',
            "Your {$document->document_type_name} has been approved.",
            json_encode(['document_id' => $document->id])
        );

        // Log admin activity
        ActivityLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'document_approved',
            'description' => "Approved document: {$document->document_type_name} for seller {$document->seller->business_name}",
            'model_type' => get_class($document),
            'model_id' => $document->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document approved successfully.',
        ]);
    }

    public function reject(Request $request, SellerDocument $document)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $adminId = Auth::guard('admin')->id();
        
        // Reject the document
        $document->reject($request->rejection_reason, $adminId);

        // Update corresponding verification
        $verificationType = $this->mapDocumentToVerificationType($document->document_type);
        $verification = SellerVerification::where([
            'seller_id' => $document->seller_id,
            'verification_type' => $verificationType,
        ])->first();

        if ($verification) {
            $verification->reject($request->rejection_reason, $adminId);
        }

        // Send notification to seller
        $document->seller->sendNotification(
            'document_rejected',
            'Document Rejected',
            "Your {$document->document_type_name} has been rejected. Reason: {$request->rejection_reason}",
            json_encode(['document_id' => $document->id])
        );

        // Log admin activity
        ActivityLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'document_rejected',
            'description' => "Rejected document: {$document->document_type_name} for seller {$document->seller->business_name}. Reason: {$request->rejection_reason}",
            'model_type' => get_class($document),
            'model_id' => $document->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document rejected successfully.',
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:seller_documents,id',
            'rejection_reason' => 'required_if:action,reject|string|max:1000',
        ]);

        $documents = SellerDocument::whereIn('id', $request->document_ids)
            ->where('verification_status', 'pending')
            ->get();

        $adminId = Auth::guard('admin')->id();
        $processed = 0;

        foreach ($documents as $document) {
            if ($request->action === 'approve') {
                $document->approve($adminId);
                
                // Send notification
                $document->seller->sendNotification(
                    'document_approved',
                    'Document Approved',
                    "Your {$document->document_type_name} has been approved.",
                    json_encode(['document_id' => $document->id])
                );
            } else {
                $document->reject($request->rejection_reason, $adminId);
                
                // Send notification
                $document->seller->sendNotification(
                    'document_rejected',
                    'Document Rejected',
                    "Your {$document->document_type_name} has been rejected. Reason: {$request->rejection_reason}",
                    json_encode(['document_id' => $document->id])
                );
            }
            
            $processed++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully {$request->action}d {$processed} documents.",
        ]);
    }

    public function sellerDocuments(Seller $seller)
    {
        $documents = $seller->documents()->latest()->get();
        $verificationProgress = $seller->getVerificationProgress();
        $requiredDocuments = $seller->getRequiredDocuments();

        return view('admin.document-verification.seller-documents', compact(
            'seller',
            'documents',
            'verificationProgress',
            'requiredDocuments'
        ));
    }

    private function mapDocumentToVerificationType($documentType)
    {
        $mapping = [
            'pan_card' => 'identity',
            'identity_proof' => 'identity',
            'gst_certificate' => 'business',
            'business_registration' => 'business',
            'bank_statement' => 'bank_account',
            'cancelled_cheque' => 'bank_account',
            'address_proof' => 'address',
        ];

        return $mapping[$documentType] ?? 'identity';
    }
}