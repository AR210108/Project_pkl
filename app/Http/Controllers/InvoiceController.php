<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // =========================
    // INDEX (ADMIN)
    // =========================
    public function index(Request $request)
    {
        $query = Invoice::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%$search%")
                  ->orWhere('client_name', 'like', "%$search%")
                  ->orWhere('invoice_no', 'like', "%$search%");
            });
        }

        $invoices = $query->paginate(10);

        if ($request->ajax()) {
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
        }

        return view('admin.invoice', compact('invoices'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.invoice_create');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'invoice_no'      => 'required|string|unique:invoices,invoice_no',
        'invoice_date'    => 'required|date',
        'company_name'    => 'required|string|max:255',
        'company_address' => 'required|string',
        'client_name'     => 'required|string|max:255',
        'order_number'    => 'nullable|string|max:255',
        'payment_method'  => 'required|string',
        'description'     => 'nullable|string', // TAMBAH ini
        'subtotal'        => 'required|integer|min:0',
        'tax'             => 'required|integer|min:0',
        'total'           => 'required|integer|min:0',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $invoice = Invoice::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dibuat',
            'data' => $invoice
        ]);
    }

    // =========================
    // SHOW
    // =========================
    // InvoiceController.php
public function show($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Invoice tidak ditemukan'
        ], 404);
    }
}

    // =========================
    // EDIT
    // =========================
   // =========================
// EDIT
// =========================
public function edit($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'invoice' => $invoice
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Invoice tidak ditemukan',
            'error' => $e->getMessage()
        ], 404);
    }
}

    // =========================
    // UPDATE
    // =========================
  // InvoiceController.php - method update()
public function update(Request $request, $id)
{
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
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $invoice->update($validator->validated());

    return response()->json([
        'success' => true,
        'message' => 'Invoice berhasil diperbarui',
        'data' => $invoice
    ]);
}       

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dihapus'
        ]);
    }

    // =========================
    // STATISTICS
    // =========================
    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
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
            ]
        ]);
    }
}
