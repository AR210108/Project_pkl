@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Detail Order #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Order Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Informasi Order</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nomor Order</p>
                        <p class="font-medium">{{ $order->order_no ?? $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Layanan</p>
                        <p class="font-medium">{{ $order->layanan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="font-medium">
                            @if($order->kategori == 'design')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">Desain</span>
                            @elseif($order->kategori == 'programming')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Programming</span>
                            @elseif($order->kategori == 'marketing')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Digital Marketing</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Klien</p>
                        <p class="font-medium">{{ $order->klien }}</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Informasi Harga</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Harga Total</p>
                        <p class="font-medium text-lg">{{ $order->price_formatted ?? ($order->price ? 'Rp ' . number_format($order->price, 0, ',', '.') : '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pembayaran Awal</p>
                        <p class="font-medium">{{ $order->deposit ? 'Rp ' . number_format($order->deposit, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pelunasan</p>
                        <p class="font-medium">{{ $order->paid ? 'Rp ' . number_format($order->paid, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sisa Pembayaran</p>
                        <p class="font-medium">
                            @php
                                $sisa = ($order->price ?? 0) - ($order->paid ?? 0);
                            @endphp
                            {{ $sisa > 0 ? 'Rp ' . number_format($sisa, 0, ',', '.') : 'Lunas' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Status Pembayaran</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p>
                            @if($order->status == 'paid')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Lunas</span>
                            @elseif($order->status == 'partial')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Sebagian</span>
                            @elseif($order->status == 'overdue')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Terlambat</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Pending</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Work Status Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Status Pengerjaan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Progress</p>
                        <p>
                            @if($order->work_status == 'planning')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Perencanaan</span>
                            @elseif($order->work_status == 'progress')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">Sedang Dikerjakan</span>
                            @elseif($order->work_status == 'review')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Review</span>
                            @elseif($order->work_status == 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Selesai</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Ditunda</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        @if($order->invoice_id)
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4">Invoice Terkait</h3>
            <div class="flex justify-between items-center">
                <p>Invoice #{{ $order->invoice_id }}</p>
                <a href="{{ route('invoices.show', $order->invoice_id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Lihat Invoice
                </a>
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="mt-6 text-sm text-gray-500 border-t pt-4">
            <p>Dibuat: {{ $order->created_at?->format('d M Y H:i') ?? '-' }}</p>
            <p>Diperbarui: {{ $order->updated_at?->format('d M Y H:i') ?? '-' }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('orders.index') }}" class="flex-1 text-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
