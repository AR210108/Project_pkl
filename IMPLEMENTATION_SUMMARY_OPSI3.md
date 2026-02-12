# Implementasi Opsi 3: Advanced Task Acceptance Tracking

## Deskripsi Singkat
Opsi 3 mengimplementasikan sistem tracking detail untuk penerimaan tugas ketika multiple karyawan ditugaskan pada satu tugas. Setiap karyawan memiliki record terpisah yang melacak status penerimaan mereka.

## Perubahan Database

### New Table: `task_acceptances`
```sql
CREATE TABLE task_acceptances (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    accepted_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    UNIQUE(task_id, user_id),
    FOREIGN KEY(task_id) REFERENCES tasks(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
)
```

**Kolom:**
- `task_id`: ID dari tugas
- `user_id`: ID dari karyawan yang ditugaskan
- `status`: Status penerimaan (pending/accepted/rejected)
- `accepted_at`: Timestamp kapan karyawan menerima tugas
- `notes`: Catatan opsional dari karyawan

## Perubahan Model

### Task Model (`app/Models/Task.php`)
**Added Relationship:**
```php
public function acceptances()
{
    return $this->hasMany(TaskAcceptance::class)->orderBy('created_at', 'asc');
}
```

**Added Methods:**
```php
// Mendapatkan status acceptance keseluruhan
public function getAcceptanceStatus()
// Returns: [
//     'total' => total assignees,
//     'accepted' => jumlah yang accept,
//     'pending' => jumlah pending,
//     'rejected' => jumlah reject,
//     'percentage' => persentase,
//     'is_fully_accepted' => boolean,
//     'is_any_accepted' => boolean,
//     'is_any_rejected' => boolean
// ]

// Mendapatkan detail acceptance per assignee
public function getAcceptanceDetails()
// Returns array of [user_id, user_name, user_email, status, accepted_at, notes]
```

### New Model: TaskAcceptance (`app/Models/TaskAcceptance.php`)
- Eloquent model untuk tracking penerimaan tugas
- Menggunakan SoftDeletes
- Relationships: task(), user()
- Scopes: accepted(), pending(), rejected()

## Perubahan Controller

### KaryawanController
**New Method: `acceptTask()`**
- Validasi user terautentikasi dan ditugaskan
- Inisialisasi task acceptances jika belum ada (first time)
- Update status user dari 'pending' → 'accepted' dengan timestamp
- **PENTING**: Status task berubah ke 'proses' HANYA jika SEMUA assignees sudah accept
- Return acceptance status lengkap

**New Method: `getAcceptanceStatus()`**
- API endpoint untuk get current acceptance status
- Return breakdown detail siapa saja yang accept/pending/reject
- Menampilkan progress percentage

**Helper Method: `initializeTaskAcceptances()`**
- Membuat acceptance record untuk setiap assignee saat task dibuat
- Dipanggil otomatis saat:
  1. Task pertama kali dibuat di ManagerDivisiTaskController
  2. Saat karyawan pertama kali click accept (fallback)

### ManagerDivisiTaskController
- Import TaskAcceptance model
- Call `initializeTaskAcceptances()` setelah task created
- Helper method yang sama untuk consistency

## Frontend Changes

### Task Detail Modal (`resources/views/karyawan/list.blade.php`)

**New Section: "Status Penerimaan Tugas"**
Hanya tampil jika multiple assignees (total > 1)
- Progress bar: menunjukkan % acceptance
- List assignees dengan status individual:
  - ✓ DITERIMA (green) dengan waktu penerimaan
  - ⏱ PENDING (yellow)
  - ✗ DITOLAK (red)

**New JavaScript Function: `loadAcceptanceStatus()`**
- Fetch `/karyawan/tugas/{id}/acceptance-status`
- Populate modal dengan data detail per assignee
- Update progress bar secara real-time

## Workflow Untuk Multi-Assign Scenario

**Scenario**: Task ditugaskan ke Dewi + Rizki

1. **Task Dibuat** (Manager Divisi)
   - Task dibuat dengan `assigned_to = Dewi`, `assigned_to_ids = [Dewi, Rizki]`
   - System otomatis create 2 acceptance records:
     - `task_acceptances[task_id=1, user_id=Dewi, status=pending]`
     - `task_acceptances[task_id=1, user_id=Rizki, status=pending]`
   - Task status = `pending`

2. **Rizki Accept Tugas**
   - Click "Terima Tugas" di card atau modal
   - System update: `task_acceptances[Rizki].status = 'accepted'` + timestamp
   - Check: Sudah semua accept? TIDAK (Dewi masih pending)
   - Task status TETAP `pending`
   - Return message: "Tugas berhasil diterima. Menunggu penerimaan dari assignee lainnya"

3. **Dewi Melihat Detail Task**
   - Open modal → Load acceptance status
   - Lihat progress: "50% - Rizki ✓, Dewi ⏱"

4. **Dewi Accept Tugas**
   - Click "Terima Tugas"
   - System update: `task_acceptances[Dewi].status = 'accepted'` + timestamp
   - Check: Sudah semua accept? YA (2/2 = 100%)
   - Update task status: `pending` → `proses`
   - Return message: "Tugas berhasil diterima. Semua assignee telah menerima. Status berubah menjadi Dalam Proses"

