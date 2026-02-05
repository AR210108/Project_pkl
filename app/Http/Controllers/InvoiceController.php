<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Layanan;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

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
                            ->orWhere('invoice_no', 'like', "%$search%")
                            ->orWhere('nama_layanan', 'like', "%$search%"); // Tambah pencarian nama layanan
                    });
                }

                // Filter berdasarkan status pembayaran
                if ($request->filled('status_pembayaran')) {
                    $query->where('status_pembayaran', $request->status_pembayaran);
                }

                // Filter berdasarkan nama layanan
                if ($request->filled('nama_layanan')) {
                    $query->where('nama_layanan', $request->nama_layanan);
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

    try {
        // Ambil data layanan untuk dropdown
        $layanan = Layanan::orderBy('nama_layanan', 'asc')
            ->get(['id', 'nama_layanan', 'harga', 'deskripsi']);

        // Ambil data perusahaan untuk dropdown
        $perusahaanList = Perusahaan::orderBy('nama_perusahaan', 'asc')
            ->get(['id', 'nama_perusahaan', 'klien', 'alamat', 'jumlah_kerjasama']);

        if ($user && $user->role == 'finance') {
            $viewName = 'finance.invoice_create';
        } else {
            $viewName = 'admin.invoice_create';
        }

        return view($viewName, compact('layanan', 'perusahaanList'));
    } catch (\Exception $e) {
        Log::error('Error getting data for create form: ' . $e->getMessage());

        $layanan = collect();
        $perusahaanList = collect();

        if ($user && $user->role == 'finance') {
            $viewName = 'finance.invoice_create';
        } else {
            $viewName = 'admin.invoice_create';
        }

        return view($viewName, compact('layanan', 'perusahaanList'));
    }
}


