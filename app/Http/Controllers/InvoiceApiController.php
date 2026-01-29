<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceApiController extends Controller
{
    // =========================
    // GET /api/invoices
    // =========================
    public function index()
    {
        $invoices = Invoice::with(['items', 'orders'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    // =========================
    // POST /api/invoices
    // =========================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_no'       => 'required|string|unique:invoices,invoice_no',
            'invoice_date'     => 'required|date',

            'company_name'     => 'required|string|max:255',
            'company_address'  => 'required|string',

            'client_name'      => 'required|string|max:255',
            'order_number'     => 'nullable|string|max:255',

            'payment_method'   => 'required|string|max:100',
            'category'         => 'required|string|max:100',
          

            'subtotal'         => 'required|integer|min:0',
            'tax'              => 'required|integer|min:0',
            'total'            => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $invoice = Invoice::create($request->only([
            'invoice_no',
            'invoice_date',
            'company_name',
            'company_address',
            'client_name',
            'order_number',
            'payment_method',
            'category',
            
            'subtotal',
            'tax',
            'total',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dibuat',
            'data' => $invoice
        ], 201);
    }

    // =========================
    // GET /api/invoices/{id}
    // =========================
    public function show($id)
    {
        $invoice = Invoice::with(['items', 'orders'])->find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }

    // =========================
    // PUT /api/invoices/{id}
    // =========================
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'invoice_no'       => 'required|string|unique:invoices,invoice_no,' . $id,
            'invoice_date'     => 'required|date',

            'company_name'     => 'required|string|max:255',
            'company_address'  => 'required|string',

            'client_name'      => 'required|string|max:255',
            'order_number'     => 'nullable|string|max:255',

            'payment_method'   => 'required|string|max:100',
            'category'         => 'required|string|max:100',
          

            'subtotal'         => 'required|integer|min:0',
            'tax'              => 'required|integer|min:0',
            'total'            => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $invoice->update($request->only([
            'invoice_no',
            'invoice_date',
            'company_name',
            'company_address',
            'client_name',
            'order_number',
            'payment_method',
            'category',
          
            'subtotal',
            'tax',
            'total',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil diperbarui',
            'data' => $invoice
        ]);
    }

    // =========================
    // DELETE /api/invoices/{id}
    // =========================
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
        }

        $invoice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dihapus'
        ]);
    }
}
