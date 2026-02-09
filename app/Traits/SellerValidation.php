<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait SellerValidation
{
    /**
     * Get seller-specific unique validation rule
     */
    protected function getSellerUniqueRule($table, $field, $excludeId = null, $sellerId = null)
    {
        $sellerId = $sellerId ?: Auth::guard('seller')->id();
        
        return function ($attribute, $value, $fail) use ($table, $field, $excludeId, $sellerId) {
            try {
                // Convert table name to proper model class name
                $modelClass = $this->getModelClass($table);
                
                if (!class_exists($modelClass)) {
                    \Log::error("SellerValidation: Model class {$modelClass} not found for table {$table}");
                    $fail("Validation error: Model not found.");
                    return;
                }
                
                $query = $modelClass::where('seller_id', $sellerId)
                                  ->where($field, $value)
                                  ->whereNull('deleted_at');
                
                if ($excludeId) {
                    $query->where('id', '!=', $excludeId);
                }
                
                if ($query->exists()) {
                    $fail("The {$field} has already been taken by you.");
                }
            } catch (\Exception $e) {
                \Log::error("SellerValidation error: " . $e->getMessage(), [
                    'table' => $table,
                    'field' => $field,
                    'seller_id' => $sellerId,
                    'value' => $value
                ]);
                $fail("Validation error occurred. Please try again.");
            }
        };
    }

    /**
     * Get proper model class name from table name
     */
    protected function getModelClass($table)
    {
        // Map table names to model classes
        $modelMap = [
            'colors' => 'Color',
            'categories' => 'Category', 
            'brands' => 'Brand',
            'collections' => 'Collection',
            'fabrics' => 'Fabric',
            'sizes' => 'Size',
            'tags' => 'Tag',
        ];
        
        if (isset($modelMap[$table])) {
            return 'App\\Models\\' . $modelMap[$table];
        }
        
        // Fallback: convert table name to singular model name
        $modelName = Str::studly(Str::singular($table));
        return 'App\\Models\\' . $modelName;
    }

    /**
     * Get validation rules for seller-specific name field
     */
    protected function getSellerNameValidation($table, $excludeId = null, $sellerId = null)
    {
        return [
            'required',
            'string',
            'max:255',
            $this->getSellerUniqueRule($table, 'name', $excludeId, $sellerId)
        ];
    }

    /**
     * Get validation rules for seller-specific slug field
     */
    protected function getSellerSlugValidation($table, $excludeId = null, $sellerId = null)
    {
        return [
            'nullable',
            'string',
            'max:255',
            $this->getSellerUniqueRule($table, 'slug', $excludeId, $sellerId)
        ];
    }
}