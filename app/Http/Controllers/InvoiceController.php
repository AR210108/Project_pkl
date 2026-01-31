<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        Log::info('Invoice Index accessed', [
            'user_id' => $userId,
            'user_role' => $user ? $user->role : 'guest',
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
                        'from' => $invoices->firstItem(),
                        'to' => $invoices->lastItem()
                    ]
                ]);

            } catch (\Exception $e) {
                Log::error('API Invoice Index Error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data invoice',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
        }

        // Untuk web requests - kembalikan view
        if ($user && $user->role == 'finance') {
            $viewName = 'finance.invoice';
        } else if ($user && $user->role == 'admin') {
            $viewName = 'admin.invoice';
        } else {
            $viewName = 'admin.invoice'; // Default
        }

        return view($viewName);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user && $user->role == 'finance') {
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
            'subtotal'        => 'required|numeric|min:0',
            'tax'             => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Untuk web form request
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $validated = $validator->validated();
            
            // Generate invoice number if not provided
            if (empty($validated['invoice_no'])) {
                $validated['invoice_no'] = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
            
            // Convert numeric values
            $validated['subtotal'] = (float) $validated['subtotal'];
            $validated['tax'] = (float) $validated['tax'];
            $validated['total'] = (float) $validated['total'];
            
            Log::info('Creating invoice with data:', $validated);
            
            $invoice = Invoice::create($validated);
            Log::info('Invoice created successfully:', ['id' => $invoice->id]);

            DB::commit();

            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice berhasil dibuat',
                    'data' => $invoice
                ], 201);
            }
            
            // Untuk web form request
            $user = Auth::user();
            return redirect()->route($user->role . '.invoice.index')
                ->with('success', 'Invoice berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating invoice: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Untuk API/AJAX request
            if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat invoice',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
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
                'message' => 'Data invoice berhasil diambil',
                'data' => $invoice
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing invoice: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan',
                'error' => config('app.debug') ? $e->getMessage() : 'Not found'
            ], 404);
        }
    }

    /**
     * Mark invoice as printed
     */
    public function markPrinted(Request $request, $id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice tidak ditemukan'
                ], 404);
            }

            // Update printed status
            $invoice->update([
                'is_printed' => true,
                'printed_at' => now(),
                'printed_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice ditandai sebagai tercetak',
                'data' => $invoice
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking invoice as printed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            // Untuk API request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data invoice berhasil diambil untuk diedit',
                    'data' => $invoice
                ]);
            }
            
            // Untuk web request
            $user = Auth::user();
            if ($user && $user->role == 'finance') {
                $viewName = 'finance.invoice_edit';
            } else {
                $viewName = 'admin.invoice_edit';
            }
            
            return view($viewName, compact('invoice'));
            
        } catch (\Exception $e) {
            Log::error('Error editing invoice: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice tidak ditemukan',
                    'error' => config('app.debug') ? $e->getMessage() : 'Not found'
                ], 404);
            }
            
            return redirect()->back()
                ->with('error', 'Invoice tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            
            Log::info('Update Invoice Request:', [
                'id' => $id,
                'data' => $request->all()
            ]);

            $validator = Validator::make($request->all(), [
                'invoice_no'      => 'required|string|unique:invoices,invoice_no,' . $id,
                'invoice_date'    => 'required|date',
                'company_name'    => 'required|string|max:255',
                'company_address' => 'required|string',
                'client_name'     => 'required|string|max:255',
                'payment_method'  => 'required|string',
                'description'     => 'nullable|string',
                'subtotal'        => 'required|numeric|min:0',
                'tax'             => 'required|numeric|min:0',
                'total'           => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            $validated = $validator->validated();
            
            // Convert numeric values
            $validated['subtotal'] = (float) $validated['subtotal'];
            $validated['tax'] = (float) $validated['tax'];
            $validated['total'] = (float) $validated['total'];
            
            $invoice->update($validated);
            DB::commit();
            
            Log::info('Invoice updated successfully:', ['id' => $invoice->id]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil diperbarui',
                'data' => $invoice
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating invoice: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);
            
            // Log sebelum menghapus
            Log::info('Deleting invoice:', [
                'id' => $id,
                'invoice_no' => $invoice->invoice_no,
                'deleted_by' => Auth::id()
            ]);
            
            $invoice->delete();
            DB::commit();
            
            Log::info('Invoice deleted successfully:', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dihapus'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard (API only)
     */
    public function statistics(): JsonResponse
    {
        try {
            // Total invoices count
            $totalInvoices = Invoice::count();
            
            // Total revenue
            $totalRevenue = Invoice::sum('total');
            
            // Average invoice value
            $averageInvoice = $totalInvoices > 0 ? round($totalRevenue / $totalInvoices, 2) : 0;
            
            // Payment methods statistics
            $paymentMethods = Invoice::select(
                    'payment_method',
                    DB::raw('count(*) as count'),
                    DB::raw('sum(total) as total_amount')
                )
                ->groupBy('payment_method')
                ->get();
            
            // Monthly statistics for current year
            $currentYear = date('Y');
            $monthlyStats = Invoice::select(
                    DB::raw('MONTH(invoice_date) as month'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total) as total')
                )
                ->whereYear('invoice_date', $currentYear)
                ->groupBy(DB::raw('MONTH(invoice_date)'))
                ->orderBy('month')
                ->get();
            
            // Recent invoices
            $recentInvoices = Invoice::latest()
                ->limit(5)
                ->get(['id', 'invoice_no', 'client_name', 'total', 'invoice_date', 'payment_method']);
            
            $data = [
                'total_invoices' => $totalInvoices,
                'total_revenue' => (float) $totalRevenue,
                'average_invoice' => (float) $averageInvoice,
                'payment_methods' => $paymentMethods,
                'monthly_stats' => $monthlyStats,
                'recent_invoices' => $recentInvoices,
                'current_year' => $currentYear
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistik berhasil diambil',
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Export invoices to PDF/Excel
     */
    public function export(Request $request)
    {
        try {
            $query = Invoice::query();
            
            // Apply filters if any
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('invoice_date', [
                    $request->start_date,
                    $request->end_date
                ]);
            }
            
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            
            $invoices = $query->orderBy('invoice_date', 'desc')->get();
            
            $format = $request->get('format', 'json');
            
            if ($format === 'pdf') {
                // Return PDF export (implement PDF generation logic here)
                return response()->json([
                    'success' => true,
                    'message' => 'PDF export belum diimplementasikan',
                    'data' => $invoices
                ]);
            } else if ($format === 'excel') {
                // Return Excel export (implement Excel generation logic here)
                return response()->json([
                    'success' => true,
                    'message' => 'Excel export belum diimplementasikan',
                    'data' => $invoices
                ]);
            }
            
            // Default return JSON
            return response()->json([
                'success' => true,
                'message' => 'Data invoice berhasil diekspor',
                'data' => $invoices,
                'total' => $invoices->count(),
                'total_amount' => $invoices->sum('total')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error exporting invoices: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber(Request $request)
    {
        try {
            $prefix = 'INV';
            $date = date('Ymd');
            
            // Count invoices for today
            $countToday = Invoice::whereDate('created_at', today())->count();
            $nextNumber = $countToday + 1;
            
            // Format: INV-20240130-001
            $invoiceNo = $prefix . '-' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            
            // Check if exists (just in case)
            while (Invoice::where('invoice_no', $invoiceNo)->exists()) {
                $nextNumber++;
                $invoiceNo = $prefix . '-' . $date . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
            
            return response()->json([
                'success' => true,
                'invoice_no' => $invoiceNo
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating invoice number: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate nomor invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}