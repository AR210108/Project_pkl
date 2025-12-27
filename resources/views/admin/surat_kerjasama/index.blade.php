<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Kerjasama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom styles untuk memastikan tampilan konsisten */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card-image {
            height: 200px;
            object-fit: cover;
            filter: grayscale(100%);
        }

        .monochrome-hover {
            transition: all 0.3s ease;
        }

        .monochrome-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-minimal {
            transition: all 0.2s ease;
        }

        .btn-minimal:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 640px) {
            .card-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="bg-white min-h-screen text-gray-900">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        @include('admin.templet.sider')

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto">
            <div class="container mx-auto py-8 px-6">

                <!-- Header Section -->
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h1 class="text-3xl font-light tracking-tight">
                            Daftar Surat Kerjasama
                        </h1>
                        <a href="{{ route('admin.surat_kerjasama.create') }}"
                            class="btn-minimal bg-black text-white px-6 py-2.5 rounded-none text-sm font-medium tracking-wide">
                            + Buat Surat Baru
                        </a>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="bg-gray-100 border-l-4 border-gray-800 p-4 mb-6">
                        <p class="font-medium text-gray-800">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Cards Grid -->
                <div class="card-container">
                    @foreach ($surat as $item)
                        <div class="monochrome-hover bg-white border border-gray-200 overflow-hidden">
                            <!-- Card Image -->
                            <div class="relative h-48 bg-gray-100">
                                @if ($item->preview_image)
                                    <img src="{{ asset('storage/' . $item->preview_image) }}" class="card-image w-full"
                                        alt="{{ $item->judul }}">
                                @endif
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                </div>

                <!-- Card Content -->
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-3 truncate">{{ $item->judul }}</h3>
                    <div class="space-y-2 mb-5 text-sm">
                        <p class="text-gray-600">
                            <span class="font-medium">Nomor:</span> {{ $item->nomor_surat }}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-medium">Tanggal:</span>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.surat_kerjasama.show', $item->id) }}"
                            class="btn-minimal flex-1 bg-gray-900 text-white py-2 text-sm font-medium text-center">
                            Detail
                        </a>
                        <a href="{{ route('admin.surat_kerjasama.edit', $item->id) }}"
                            class="btn-minimal flex-1 border border-gray-900 text-gray-900 py-2 text-sm font-medium text-center hover:bg-gray-900 hover:text-white">
                            Edit
                        </a>
                        <form action="{{ route('admin.surat_kerjasama.destroy', $item->id) }}" method="POST"
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?')"
                                class="btn-minimal w-full border border-gray-300 text-gray-700 py-2 text-sm font-medium hover:border-gray-700 hover:text-gray-900">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
    </div>

    <!-- Empty State -->
    @if ($surat->isEmpty())
        <div class="bg-white border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada surat kerjasama</h3>
            <p class="text-gray-500 mb-6">Mulai dengan membuat surat kerjasama pertama Anda.</p>
            <a href="{{ route('admin.surat_kerjasama.create') }}"
                class="btn-minimal bg-black text-white px-6 py-2.5 rounded-none text-sm font-medium tracking-wide">
                Buat Surat Baru
            </a>
        </div>
    @endif
    </div>
    </main>
    </div>
</body>

</html>
