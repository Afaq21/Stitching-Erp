<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search and pagination
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 12 customers per page (4 columns × 3 rows in grid)
        $customers = $query->latest()->paginate(12);

        // Search parameter pagination links mein preserve ho jayega automatically
        $customers->appends($request->only('search'));

        return view('Dashboard.customers.index', compact('customers'));
    }

    /**
     * Store a newly created customer (AJAX only - JSON response)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20|unique:customers,phone',
                'gender'  => 'required|in:male,female,other',
                'address' => 'nullable|string|max:500',
            ]);

            $customer = Customer::create($request->all());

            return response()->json([
                'success'  => true,
                'message'  => 'Customer added successfully',
                'customer' => $customer
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return customer data for edit (AJAX - JSON response)
     */
    public function edit(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified customer (AJAX only - JSON response)
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20|unique:customers,phone,' . $customer->id,
                'gender'  => 'required|in:male,female,other',
                'address' => 'nullable|string|max:500',
            ]);

            $customer->update($request->all());

            return response()->json([
                'success'  => true,
                'message'  => 'Customer updated successfully',
                'customer' => $customer
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer (AJAX only - JSON response)
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting customer: ' . $e->getMessage()
            ], 500);
        }
    }
}
