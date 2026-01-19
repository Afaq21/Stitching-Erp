<?php

namespace App\Http\Controllers;
use App\Models\DesignCatalog;
use App\Models\Service;
use Illuminate\Http\Request;

class DesignCatalogController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('name')->get();

        $query = DesignCatalog::with('service');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('service', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $designs = $query->latest()->paginate(12);

        // Preserve search parameter in pagination
        $designs->appends($request->only('search'));

        $design = null;

        return view('Dashboard.design-catalog.index', compact('designs', 'services', 'design'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price_adjustment' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/designs'), $imageName);
            $data['image_path'] = 'uploads/designs/' . $imageName;
        }

        $design = DesignCatalog::create($data);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Design added successfully',
                'design' => $design->load('service')
            ]);
        }

        return redirect()->route('Dashboard.design-catalog.index')
            ->with('success', 'Design added successfully');
    }

    public function edit(DesignCatalog $designCatalog)
    {
        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'id' => $designCatalog->id,
                'title' => $designCatalog->title,
                'service_id' => $designCatalog->service_id,
                'description' => $designCatalog->description,
                'image_path' => $designCatalog->image_path,
                'price_adjustment' => $designCatalog->price_adjustment,
                'is_active' => $designCatalog->is_active
            ]);
        }

        $designs = DesignCatalog::with('service')->latest()->get();
        $services = Service::orderBy('name')->get();
        $design = $designCatalog;

        return view('Dashboard.design-catalog.index', compact('designs', 'services', 'design'));
    }

    public function update(Request $request, DesignCatalog $designCatalog)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price_adjustment' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($designCatalog->image_path && file_exists(public_path($designCatalog->image_path))) {
                unlink(public_path($designCatalog->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/designs'), $imageName);
            $data['image_path'] = 'uploads/designs/' . $imageName;
        }

        $designCatalog->update($data);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Design updated successfully',
                'design' => $designCatalog->load('service')
            ]);
        }

        return redirect()->route('Dashboard.design-catalog.index')
            ->with('success', 'Design updated successfully');
    }

    public function destroy(DesignCatalog $designCatalog)
    {
        // Delete image file if exists
        if ($designCatalog->image_path && file_exists(public_path($designCatalog->image_path))) {
            unlink(public_path($designCatalog->image_path));
        }

        $designCatalog->delete();

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Design deleted successfully'
            ]);
        }

        return redirect()->route('Dashboard.design-catalog.index')
            ->with('success', 'Design deleted successfully');
    }
}
