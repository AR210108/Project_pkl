@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Invoice {{ $invoice->invoice_no }}</h1>
            <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
            <div>
                <h3 class="text-lg font-semibold mb-3">Informasi Invoice</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">No Invoice:</span> <span class="font-medium">{{ $invoice->invoice_no }}</span></p>
                    <p><span class="text-gray-600">Tanggal:</span> <span class="font-medium">{{ $invoice->invoice_date?->format('d M Y') ?? '-' }}</span></p>
                    <p><span class="text-gray-600">No Order:</span> <span class="font-medium">{{ $invoice->order_number ?? '-' }}</span></p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3">Informasi Perusahaan</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Nama:</span> <span class="font-medium">{{ $invoice->company_name ?? '-' }}</span></p>
                    <p><span class="text-gray-600">Alamat:</span> <span class="font-medium">{{ $invoice->company_address ?? '-' }}</span></p>
                    <p><span class="text-gray-600">Kontak:</span> <span class="font-medium">{{ $invoice->client_name ?? '-' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        @if($invoice->items && $invoice->items->count() > 0)
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Detail Item</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left text-sm font-semibold">No</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Deskripsi</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Harga</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold">Qty</th>
                            <th class="px-4 py-2 text-right text-sm font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm">{{ $item->item_no ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->description }}</td>
                            <td class="px-4 py-2 text-sm text-right">{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}</td>
                            <td class="px-4 py-2 text-sm text-center">{{ $item->qty ?? 1 }}</td>
                            <td class="px-4 py-2 text-sm text-right font-medium">{{ $item->total ? 'Rp ' . number_format($item->total, 0, ',', '.') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Metode Pembayaran</h3>
                <div class="bg-gray-50 rounded p-3 text-sm">
                    {{ $invoice->payment_method ?? '-' }}
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3">Ringkasan Pembayaran</h3>
                <div class="bg-gray-50 rounded p-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">{{ $invoice->subtotal ? 'Rp ' . number_format($invoice->subtotal, 0, ',', '.') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak:</span>
                        <span class="font-medium">{{ $invoice->tax ? 'Rp ' . number_format($invoice->tax, 0, ',', '.') : '-' }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2 font-semibold text-base">
                        <span>Total:</span>
                        <span>{{ $invoice->total ? 'Rp ' . number_format($invoice->total, 0, ',', '.') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
            <div>
                <h3 class="text-lg font-semibold mb-3">Kategori</h3>
                <p>
                    @if($invoice->category == 'design')
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Desain</span>
                    @elseif($invoice->category == 'programming')
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Programming</span>
                    @elseif($invoice->category == 'marketing')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Digital Marketing</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ $invoice->category ?? '-' }}</span>
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3">Status Pengerjaan</h3>
                <p>
                    @if($invoice->work_status == 'planning')
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Perencanaan</span>
                    @elseif($invoice->work_status == 'progress')
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Sedang Dikerjakan</span>
                    @elseif($invoice->work_status == 'review')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Review</span>
                    @elseif($invoice->work_status == 'completed')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Selesai</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">{{ $invoice->work_status ?? 'Ditunda' }}</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="text-sm text-gray-500 border-t pt-4">
            <p>Dibuat: {{ $invoice->created_at?->format('d M Y H:i') ?? '-' }}</p>
            <p>Diperbarui: {{ $invoice->updated_at?->format('d M Y H:i') ?? '-' }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('orders.index') }}" class="flex-1 text-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali ke Daftar Order
            </a>
        </div>
    </div>
</div>
@endsection
