<?php

namespace App\Http\Controllers;

use App\Models\MeasurementAttribute;
use App\Models\Service;
use Illuminate\Http\Request;

class MeasurementAttributeController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::orderBy('name')->get();

        $query = MeasurementAttribute::with('service');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('service', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $measurementAttributes = $query->latest()->paginate(12);

        // Preserve search parameter in pagination
        $measurementAttributes->appends($request->only('search'));

        $measurementAttribute = null;

        return view('Dashboard.measurement-attributes.index', compact(
            'measurementAttributes',
            'services',
            'measurementAttribute'
        ));
    }

    public function edit(MeasurementAttribute $measurementAttribute)
    {
        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'id' => $measurementAttribute->id,
                'name' => $measurementAttribute->name,
                'service_id' => $measurementAttribute->service_id
            ]);
        }

        $measurementAttributes = MeasurementAttribute::with('service')->latest()->get();
        $services = Service::orderBy('name')->get();

        return view('Dashboard.measurement-attributes.index', compact(
            'measurementAttributes',
            'services',
            'measurementAttribute'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'service_id' => 'required|exists:services,id',
        ]);

        $attribute = MeasurementAttribute::create($request->all());

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute added successfully',
                'attribute' => $attribute->load('service')
            ]);
        }

        return redirect()->route('Dashboard.measurement-attributes.index')->with('success', 'Attribute added successfully');
    }

    public function update(Request $request, MeasurementAttribute $measurementAttribute)
    {
        $request->validate([
            'name' => 'required|string',
            'service_id' => 'required|exists:services,id',
        ]);

        $measurementAttribute->update($request->all());

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute updated successfully',
                'attribute' => $measurementAttribute->load('service')
            ]);
        }

        return redirect()->route('Dashboard.measurement-attributes.index')->with('success', 'Attribute updated successfully');
    }

    public function destroy(MeasurementAttribute $measurementAttribute)
    {
        $measurementAttribute->delete();

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute deleted successfully'
            ]);
        }

        return redirect()->route('Dashboard.measurement-attributes.index')->with('success', 'Attribute deleted successfully');
    }
}
