<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Surat Kerjasama</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts untuk tipografi yang lebih baik -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    
    <!-- Ikon (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom styles untuk input minimalis */
        .minimal-input {
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .minimal-input:focus {
            border-color: #000;
            outline: none;
            box-shadow: none;
        }
        
        /* Style untuk CKEditor agar sesuai tema */
        .ck-editor__editable {
            min-height: 200px;
        }
        
        /* Canvas untuk tanda tangan */
        #signature-pad {
            border: 1px solid #e5e7eb;
            cursor: crosshair;
        }
    </style>
</head>
<body class="bg-white min-h-screen">

<div class="max-w-5xl mx-auto bg-white">
    <!-- Header Form -->
    <div class="border-b border-gray-200 px-8 py-6">
        <h1 class="text-3xl font-light tracking-tight text-gray-900 flex items-center">
            <i class="fas fa-file-circle-plus mr-3 text-gray-700"></i>
            Buat Surat Kerjasama Baru
        </h1>
        <p class="text-gray-600 mt-2">Isi formulir di bawah ini untuk membuat surat kerjasama baru.</p>
    </div>

    <!-- Body Form -->
    <form method="POST" action="{{ route('admin.surat_kerjasama.store') }}" class="p-8">
        @csrf

        <!-- Grid untuk 2 kolom pada layar besar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-900 mb-2">
                    Judul Surat
                </label>
                <input type="text" id="judul" name="judul"
                       class="w-full px-4 py-2 minimal-input rounded" required>
            </div>

            <!-- Nomor Surat -->
            <div>
                <label for="nomor_surat" class="block text-sm font-medium text-gray-900 mb-2">
                    Nomor Surat
                </label>
                <input type="text" id="nomor_surat" name="nomor_surat"
                       class="w-full px-4 py-2 minimal-input rounded" required>
            </div>
        </div>
        
        <!-- Tanggal -->
        <div class="mb-8">
            <label for="tanggal" class="block text-sm font-medium text-gray-900 mb-2">
                Tanggal Surat
            </label>
            <input type="date" id="tanggal" name="tanggal"
                   class="w-full md:w-1/2 px-4 py-2 minimal-input rounded" required>
        </div>

        <!-- Bagian-bagian Surat dengan CKEditor -->
        <div class="space-y-8">
            <!-- Para Pihak -->
            <div>
                <label for="para_pihak" class="block text-sm font-medium text-gray-900 mb-2">
                    Para Pihak
                </label>
                <textarea id="para_pihak" name="para_pihak" class="hidden"></textarea>
            </div>

            <!-- Maksud dan Tujuan -->
            <div>
                <label for="maksud_tujuan" class="block text-sm font-medium text-gray-900 mb-2">
                    Maksud dan Tujuan
                </label>
                <textarea id="maksud_tujuan" name="maksud_tujuan" class="hidden"></textarea>
            </div>

            <!-- Ruang Lingkup -->
            <div>
                <label for="ruang_lingkup" class="block text-sm font-medium text-gray-900 mb-2">
                    Ruang Lingkup Kerjasama
                </label>
                <textarea id="ruang_lingkup" name="ruang_lingkup" class="hidden"></textarea>
            </div>

            <!-- Jangka Waktu -->
            <div>
                <label for="jangka_waktu" class="block text-sm font-medium text-gray-900 mb-2">
                    Jangka Waktu Kerjasama
                </label>
                <textarea id="jangka_waktu" name="jangka_waktu" class="hidden"></textarea>
            </div>

            <!-- Biaya -->
            <div>
                <label for="biaya_pembayaran" class="block text-sm font-medium text-gray-900 mb-2">
                    Biaya dan Pembayaran
                </label>
                <textarea id="biaya_pembayaran" name="biaya_pembayaran" class="hidden"></textarea>
            </div>

            <!-- Kerahasiaan -->
            <div>
                <label for="kerahasiaan" class="block text-sm font-medium text-gray-900 mb-2">
                    Kerahasiaan
                </label>
                <textarea id="kerahasiaan" name="kerahasiaan" class="hidden"></textarea>
            </div>

            <!-- Sengketa -->
            <div>
                <label for="penyelesaian_sengketa" class="block text-sm font-medium text-gray-900 mb-2">
                    Penyelesaian Sengketa
                </label>
                <textarea id="penyelesaian_sengketa" name="penyelesaian_sengketa" class="hidden"></textarea>
            </div>

            <!-- Penutup -->
            <div>
                <label for="penutup" class="block text-sm font-medium text-gray-900 mb-2">
                    Penutup
                </label>
                <textarea id="penutup" name="penutup" class="hidden"></textarea>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="border-t border-gray-200 pt-8 mt-8">
            <label class="block text-sm font-medium text-gray-900 mb-2">
                Tanda Tangan Digital
            </label>
            <p class="text-sm text-gray-600 mb-4">Buat tanda tangan digital Anda di bawah ini:</p>
            <div class="bg-gray-50 p-2 rounded">
                <canvas id="signature-pad" width="600" height="200" class="w-full bg-white rounded"></canvas>
            </div>
            <input type="hidden" name="tanda_tangan" id="tanda_tangan">

            <button type="button"
                    onclick="clearCanvas()"
                    class="mt-4 border border-gray-300 hover:border-gray-400 text-gray-700 font-medium py-2 px-4 rounded transition duration-150">
                <i class="fas fa-eraser mr-2"></i> Hapus Tanda Tangan
            </button>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end items-center gap-4 border-t border-gray-200 pt-8 mt-8">
            <a href="{{ route('admin.surat_kerjasama.index') }}"
               class="border border-gray-300 hover:border-gray-400 text-gray-700 font-medium py-2.5 px-6 rounded transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="bg-black hover:bg-gray-800 text-white font-medium py-2.5 px-6 rounded transition duration-150">
                <i class="fas fa-save mr-2"></i> Simpan Surat
            </button>
        </div>
    </form>
</div>

<!-- CKEditor Init -->
<script>
    document.querySelectorAll('textarea').forEach(textarea => {
        if (textarea.id) {
            ClassicEditor
                .create(document.querySelector('#' + textarea.id), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', '|',
                            'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    }
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        textarea.value = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>

<!-- Canvas Tanda Tangan -->
<script>
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    // Set ukuran canvas agar tidak blur
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    // Fungsi menggambar
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    canvas.addEventListener('mousemove', draw);

    // Touch events untuk mobile
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchmove', handleTouch);

    function startDrawing(e) {
        drawing = true;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function stopDrawing() {
        if (!drawing) return;
        drawing = false;
        document.getElementById('tanda_tangan').value = canvas.toDataURL();
    }

    function draw(e) {
        if (!drawing) return;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000000';
        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('tanda_tangan').value = '';
        // Isi kembali dengan background putih
        ctx.fillStyle = "#FFFFFF";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }
</script>

</body>
</html>