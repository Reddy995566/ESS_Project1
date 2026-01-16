<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialsController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('review', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sorting
        $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 25);
        if ($perPage == -1) {
            $testimonials = $query->get();
            $testimonials = new \Illuminate\Pagination\LengthAwarePaginator(
                $testimonials, $testimonials->count(), $testimonials->count(), 1
            );
        } else {
            $testimonials = $query->paginate($perPage);
        }

        // Stats
        $totalTestimonials = Testimonial::count();
        $activeTestimonials = Testimonial::where('is_active', true)->count();
        $avgRating = Testimonial::where('is_active', true)->avg('rating') ?? 0;

        return view('admin.testimonials.index', compact(
            'testimonials',
            'totalTestimonials',
            'activeTestimonials',
            'avgRating'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Testimonial::create([
            'name' => $request->name,
            'review' => $request->review,
            'rating' => $request->rating,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully!');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial->update([
            'name' => $request->name,
            'review' => $request->review,
            'rating' => $request->rating,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully!']);
    }

    public function toggle(Request $request, Testimonial $testimonial)
    {
        $field = $request->field;
        $value = $request->value;

        if ($field === 'is_active') {
            $testimonial->update(['is_active' => $value]);
            return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field']);
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected']);
        }

        switch ($action) {
            case 'activate':
                Testimonial::whereIn('id', $ids)->update(['is_active' => true]);
                return response()->json(['success' => true, 'message' => count($ids) . ' testimonials activated']);
            
            case 'deactivate':
                Testimonial::whereIn('id', $ids)->update(['is_active' => false]);
                return response()->json(['success' => true, 'message' => count($ids) . ' testimonials deactivated']);
            
            case 'delete':
                Testimonial::whereIn('id', $ids)->delete();
                return response()->json(['success' => true, 'message' => count($ids) . ' testimonials deleted']);
            
            default:
                return response()->json(['success' => false, 'message' => 'Invalid action']);
        }
    }
}
