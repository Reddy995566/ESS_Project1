<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Get all addresses for authenticated user
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'addresses' => $addresses
        ]);
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'address' => 'required|string',
                'apartment' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|digits:6',
                'phone' => 'required|digits:10',
                'is_default' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // If this is set as default, unset all other defaults
            if ($request->is_default) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }

            $address = Auth::user()->addresses()->create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully!',
                'address' => $address
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving address: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        try {
            $address = Auth::user()->addresses()->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'address' => 'required|string',
                'apartment' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|digits:6',
                'phone' => 'required|digits:10',
                'is_default' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // If this is set as default, unset all other defaults
            if ($request->is_default) {
                Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            }

            $address->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
                'address' => $address
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating address: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an address
     */
    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully!'
        ]);
    }

    /**
     * Set an address as default
     */
    public function setDefault($id)
    {
        // Unset all defaults
        Auth::user()->addresses()->update(['is_default' => false]);
        
        // Set this one as default
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated!',
            'address' => $address
        ]);
    }
}
