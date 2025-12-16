<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::latest();

        // Fitur pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_perusahaan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nomor_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nama_klien', 'LIKE', "%{$searchTerm}%");
            });
        }

        $invoices = $query->paginate(10);

        // Jika request adalah AJAX, kembalikan JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $invoices->items(),
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'per_page' => $invoices->perPage(),
                'total' => $invoices->total(),
                'prev_page_url' => $invoices->previousPageUrl(),
                'next_page_url' => $invoices->nextPageUrl(),
            ]);
        }

        return view('admin.invoice', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_order' => 'required|string|unique:invoices,nomor_order',
            'nama_perusahaan' => 'nullable|string|max:255',
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'detail_layanan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'nullable|integer|min:1',
            'pajak' => 'nullable|numeric|min:0|max:100',
            'metode_pembayaran' => 'required|string|in:Bank Transfer,E-Wallet,Credit Card,Cash',
            'tanggal' => 'nullable|date',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Siapkan data
        $data = $validator->validated();
        
        // Set default values
        $qty = isset($data['qty']) ? (int)$data['qty'] : 1;
        $pajak = isset($data['pajak']) ? (float)$data['pajak'] : 0.0;

        // Hitung total (harga * qty + pajak)
        $subtotal = $data['harga'] * $qty;
        $data['qty'] = $qty;
        $data['pajak'] = $pajak;
        $data['total'] = $subtotal + ($subtotal * $pajak / 100);

        // Buat invoice
        $invoice = Invoice::create($data);

        // Jika request adalah AJAX, kembalikan sukses JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dibuat!',
                'data' => $invoice,
            ], 201);
        }

        return redirect()->route('admin.invoice.index')
            ->with('success', 'Invoice berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $invoice = Invoice::with('kwitansis')->findOrFail($id);

        // Jika request adalah AJAX, kembalikan JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $invoice,
            ]);
        }

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'nomor_order' => 'required|string|unique:invoices,nomor_order,' . $id,
            'nama_perusahaan' => 'nullable|string|max:255',
            'nama_klien' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'detail_layanan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'nullable|integer|min:1',
            'pajak' => 'nullable|numeric|min:0|max:100',
            'metode_pembayaran' => 'required|string|in:Bank Transfer,E-Wallet,Credit Card,Cash',
            'tanggal' => 'nullable|date',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Siapkan data
        $data = $validator->validated();
        
        // Set default values
        $qty = isset($data['qty']) ? (int)$data['qty'] : 1;
        $pajak = isset($data['pajak']) ? (float)$data['pajak'] : 0.0;

        // Hitung total
        $subtotal = $data['harga'] * $qty;
        $data['qty'] = $qty;
        $data['pajak'] = $pajak;
        $data['total'] = $subtotal + ($subtotal * $pajak / 100);

        // Update invoice
        $invoice->update($data);

        // Jika request adalah AJAX, kembalikan sukses JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil diperbarui!',
                'data' => $invoice,
            ]);
        }

        return redirect()->route('admin.invoice.index')
            ->with('success', 'Invoice berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        // Jika request adalah AJAX, kembalikan sukses JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice berhasil dihapus!',
            ]);
        }

        return redirect()->route('admin.invoice.index')
            ->with('success', 'Invoice berhasil dihapus!');
    }

    /**
     * Get statistics for invoices
     */
    public function statistics(): JsonResponse
    {
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::sum('total');
        $averageInvoice = Invoice::avg('total');

        // Group by payment method
        $paymentMethods = Invoice::select('metode_pembayaran', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
            ->groupBy('metode_pembayaran')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_invoices' => $totalInvoices,
                'total_revenue' => $totalRevenue,
                'average_invoice' => $averageInvoice,
                'payment_methods' => $paymentMethods,
            ],
        ]);
    }
}