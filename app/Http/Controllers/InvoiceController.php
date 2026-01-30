<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('Invoice Index accessed', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'guest',
            'request_type' => $request->ajax() ? 'AJAX' : 'WEB',
            'url' => $request->fullUrl()
        ]);

        // Untuk API requests (AJAX) - kembalikan JSON
        if ($request->ajax() || $request->wantsJson() || $request->is('api/*') || $request->expectsJson()) {
            try {
                $query = Invoice::latest();

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('company_name', 'like', "%$search%")
                          ->orWhere('client_name', 'like', "%$search%")
                          ->orWhere('invoice_no', 'like', "%$search%");
                    });
                }

                $perPage = $request->input('per_page', 10);
                $invoices = $query->paginate($perPage);

                return response()->json([
                    'success' => true,
                    'message' => 'Data invoice berhasil diambil',
                    'data' => $invoices->items(),
                    'pagination' => [
                        'total' => $invoices->total(),
                        'per_page' => $invoices->perPage(),
                        'current_page' => $invoices->currentPage(),
                        'last_page' => $invoices->lastPage(),
                    ]
                ]);

            } catch (\Exception $e) {
                Log::error('API Invoice Index Error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data invoice',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Untuk web requests - kembalikan view
        $userRole = auth()->user()->role;
        
        if ($userRole == 'finance') {
            $viewName = 'finance.invoice';
        } else if ($userRole == 'admin') {
            $viewName = 'admin.invoice';
        } else {
            $viewName = 'admin.invoice';
        }

        return view($viewName);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRole = auth()->user()->role;
        
        if ($userRole == 'finance') {
            $viewName = 'finance.invoice_create';
        } else {
            $viewName = 'admin.invoice_create';
        }
        
        return view($viewName);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Store Invoice Request:', $request->all());

        // Validasi
        $validator = Validator::make($request->all(), [
            'invoice_no'      => 'required|string|unique:invoices,invoice_no',
            'invoice_date'    => 'required|date',
            'company_name'    => 'required|string|max:255',
            'company_address' => 'required|string',
            'client_name'     => 'required|string|max:255',
            'payment_method'  => 'required|string',
            'description'     => 'nullable|string',
            'subtotal'        => 'required|integer|min:0',
            'tax'             => 'required|integer|min:0',
            'total'           => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Untuk web form request
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $validated = $validator->validated();
            Log::info('Creating invoice with data:', $validated);
            
            $invoice = Invoice::create($validated);
            Log::info('Invoice created successfully:', ['id' => $invoice->id]);

            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice berhasil dibuat',
                    'data' => $invoice
                ], 201);
            }
            
            // Untuk web form request
            return redirect()->route(auth()->user()->role . '.invoice.index')
                ->with('success', 'Invoice berhasil dibuat');

        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat invoice',
                    'error' => $e->getMessage(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null
                ], 500);
            }
            
            // Untuk web form request
            return redirect()->back()
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $invoice
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing invoice: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Edit endpoint untuk API/AJAX
     */
    public function edit($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'invoice' => $invoice
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error editing invoice: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Update Invoice Request:', [
            'id' => $id,
            'data' => $request->all()
        ]);

        try {
            $invoice = Invoice::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'invoice_no'      => 'required|string|unique:invoices,invoice_no,' . $id,
                'invoice_date'    => 'required|date',
                'company_name'    => 'required|string|max:255',
                'company_address' => 'required|string',
                'client_name'     => 'required|string|max:255',
                'payment_method'  => 'required|string',
                'description'     => 'nullable|string',
                'subtotal'        => 'required|integer|min:0',
                'tax'             => 'required|integer|min:0',
                'total'           => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                Log::error('Update validation failed:', $validator->errors()->toArray());
                
                if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();
            Log::info('Updating invoice with data:', $validated);
            
            $invoice->update($validated);
            Log::info('Invoice updated successfully:', ['id' => $invoice->id]);

            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice berhasil diperbarui',
                    'data' => $invoice
                ]);
            }
            
            return redirect()->route(auth()->user()->role . '.invoice.index')
                ->with('success', 'Invoice berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate invoice',
                    'error' => $e->getMessage(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();
            Log::info('Invoice deleted successfully:', ['id' => $id]);

            if (request()->ajax() || request()->wantsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice berhasil dihapus'
                ]);
            }
            
            return redirect()->route(auth()->user()->role . '.invoice.index')
                ->with('success', 'Invoice berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Error deleting invoice: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson() || request()->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus invoice',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice view
     */
    public function print($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            return view('invoice.print', compact('invoice'));
            
        } catch (\Exception $e) {
            Log::error('Error printing invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Invoice tidak ditemukan');
        }
    }

    /**
     * Get statistics for dashboard (API only)
     */
    public function statistics(): JsonResponse
    {
        try {
            $data = [
                'total_invoices' => Invoice::count(),
                'total_revenue' => Invoice::sum('total'),
                'average_invoice' => Invoice::avg('total'),
                'payment_methods' => Invoice::select(
                        'payment_method',
                        DB::raw('count(*) as count'),
                        DB::raw('sum(total) as total')
                    )
                    ->groupBy('payment_method')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint khusus untuk AJAX requests (alternatif)
     */
    public function getData(Request $request)
    {
        try {
            $query = Invoice::latest();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%$search%")
                      ->orWhere('client_name', 'like', "%$search%")
                      ->orWhere('invoice_no', 'like', "%$search%");
                });
            }

            $perPage = $request->input('per_page', 10);
            $invoices = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $invoices->items(),
                'pagination' => [
                    'total' => $invoices->total(),
                    'per_page' => $invoices->perPage(),
                    'current_page' => $invoices->currentPage(),
                    'last_page' => $invoices->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting invoice data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}