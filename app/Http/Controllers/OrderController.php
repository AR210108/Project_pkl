<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Invoice;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $q = $request->input('q');

        $query = Order::with('invoice');

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('layanan', 'like', "%{$q}%")
                    ->orWhere('klien', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('finance.data_orderan', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('invoice.items')->findOrFail($id);
        return view('finance.order_show', compact('order'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_no' => 'nullable|string',
            'layanan' => 'required|string',
            'kategori' => 'nullable|string',
            'price' => 'nullable|numeric',
            'klien' => 'nullable|string',
            'deposit' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'status' => 'nullable|in:paid,partial,pending,overdue',
            'work_status' => 'nullable|in:planning,progress,review,completed,onhold',
            'invoice_id' => 'nullable|integer|exists:invoices,id',
        ]);

        $order = Order::create($data);

        return redirect()->route('orders.show', $order->id);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'layanan' => 'sometimes|required|string',
            'kategori' => 'nullable|string',
            'price' => 'nullable|numeric',
            'klien' => 'nullable|string',
            'deposit' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'status' => 'nullable|in:paid,partial,pending,overdue',
            'work_status' => 'nullable|in:planning,progress,review,completed,onhold',
            'invoice_id' => 'nullable|integer|exists:invoices,id',
        ]);

        $order->update($data);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index');
    }
}