5. **Manager Divisi Monitoring**
   - Di dashboard manager bisa lihat:
     - Task status = `proses` (berarti semua sudah accept)
     - Jika klik detail, lihat breakdown siapa yang accept kapan

## Status Task vs Acceptance Status

| Scenario | Task Status | Acceptance Status |
|----------|------------|-------------------|
| Baru dibuat, belum ada yang accept | `pending` | 0/2 accepted |
| 1 dari 2 accept | `pending` | 1/2 accepted |
| Semua accept | `proses` | 2/2 accepted |
| Task selesai | `selesai` | 2/2 accepted (not changed) |
| Task batal | `dibatalkan` | -  |

## API Endpoints

### GET `/karyawan/tugas/{id}/acceptance-status`
```json
{
    "success": true,
    "acceptance_status": {
        "total": 2,
        "accepted": 1,
        "pending": 1,
        "rejected": 0,
        "percentage": 50,
        "is_fully_accepted": false,
        "is_any_accepted": true,
        "is_any_rejected": false
    },
    "acceptance_details": [
        {
            "user_id": 1,
            "user_name": "Rizki",
            "user_email": "rizki@example.com",
            "status": "accepted",
            "accepted_at": "2026-02-10 15:30:00",
            "notes": null
        },
        {
            "user_id": 2,
            "user_name": "Dewi",
            "user_email": "dewi@example.com",
            "status": "pending",
            "accepted_at": null,
            "notes": null
        }
    ]
}
```

### PUT `/karyawan/tugas/{id}/accept`
```json
Request:
{
    "notes": "Catatan opsional"
}

Response Success (not all accepted):
{
    "success": true,
    "message": "Tugas berhasil diterima. Menunggu penerimaan dari assignee lainnya",
    "data": {
        "task_id": 1,
        "task_status": "pending",
        "acceptance_status": { ... },
        "acceptance_details": [ ... ]
    }
}

Response Success (all accepted):
{
    "success": true,
    "message": "Tugas berhasil diterima. Semua assignee telah menerima. Status berubah menjadi Dalam Proses",
    "data": {
        "task_id": 1,
        "task_status": "proses",
        "acceptance_status": { ... },
        "acceptance_details": [ ... ]
    }
}
```

## Database Migration
File: `database/migrations/2026_02_10_140000_create_task_acceptances_table.php`

Sudah dijalankan dengan `php artisan migrate`

## Testing Instructions

1. **Create Task dengan Multiple Assignees**
   - Buka Manager Divisi → Kelola Tugas → Tambah Tugas
   - Pilih 2+ karyawan
   - Submit

2. **Check Karyawan 1**
   - Login sebagai Karyawan 1
   - Buka Daftar Tugas
   - Lihat task card dengan button "Terima Tugas"
   - Click → lihat modal dengan acceptance progress
   - Click "Terima Tugas" di modal
   - Verify message: "Menunggu penerimaan dari assignee lainnya"
   - Task status masih "pending"

3. **Check Karyawan 2**
   - Login sebagai Karyawan 2
   - Buka Daftar Tugas
   - Modal → lihat progress 50% (1/2 accepted)
   - Click "Terima Tugas"
   - Verify message: "Semua assignee telah menerima..."
   - Task status berubah ke "proses"

4. **Manager Monitoring**
   - Login Manager Divisi
   - Lihat task status = "proses"
   - Klik detail → lihat acceptance breakdown

## Keuntungan Opsi 3

✅ **Detailed Tracking**: Tahu siapa saja yang sudah accept dan kapan
✅ **Progress Visibility**: Progress bar menunjukkan % completion
✅ **Accountability**: Audit trail lengkap siapa yang make action kapan
✅ **Flexible**: Bisa extended untuk add rejection reasons, notes per user
✅ **Non-Blocking**: Task bisa dilanjutkan dengan partial acceptance jika diinginkan
✅ **Scalable**: Tetap efisien meski banyak assignees

## Kelemahan Opsi 3

⚠️ **More Complex**: Implementation lebih complex vs opsi 2
⚠️ **Extra DB Queries**: Need extra table + queries untuk tracking
⚠️ **Data Growth**: task_acceptances table bisa besar untuk many tasks

## Files Changed

```
app/Models/
  ├── Task.php (added relationships & methods)
  └── TaskAcceptance.php (NEW)

app/Http/Controllers/
  ├── KaryawanController.php (added acceptTask, getAcceptanceStatus, initializeTaskAcceptances)
  └── ManagerDivisiTaskController.php (added initializeTaskAcceptances)

database/migrations/
  └── 2026_02_10_140000_create_task_acceptances_table.php (NEW)

resources/views/karyawan/
  └── list.blade.php (added acceptance status section & loadAcceptanceStatus function)

routes/
  └── web.php (added acceptance-status route)
```

## Notes

- Opsi 3 mudah di-upgrade ke more features (rejection reasons, deadline untuk accept, etc)
- Current implementation: ALL must accept untuk status jadi proses. Bisa di-modify jika dibutuhkan partial acceptance workflow
- Historical tracking bisa diretrieve lewat task_acceptances dengan accepted_at timestamp