public function store(Request $request)
{
    Log::info('Store Invoice Request:', $request->all());

    // Validasi dengan field baru
    $validator = Validator::make($request->all(), [
        'invoice_no' => 'required|string|unique:invoices,invoice_no',
        'invoice_date' => 'required|date',
        'company_name' => 'required|string|max:255',
        'company_address' => 'required|string',
        'client_name' => 'required|string|max:255',
        'nama_layanan' => 'required|string|max:255',
        'status_pembayaran' => 'required|in:pembayaran awal,lunas',
        'payment_method' => 'required|string',
        'description' => 'nullable|string',
        'subtotal' => 'required|numeric|min:0',
        'tax' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'order_number' => 'nullable|string', // Tambahkan ini
    ]);

    if ($validator->fails()) {
        Log::error('Validation failed:', $validator->errors()->toArray());

        if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

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

        // Simpan jumlah kerjasama dari perusahaan
        $perusahaan = Perusahaan::where('nama_perusahaan', $validated['company_name'])->first();
        if ($perusahaan) {
            // Update jumlah kerjasama perusahaan
            $perusahaan->update([
                'jumlah_kerjasama' => ($perusahaan->jumlah_kerjasama + 1)
            ]);
        }

        Log::info('Creating invoice with data:', $validated);

        $invoice = Invoice::create($validated);
        Log::info('Invoice created successfully:', ['id' => $invoice->id]);

        // The Invoice model's created event will create the Order. Try to fetch it for logging.
        try {
            $order = Order::where('invoice_id', $invoice->id)->first();
            if ($order) {
                Log::info('Order exists for invoice', ['order_id' => $order->id, 'invoice_id' => $invoice->id]);
            } else {
                Log::warning('Order not found immediately after invoice creation', ['invoice_id' => $invoice->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed while checking for order after invoice creation: ' . $e->getMessage());
        }

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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            // Ambil data layanan untuk dropdown
            $layanan = Layanan::orderBy('nama_layanan', 'asc')
                ->get(['id', 'nama_layanan', 'harga']);

            // Untuk API request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data invoice berhasil diambil untuk diedit',
                    'data' => $invoice,
                    'layanan' => $layanan
                ]);
            }

            // Untuk web request
            $user = Auth::user();
            if ($user && $user->role == 'finance') {
                $viewName = 'finance.invoice_edit';
            } else {
                $viewName = 'admin.invoice_edit';
            }

            return view($viewName, compact('invoice', 'layanan'));
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
                'invoice_no' => 'required|string|unique:invoices,invoice_no,' . $id,
                'invoice_date' => 'required|date',
                'company_name' => 'required|string|max:255',
                'company_address' => 'required|string',
                'client_name' => 'required|string|max:255',
                'nama_layanan' => 'required|string|max:255', // Field baru
                'status_pembayaran' => 'required|in:pembayaran awal,lunas', // Field baru
                'payment_method' => 'required|string',
                'description' => 'nullable|string',
                'subtotal' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
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
     * Get all unique layanan names from invoices (for filter dropdown)
     */
    public function getLayananList(Request $request)
    {
        try {
            $layananList = Invoice::select('nama_layanan')
                ->distinct()
                ->whereNotNull('nama_layanan')
                ->orderBy('nama_layanan', 'asc')
                ->pluck('nama_layanan');

            return response()->json([
                'success' => true,
                'message' => 'List layanan dari invoice berhasil diambil',
                'data' => $layananList
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting invoice layanan list: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil list layanan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get status pembayaran list (for filter dropdown)
     */
    public function getStatusPembayaranList(Request $request)
    {
        try {
            $statusList = Invoice::select('status_pembayaran')
                ->distinct()
                ->whereNotNull('status_pembayaran')
                ->orderBy('status_pembayaran', 'asc')
                ->pluck('status_pembayaran');

            return response()->json([
                'success' => true,
                'message' => 'List status pembayaran berhasil diambil',
                'data' => $statusList
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting status pembayaran list: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil list status pembayaran',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get data layanan from Layanan model (for dropdown)
     */
    public function getLayananData(Request $request)
    {
        try {
            $layanan = Layanan::orderBy('nama_layanan', 'asc')
                ->get(['id', 'nama_layanan', 'harga', 'deskripsi']);

            return response()->json([
                'success' => true,
                'message' => 'Data layanan berhasil diambil',
                'data' => $layanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting layanan data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data layanan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Print invoice
     */
    public function print($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            // Jika request AJAX, kembalikan data JSON
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data invoice untuk print berhasil diambil',
                    'data' => $invoice
                ]);
            }

            // Untuk web, kembalikan view print
            return view('admin.invoice_print', compact('invoice'));
        } catch (\Exception $e) {
            Log::error('Error printing invoice: ' . $e->getMessage());

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
     * Get finance invoices
     */
    public function getFinanceInvoices()
    {
        try {
            $invoices = Invoice::with(['client', 'layanan'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data invoice berhasil diambil',
                'data' => $invoices
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching finance invoices: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get invoices for kwitansi
     */
    // InvoiceController.php - Method getInvoicesForKwitansi
        public function getInvoicesForKwitansi(Request $request)
    {
        try {
            Log::info('Admin API: getInvoicesForKwitansi called', [
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'guest'
            ]);
            
            // Query semua invoice (bisa difilter nanti)
            $query = Invoice::select('invoices.*')
                ->orderBy('created_at', 'desc');
            
            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('client_name', 'like', "%{$search}%")
                        ->orWhere('invoice_no', 'like', "%{$search}%")
                        ->orWhere('order_number', 'like', "%{$search}%");
                });
            }
            
            // Filter hanya invoice yang belum memiliki kwitansi (opsional)
            if ($request->input('unpaid_only', false)) {
                $query->whereDoesntHave('kwitansi'); // Asumsi ada relationship
            }
            
            $invoices = $query->get()->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'order_number' => $invoice->order_number,
                    'company_name' => $invoice->company_name,
                    'client_name' => $invoice->client_name,
                    'description' => $invoice->description,
                    'total' => $invoice->total,
                    'invoice_date' => $invoice->invoice_date,
                    'status_pembayaran' => $invoice->status_pembayaran,
                    'nama_layanan' => $invoice->nama_layanan,
                    'company_address' => $invoice->company_address,
                    'payment_method' => $invoice->payment_method,
                    'subtotal' => $invoice->subtotal,
                    'tax' => $invoice->tax,
                ];
            });
            
            Log::info('Admin API: Invoices data returned', ['count' => $invoices->count()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data invoice untuk kwitansi berhasil diambil',
                'data' => $invoices,
                'total' => $invoices->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getInvoicesForKwitansi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data invoice untuk kwitansi',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // Method getInvoiceDetailForKwitansi - PERBAIKAN
     public function getInvoiceDetailForKwitansi($id)
    {
        try {
            Log::info('Admin API: getInvoiceDetailForKwitansi called', [
                'invoice_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            $invoice = Invoice::findOrFail($id);
            
            // Format data untuk form kwitansi
            $invoiceData = [
                'id' => $invoice->id,
                'invoice_no' => $invoice->invoice_no,
                'order_number' => $invoice->order_number,
                'company_name' => $invoice->company_name,
                'client_name' => $invoice->client_name,
                'description' => $invoice->description,
                'nama_layanan' => $invoice->nama_layanan,
                'total' => $invoice->total,
                'subtotal' => $invoice->subtotal,
                'tax' => $invoice->tax,
                'invoice_date' => $invoice->invoice_date,
                'status_pembayaran' => $invoice->status_pembayaran,
                'company_address' => $invoice->company_address,
                'payment_method' => $invoice->payment_method,
            ];
            
            Log::info('Admin API: Invoice detail returned', ['invoice_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data invoice berhasil diambil',
                'data' => $invoiceData
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Admin API: Invoice not found: ' . $id);
            
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error in getInvoiceDetailForKwitansi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    /**
     * Get all invoices (API) - untuk kwitansi dropdown
     */
  public function getAllInvoicesApi(Request $request)
    {
        try {
            $query = Invoice::orderBy('created_at', 'desc');
            
            // Filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('client_name', 'like', "%{$search}%")
                        ->orWhere('invoice_no', 'like', "%{$search}%");
                });
            }
            
            $invoices = $query->get(['id', 'invoice_no', 'company_name', 'client_name', 'order_number', 'total', 'status_pembayaran']);
            
            return response()->json([
                'success' => true,
                'message' => 'Data semua invoice berhasil diambil',
                'data' => $invoices
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getAllInvoicesApi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data invoice',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
 * Create project from invoice manually
 */
public function createProjectFromInvoice($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        // Cek apakah sudah ada project
        if ($invoice->hasProject()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice sudah memiliki project',
                'data' => [
                    'invoice' => $invoice,
                    'project' => $invoice->project,
                    'project_link' => $invoice->project_link
                ]
            ]);
        }
        
        // Buat project dari invoice
        $project = $invoice->createProjectFromInvoice();
        
        if ($project) {
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dibuat dari invoice',
                'data' => [
                    'invoice' => $invoice,
                    'project' => $project,
                    'project_link' => $invoice->project_link
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat project dari invoice'
            ], 500);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error creating project from invoice: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat project: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Sync project with invoice
 */
public function syncProjectWithInvoice($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        if (!$invoice->hasProject()) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice belum memiliki project'
            ], 404);
        }
        
        $result = $invoice->syncProjectWithInvoice();
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Project berhasil disinkronisasi dengan invoice',
                'data' => [
                    'invoice' => $invoice,
                    'project' => $invoice->project
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyinkronisasi project'
            ], 500);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error syncing project with invoice: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Get project info for invoice
 */
public function getInvoiceProjectInfo($id)
{
    try {
        $invoice = Invoice::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi project berhasil diambil',
            'data' => [
                'has_project' => $invoice->hasProject(),
                'project' => $invoice->project,
                'project_status' => $invoice->project_status,
                'project_link' => $invoice->project_link
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error getting invoice project info: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil informasi project: ' . $e->getMessage()
        ], 500);
    }
}
}
