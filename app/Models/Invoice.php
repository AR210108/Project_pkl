<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'company_name',
        'company_address',
        'kontak',
        'client_name',
        'order_number',
        'payment_method',
        'description',
        'subtotal',
        'tax',
        'total',
        'nama_layanan',
        'status_pembayaran'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function kwitansi()
    {
        return $this->hasMany(Kwitansi::class, 'invoice_id');
    }

    // Relationship dengan Project
    public function projects()
    {
        return $this->hasMany(Project::class, 'invoice_id');
    }

    // Accessor untuk mendapatkan project pertama (jika ada)
    public function getProjectAttribute()
    {
        return $this->projects()->first();
    }

    // Optional: Tambahkan accessor jika perlu kompatibilitas dengan field lama
    public function getNamaPerusahaanAttribute()
    {
        return $this->company_name;
    }

    public function getNamaKlienAttribute()
    {
        return $this->client_name;
    }

    public function getAlamatAttribute()
    {
        return $this->company_address;
    }

    public function getPajakAttribute()
    {
        return $this->tax;
    }

    public function getTotalAttribute()
    {
        return $this->attributes['total'] ?? 0;
    }

    // Relationship dengan model Layanan (jika ada)
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'nama_layanan', 'nama_layanan');
    }

    public function perusahaan()
{
    return $this->belongsTo(Perusahaan::class, 'company_name', 'nama_perusahaan');
}

    /**
     * Boot method untuk event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika invoice dibuat
        static::created(function ($invoice) {
            $invoice->createProjectFromInvoice();
            // Juga buat order otomatis agar invoice muncul di Data Orderan
            $invoice->createOrderFromInvoice();
        });

        // Event ketika invoice diupdate
        static::updated(function ($invoice) {
            // Jika invoice sudah memiliki project, sinkronkan data
            if ($invoice->project) {
                $invoice->syncProjectWithInvoice();
            }
        });
    }

    /**
     * Method untuk membuat project dari invoice
     */
    public function createProjectFromInvoice()
    {
        try {
            // Cek apakah sudah ada project untuk invoice ini
            $existingProject = Project::where('invoice_id', $this->id)->first();
            
            if ($existingProject) {
                return $existingProject;
            }

            // Buat nama project
            $projectName = $this->nama_layanan 
                ? "Project: " . $this->nama_layanan . " - " . $this->company_name
                : "Project dari Invoice #" . $this->invoice_no;

            // Buat deskripsi project
            $projectDescription = "Project berdasarkan Invoice #" . $this->invoice_no . "\n\n";
            $projectDescription .= "Klien: " . $this->client_name . "\n";
            $projectDescription .= "Perusahaan: " . $this->company_name . "\n";
            $projectDescription .= "Layanan: " . $this->nama_layanan . "\n";
            $projectDescription .= "Deskripsi Invoice: " . $this->description;

            // Tentukan tanggal mulai pengerjaan (default: 3 hari setelah invoice dibuat)
            $tanggalMulaiPengerjaan = now()->addDays(3);
            
            // Tentukan tanggal selesai pengerjaan (default: 30 hari setelah tanggal mulai)
            $tanggalSelesaiPengerjaan = $tanggalMulaiPengerjaan->copy()->addDays(30);
            
            // Tentukan tanggal kerjasama
            $tanggalMulaiKerjasama = $this->invoice_date;
            $tanggalSelesaiKerjasama = $tanggalMulaiKerjasama->copy()->addMonths(6); // Default 6 bulan

            // Tentukan status berdasarkan status pembayaran invoice
            $statusPengerjaan = 'pending';
            $statusKerjasama = $this->status_pembayaran == 'lunas' ? 'aktif' : 'aktif';

            // Buat project baru
            $project = Project::create([
                'invoice_id' => $this->id,
                'nama' => $projectName,
                'deskripsi' => $projectDescription,
                'harga' => $this->total,
                'tanggal_mulai_pengerjaan' => $tanggalMulaiPengerjaan,
                'tanggal_selesai_pengerjaan' => $tanggalSelesaiPengerjaan,
                'tanggal_mulai_kerjasama' => $tanggalMulaiKerjasama,
                'tanggal_selesai_kerjasama' => $tanggalSelesaiKerjasama,
                'status_pengerjaan' => $statusPengerjaan,
                'status_kerjasama' => $statusKerjasama,
                'progres' => 0,
                'penanggung_jawab_id' => null, // Bisa diisi nanti oleh admin
            ]);

            \Log::info('Project berhasil dibuat dari invoice', [
                'invoice_id' => $this->id,
                'project_id' => $project->id,
                'invoice_no' => $this->invoice_no
            ]);

            return $project;

        } catch (\Exception $e) {
            \Log::error('Gagal membuat project dari invoice: ' . $e->getMessage(), [
                'invoice_id' => $this->id,
                'error' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create an Order when an Invoice is created so it appears in Data Orderan
     */
    public function createOrderFromInvoice()
    {
        try {
            // Avoid duplicating orders
            $existingOrder = \App\Models\Order::where('invoice_id', $this->id)->first();
            if ($existingOrder) return $existingOrder;

            $order = \App\Models\Order::create([
                'order_no' => 'ORD-' . $this->id . '-' . time(),
                'layanan' => $this->nama_layanan ?? null,
                'kategori' => $this->nama_layanan ?? null,
                'price' => (int) ($this->subtotal ?? 0),
                'price_formatted' => number_format($this->subtotal ?? 0, 0, ',', '.'),
                'klien' => $this->client_name ?? null,
                'company_name' => $this->company_name ?? null,
                'order_date' => $this->invoice_date ?? null,
                'invoice_no' => $this->invoice_no,
                'company_address' => $this->company_address ?? null,
                'description' => $this->description ?? null,
                'subtotal' => $this->subtotal ?? 0,
                'tax' => $this->tax ?? 0,
                'total' => $this->total ?? 0,
                'payment_method' => $this->payment_method ?? null,
                'deposit' => 0,
                'paid' => 0,
                'status' => 'pending',
                'work_status' => 'planning',
                'invoice_id' => $this->id,
            ]);

            \Log::info('Order created from invoice (model)', ['order_id' => $order->id, 'invoice_id' => $this->id]);

            return $order;
        } catch (\Exception $e) {
            \Log::error('Failed to create order from invoice (model): ' . $e->getMessage(), ['invoice_id' => $this->id]);
            return null;
        }
    }

    /**
     * Method untuk sinkronisasi project dengan invoice
     */
    public function syncProjectWithInvoice()
    {
        try {
            $project = $this->project;
            
            if (!$project) {
                return false;
            }

            // Update nama project jika layanan berubah
            if ($this->nama_layanan && $project->nama != $this->nama_layanan) {
                $project->nama = "Project: " . $this->nama_layanan . " - " . $this->company_name;
            }

            // Update harga jika berubah
            if ($project->harga != $this->total) {
                $project->harga = $this->total;
            }

            // Update deskripsi
            $newDescription = "Project berdasarkan Invoice #" . $this->invoice_no . "\n\n";
            $newDescription .= "Klien: " . $this->client_name . "\n";
            $newDescription .= "Perusahaan: " . $this->company_name . "\n";
            $newDescription .= "Layanan: " . $this->nama_layanan . "\n";
            $newDescription .= "Deskripsi Invoice: " . $this->description;
            
            if ($project->deskripsi != $newDescription) {
                $project->deskripsi = $newDescription;
            }

            // Simpan perubahan
            $project->save();

            \Log::info('Project berhasil disinkronisasi dengan invoice', [
                'invoice_id' => $this->id,
                'project_id' => $project->id
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Gagal sinkronisasi project dengan invoice: ' . $e->getMessage(), [
                'invoice_id' => $this->id
            ]);
            return false;
        }
    }

    /**
     * Method untuk mengecek apakah invoice memiliki project
     */
    public function hasProject()
    {
        return $this->projects()->exists();
    }

    /**
     * Method untuk mendapatkan link ke project
     */
    public function getProjectLinkAttribute()
    {
        $project = $this->project;
        if ($project) {
            return route('admin.project.show', $project->id);
        }
        return null;
    }

    /**
     * Method untuk mendapatkan status project
     */
    public function getProjectStatusAttribute()
    {
        $project = $this->project;
        if ($project) {
            return [
                'status_pengerjaan' => $project->status_pengerjaan_formatted,
                'status_kerjasama' => $project->status_kerjasama_formatted,
                'progres' => $project->progres . '%'
            ];
        }
        return null;
    }

    // Di App\Models\Invoice.php
public function getDescriptionAttribute($value)
{
    // Jika description kosong, ambil dari layanan terkait
    if (empty($value) && $this->layanan) {
        return $this->layanan->deskripsi;
    }
    
    return $value;
}

// Atau tambahkan attribute casting untuk memastikan deskripsi selalu ada
public function getDeskripsiAttribute()
{
    // Prioritas 1: description dari invoice
    if (!empty($this->attributes['description'])) {
        return $this->attributes['description'];
    }
    
    // Prioritas 2: deskripsi dari layanan
    if ($this->layanan && !empty($this->layanan->deskripsi)) {
        return $this->layanan->deskripsi;
    }
    
    // Default: nama layanan
    return $this->nama_layanan ? 'Layanan: ' . $this->nama_layanan : 'Tidak ada deskripsi';
}
}