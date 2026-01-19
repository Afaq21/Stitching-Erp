<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\MeasurementValue;
use App\Models\MeasurementAttribute;
use App\Models\Service;
use App\Models\Customer;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $services  = Service::orderBy('name')->get();

        $measurementAttributes = MeasurementAttribute::orderBy('name')->get();

        $query = Measurement::with([
            'customer',
            'service',
            'values.measurementAttribute'
        ]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('service', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $measurements = $query->latest()->paginate(12);

        // Preserve search parameter in pagination
        $measurements->appends($request->only('search'));

        return view('Dashboard.measurements.index', compact(
            'customers',
            'services',
            'measurementAttributes',
            'measurements'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id'  => 'required|exists:services,id',
            'values'      => 'required|array'
        ]);

        // 🔹 Create measurement
        $measurement = Measurement::create([
            'customer_id' => $request->customer_id,
            'service_id'  => $request->service_id,
        ]);

        // 🔹 Save values
        foreach ($request->values as $attrId => $value) {
            MeasurementValue::create([
                'measurement_id' => $measurement->id,
                'measurement_attribute_id' => $attrId,
                'value' => $value,
                'notes' => $request->notes[$attrId] ?? null
            ]);
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement added successfully',
                'measurement' => $measurement
            ]);
        }

        return redirect()->back()->with('success', 'Measurement added successfully');
    }

    public function edit(Measurement $measurement)
    {
        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            $values = [];
            foreach ($measurement->values as $value) {
                $values[$value->measurement_attribute_id] = $value->value;
            }

            return response()->json([
                'id' => $measurement->id,
                'customer_id' => $measurement->customer_id,
                'service_id' => $measurement->service_id,
                'values' => $values
            ]);
        }

        return view('Dashboard.measurements.edit', compact('measurement'));
    }

    public function update(Request $request, Measurement $measurement)
    {
        $request->validate([
            'values' => 'required|array'
        ]);

        foreach ($request->values as $attrId => $value) {
            MeasurementValue::updateOrCreate(
                [
                    'measurement_id' => $measurement->id,
                    'measurement_attribute_id' => $attrId,
                ],
                [
                    'value' => $value,
                    'notes' => $request->notes[$attrId] ?? null
                ]
            );
        }

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement updated successfully',
                'measurement' => $measurement
            ]);
        }

        return redirect()->back()->with('success', 'Measurement updated successfully');
    }

    public function destroy(Measurement $measurement)
    {
        $measurement->values()->delete();
        $measurement->delete();

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Measurement deleted successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Measurement deleted successfully');
    }


public function getServiceAttributes(Service $service)
{
    return response()->json(
        $service->measurementAttributes()->select('id','name')->get()
    );
}

    // Get measurements for a specific service (API endpoint)
    public function getServiceMeasurements($serviceId)
    {
        $service = Service::with('measurementAttributes')->find($serviceId);
        
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        return response()->json([
            'success' => true,
            'service' => $service,
            'measurements' => $service->measurementAttributes->map(function($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'service_id' => $attr->service_id
                ];
            })
        ]);
    }

    // Store customer measurements (API endpoint)
    public function storeMeasurements(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'service_id' => 'required|exists:services,id',
            'measurements' => 'required|array',
            'measurements.*.attribute_id' => 'required|exists:measurement_attributes,id',
            'measurements.*.value' => 'required|string',
            'measurements.*.notes' => 'nullable|string'
        ]);

        // First create a Measurement record
        $measurement = Measurement::create([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
        ]);

        $savedMeasurements = [];

        foreach ($request->measurements as $measurementData) {
            $measurementValue = MeasurementValue::create([
                'measurement_id' => $measurement->id,
                'measurement_attribute_id' => $measurementData['attribute_id'],
                'customer_id' => $request->customer_id,
                'value' => $measurementData['value'],
                'notes' => $measurementData['notes'] ?? null
            ]);

            $savedMeasurements[] = $measurementValue->load('measurementAttribute');
        }

        return response()->json([
            'success' => true,
            'message' => 'Measurements saved successfully',
            'measurements' => $savedMeasurements
        ]);
    }

    // Get customer measurements for a service (API endpoint)
    public function getCustomerMeasurements($customerId, $serviceId)
    {
        // Get measurements through the Measurement model
        $measurements = Measurement::with(['values.measurementAttribute'])
            ->where('customer_id', $customerId)
            ->where('service_id', $serviceId)
            ->latest()
            ->first();

        if (!$measurements || $measurements->values->isEmpty()) {
            return response()->json([
                'success' => true,
                'measurements' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'measurements' => $measurements->values->map(function($value) {
                return [
                    'id' => $value->id,
                    'value' => $value->value,
                    'notes' => $value->notes,
                    'measurement_attribute' => [
                        'id' => $value->measurementAttribute->id,
                        'name' => $value->measurementAttribute->name
                    ]
                ];
            })
        ]);
    }

    // Update customer measurements (API endpoint)
    public function updateMeasurements(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'service_id' => 'required|exists:services,id',
            'measurements' => 'required|array',
            'measurements.*.id' => 'required|exists:measurement_values,id',
            'measurements.*.attribute_id' => 'required|exists:measurement_attributes,id',
            'measurements.*.value' => 'required|string',
            'measurements.*.notes' => 'nullable|string'
        ]);

        $updatedMeasurements = [];

        foreach ($request->measurements as $measurementData) {
            $measurementValue = MeasurementValue::find($measurementData['id']);
            
            if ($measurementValue) {
                $measurementValue->update([
                    'value' => $measurementData['value'],
                    'notes' => $measurementData['notes'] ?? null
                ]);
                
                $updatedMeasurements[] = $measurementValue->load('measurementAttribute');
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Measurements updated successfully',
            'measurements' => $updatedMeasurements
        ]);
    }
}