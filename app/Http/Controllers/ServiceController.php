<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Base Services Page
    public function baseServices(Request $request)
    {
        $query = Service::where('service_category', 'base');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->latest()->paginate(12);
        
        // Preserve search parameter in pagination
        $services->appends($request->only('search'));
        
        return view('Dashboard.services.base', compact('services'));
    }

    // Add-on Services Page
    public function addonServices(Request $request)
    {
        $query = Service::where('service_category', 'addon');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $services = $query->latest()->paginate(12);
        
        // Preserve search parameter in pagination
        $services->appends($request->only('search'));
        
        return view('Dashboard.services.addon', compact('services'));
    }

    // Create Service Form
    public function create(Request $request)
    {
        // Sidebar ya button se type pass hoga, default 'base'
        $type = $request->query('type', 'base');
        return view('Dashboard.services.create', compact('type'));
    }

    // Store Service
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,both',
                'price' => 'required|numeric|min:0',
                'service_category' => 'required|in:base,addon',
            ]);

            $service = Service::create($request->only(['name','gender','price','service_category']));

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service created successfully',
                    'service' => $service
                ]);
            }

            // Redirect according to category
            $route = $request->service_category === 'base' ? 'Dashboard.services.base' : 'Dashboard.services.addon';

            return redirect()->route($route)
                ->with('success', 'Service created successfully');
                
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating service: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error creating service')->withInput();
        }
    }

    // Edit Service Form
    public function edit(Service $service)
    {
        try {
            // Return JSON for AJAX requests
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'service' => [
                        'id' => $service->id,
                        'name' => $service->name,
                        'gender' => $service->gender,
                        'price' => $service->price,
                        'service_category' => $service->service_category
                    ]
                ]);
            }
            
            return view('Dashboard.services.edit', compact('service'));
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading service: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error loading service');
        }
    }

    // Update Service
    public function update(Request $request, Service $service)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,both',
                'price' => 'required|numeric|min:0',
                'service_category' => 'required|in:base,addon',
            ]);

            $service->update($request->only(['name','gender','price','service_category']));

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service updated successfully',
                    'service' => $service
                ]);
            }

            $route = $service->service_category === 'base' ? 'Dashboard.services.base' : 'Dashboard.services.addon';

            return redirect()->route($route)
                ->with('success', 'Service updated successfully');
                
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating service: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error updating service')->withInput();
        }
    }

    // Delete Service
    public function destroy(Service $service)
    {
        try {
            $category = $service->service_category;
            $service->delete();

            // Return JSON for AJAX requests
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service deleted successfully'
                ]);
            }

            $route = $category === 'base' ? 'Dashboard.services.base' : 'Dashboard.services.addon';

            return redirect()->route($route)
                ->with('success', 'Service deleted successfully');
                
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting service: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting service');
        }
    }
}
