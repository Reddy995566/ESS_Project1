<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SellerDocument;
use App\Models\SellerVerification;
use App\Services\ImageKitService;

class DocumentController extends Controller
{
    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    public function index()
    {
        $seller = Auth::guard('seller')->user();
        
        // Get verification progress
        $verificationProgress = $seller->getVerificationProgress();
        
        // Get required documents
        $requiredDocuments = $seller->getRequiredDocuments();
        
        // Get uploaded documents
        $uploadedDocuments = $seller->documents()->latest()->get()->groupBy('document_type');
        
        // Get document statuses
        $documentStatuses = [];
        foreach ($requiredDocuments as $type => $info) {
            $documentStatuses[$type] = $seller->getDocumentStatus($type);
        }
        
        return view('seller.documents.index', compact(
            'seller',
            'verificationProgress',
            'requiredDocuments',
            'uploadedDocuments',
            'documentStatuses'
        ));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:' . implode(',', array_keys(SellerDocument::DOCUMENT_TYPES)),
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'document_name' => 'nullable|string|max:255',
        ]);

        $seller = Auth::guard('seller')->user();

        try {
            $file = $request->file('document');
            $documentType = $request->document_type;
            $documentName = $request->document_name ?: (SellerDocument::DOCUMENT_TYPES[$documentType] ?? 'Document');

            // Generate unique filename
            $fileName = 'seller_' . $seller->id . '_' . $documentType . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Upload to ImageKit
            $folder = 'sellers/documents/' . $seller->id;
            $result = $this->imageKitService->upload($file, $fileName, $folder);

            if (!$result || !$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload document. Please try again.',
                ], 500);
            }

            // Save document record
            $document = SellerDocument::create([
                'seller_id' => $seller->id,
                'document_type' => $documentType,
                'document_name' => $documentName,
                'file_path' => $result['file_path'],
                'file_url' => $result['url'],
                'file_id' => $result['file_id'],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'verification_status' => SellerDocument::STATUS_PENDING,
            ]);

            // Create or update verification record
            SellerVerification::updateOrCreate(
                [
                    'seller_id' => $seller->id,
                    'verification_type' => $this->mapDocumentToVerificationType($documentType),
                ],
                [
                    'status' => SellerVerification::STATUS_PENDING,
                    'verification_data' => [
                        'document_id' => $document->id,
                        'uploaded_at' => now()->toISOString(),
                    ],
                ]
            );

            // Log activity
            $seller->logActivity('document_uploaded', "Uploaded {$documentName}", 'SellerDocument', $document->id);

            // Send notification to seller
            $seller->sendNotification(
                'document_uploaded',
                'Document Uploaded',
                "Your {$documentName} has been uploaded and is pending verification.",
                json_encode(['document_id' => $document->id])
            );

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully! It will be reviewed within 24-48 hours.',
                'document' => [
                    'id' => $document->id,
                    'name' => $document->document_name,
                    'type' => $document->document_type,
                    'status' => $document->verification_status,
                    'uploaded_at' => $document->created_at->format('M d, Y H:i'),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Document upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(SellerDocument $document)
    {
        $seller = Auth::guard('seller')->user();
        
        // Ensure document belongs to authenticated seller
        if ($document->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to document');
        }

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'name' => $document->document_name,
                'type' => $document->document_type_name,
                'status' => $document->verification_status,
                'file_url' => $document->file_url,
                'file_size' => $document->file_size_formatted,
                'uploaded_at' => $document->created_at->format('M d, Y H:i'),
                'verified_at' => $document->verified_at?->format('M d, Y H:i'),
                'rejection_reason' => $document->rejection_reason,
            ],
        ]);
    }

    public function delete(SellerDocument $document)
    {
        $seller = Auth::guard('seller')->user();
        
        // Ensure document belongs to authenticated seller
        if ($document->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to document');
        }

        // Only allow deletion of pending or rejected documents
        if ($document->verification_status === SellerDocument::STATUS_APPROVED) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved documents.',
            ], 400);
        }

        try {
            // Delete from ImageKit if file_id exists
            if ($document->file_id) {
                $this->imageKitService->delete($document->file_id);
            }

            // Delete document record
            $documentName = $document->document_name;
            $document->delete();

            // Log activity
            $seller->logActivity('document_deleted', "Deleted {$documentName}");

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reupload(Request $request, SellerDocument $document)
    {
        $seller = Auth::guard('seller')->user();
        
        // Ensure document belongs to authenticated seller
        if ($document->seller_id !== $seller->id) {
            abort(403, 'Unauthorized access to document');
        }

        // Only allow re-upload of rejected documents
        if ($document->verification_status !== SellerDocument::STATUS_REJECTED) {
            return response()->json([
                'success' => false,
                'message' => 'Can only re-upload rejected documents.',
            ], 400);
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            $file = $request->file('document');
            
            // Delete old file from ImageKit
            if ($document->file_id) {
                $this->imageKitService->delete($document->file_id);
            }

            // Upload new file
            $fileName = 'seller_' . $seller->id . '_' . $document->document_type . '_' . time() . '.' . $file->getClientOriginalExtension();
            $folder = 'sellers/documents/' . $seller->id;
            $result = $this->imageKitService->upload($file, $fileName, $folder);

            if (!$result || !$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload document. Please try again.',
                ], 500);
            }

            // Update document record
            $document->update([
                'file_path' => $result['file_path'],
                'file_url' => $result['url'],
                'file_id' => $result['file_id'],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'verification_status' => SellerDocument::STATUS_PENDING,
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);

            // Update verification status
            $verification = SellerVerification::where([
                'seller_id' => $seller->id,
                'verification_type' => $this->mapDocumentToVerificationType($document->document_type),
            ])->first();

            if ($verification) {
                $verification->update([
                    'status' => SellerVerification::STATUS_PENDING,
                    'rejection_reason' => null,
                ]);
            }

            // Log activity
            $seller->logActivity('document_reuploaded', "Re-uploaded {$document->document_name}", 'SellerDocument', $document->id);

            return response()->json([
                'success' => true,
                'message' => 'Document re-uploaded successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to re-upload document: ' . $e->getMessage(),
            ], 500);
        }
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