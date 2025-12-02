<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Attendance Screen</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: "#3b82f6",
        "primary-dark": "#2563eb",
        "primary-light": "#60a5fa",
        secondary: "#8b5cf6",
        success: "#10b981",
        warning: "#f59e0b",
        danger: "#ef4444",
        background: "#f8fafc",
        "background-alt": "#f1f5f9",
        surface: "#ffffff",
        "text-primary": "#1e293b",
        "text-secondary": "#64748b",
        "border-color": "#e2e8f0",
        "shadow-color": "rgba(0, 0, 0, 0.08)",
      },
      fontFamily: { display: ["Roboto", "sans-serif"] },
      borderRadius: { DEFAULT: "1rem", lg: "1.25rem", full: "9999px" },
      boxShadow: {
        card: "0 10px 25px rgba(0,0,0,0.08)",
        "card-hover": "0 20px 40px rgba(0,0,0,0.12)",
        "inner": "inset 0 2px 4px rgba(0,0,0,0.06)",
      },
    },
  },
};
</script>
<style>
body { 
  font-family: 'Roboto', sans-serif;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
  min-height: 100vh;
  overflow-x: hidden;
  width: 100%;
}

* {
  box-sizing: border-box;
}

main, .container, .grid, .action-card, .status-card, .history-table {
  max-width: 100%;
  overflow-x: hidden;
}

/* Enhanced SweetAlert popup styles */
.swal2-popup { 
  font-family: 'Roboto', sans-serif; 
  border-radius: 1.25rem; 
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  overflow: hidden !important;
  max-width: 480px !important;
  width: 100% !important;
  padding: 0 !important;
  background: linear-gradient(145deg, #ffffff, #fafbfc);
  border: 1px solid rgba(255, 255, 255, 0.8);
}

.swal2-header {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  padding: 1.25rem 1.5rem 1rem;
  margin: 0 !important;
  border-radius: 1.25rem 1.25rem 0 0;
}

.swal2-title {
  color: white !important;
  font-weight: 600 !important;
  font-size: 1.3rem !important;
  margin: 0 !important;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.swal2-title .material-icons {
  font-size: 1.5rem;
}

.swal2-html-container {
  overflow: hidden !important;
  width: 100% !important;
  max-width: 100% !important;
  padding: 1.25rem 1.5rem !important;
  margin: 0 !important;
}

.swal2-actions {
  padding: 0 1.5rem 1.25rem !important;
  margin: 0 !important;
  gap: 0.75rem !important;
}

.swal2-confirm, .swal2-cancel {
  border-radius: 0.75rem !important;
  font-weight: 500 !important;
  padding: 0.75rem 1.25rem !important;
  transition: all 0.3s ease !important;
  font-size: 0.9rem !important;
}

.swal2-confirm {
  background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

.swal2-confirm:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4) !important;
}

.swal2-cancel {
  background: #f1f5f9 !important;
  color: #64748b !important;
  border: 1px solid #e2e8f0 !important;
}

.swal2-cancel:hover {
  background: #e2e8f0 !important;
}

/* Form container dengan wrapper untuk semua field */
.swal2-form-container {
  width: 100%;
  max-width: 100% !important;
  margin: 0;
  overflow: hidden;
  padding: 0;
}

.form-group {
  margin-bottom: 1rem;
  width: 100%;
  overflow: hidden;
  position: relative;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #1e293b;
  font-size: 0.85rem;
  width: 100%;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-group label .material-icons {
  font-size: 1.1rem;
  color: #3b82f6;
}

.form-group .required {
  color: #ef4444;
  margin-left: 0.25rem;
}

/* Wrapper untuk semua field agar sama persis */
.field-wrapper {
  position: relative;
  width: 100%;
  display: flex;
  align-items: center;
}

.field-wrapper .material-icons {
  position: absolute;
  right: 12px;
  pointer-events: none;
  color: #64748b;
  font-size: 20px;
  z-index: 2;
}

/* Semua field input menggunakan style yang sama persis */
.uniform-input {
  border-radius: 0.75rem !important; 
  border: 2px solid #e2e8f0 !important;
  padding: 0.75rem 1rem !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  overflow: hidden !important;
  font-size: 0.9rem !important;
  transition: all 0.3s ease !important;
  background: #fafbfc !important;
  font-family: 'Roboto', sans-serif !important;
  height: 48px !important;
  line-height: 1.4 !important;
  appearance: none !important;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
}

.uniform-input:focus {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
  background: white !important;
  outline: none !important;
}

/* Custom untuk date input */
input[type="date"].uniform-input::-webkit-calendar-picker-indicator {
  opacity: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

/* Custom untuk select */
select.uniform-input {
  cursor: pointer !important;
  padding-right: 40px !important;
}

/* Textarea khusus */
.uniform-textarea {
  border-radius: 0.75rem !important; 
  border: 2px solid #e2e8f0 !important;
  padding: 0.75rem 1rem !important;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
  overflow: hidden !important;
  font-size: 0.9rem !important;
  transition: all 0.3s ease !important;
  background: #fafbfc !important;
  font-family: 'Roboto', sans-serif !important;
  min-height: 100px !important;
  resize: vertical !important;
  line-height: 1.4 !important;
}

.uniform-textarea:focus {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
  background: white !important;
  outline: none !important;
}

/* Success message styling */
.success-container {
  text-align: center;
  padding: 0.5rem;
}

.success-container .material-icons {
  font-size: 3.5rem;
  color: #10b981;
  margin-bottom: 0.75rem;
  animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
  0% { transform: scale(0); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

/* Action Cards */
.action-card {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border-radius: 1.25rem;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.8);
}

.action-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.action-card:hover::before {
  transform: scaleX(1);
}

.action-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.action-card span.material-icons { 
  font-size: 3.5rem; 
  margin-bottom: 1rem;
  transition: all 0.3s ease;
}

.action-card:hover span.material-icons {
  transform: scale(1.1);
}

.clock-container {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  position: relative;
  overflow: hidden;
}

.clock-container::after {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.05) 50%, transparent 70%);
}

.clock-time {
  font-size: 3.5rem;
  font-weight: 700;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  position: relative;
  z-index: 1;
}

.clock-date {
  color: #64748b;
  position: relative;
  z-index: 1;
}

.status-card {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.status-item {
  padding: 1rem;
  border-radius: 0.75rem;
  transition: all 0.3s ease;
}

.status-item:hover {
  background-color: #f8fafc;
  transform: translateX(5px);
}

.history-table {
  background: linear-gradient(145deg, #ffffff, #f8fafc);
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.table-row {
  transition: all 0.2s ease;
  cursor: pointer;
}

.table-row:hover {
  background-color: #f8fafc;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-on-time {
  background-color: rgba(16, 185, 129, 0.1);
  color: #10b981;
}

.status-late {
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
}

.status-absent {
  background-color: rgba(245, 158, 11, 0.1);
  color: #f59e0b;
}

.status-no-show {
  background-color: rgba(156, 163, 175, 0.1);
  color: #9ca3af;
}

.btn-primary {
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
}

.page-title {
  position: relative;
  display: inline-block;
  margin-bottom: 2rem;
}

.page-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  border-radius: 2px;
}

/* Late time badge */
.late-time-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.125rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 500;
  background-color: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  margin-left: 0.5rem;
}

/* History modal styles */
.history-modal-table {
  width: 100%;
  border-collapse: collapse;
}

.history-modal-table th,
.history-modal-table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}

.history-modal-table th {
  background-color: #f8fafc;
  font-weight: 600;
  color: #1e293b;
  font-size: 0.875rem;
}

.history-modal-table td {
  color: #64748b;
  font-size: 0.875rem;
}

.history-modal-table tr:hover {
  background-color: #f8fafc;
}

/* Responsive form styles */
@media (max-width: 640px) {
  .swal2-popup {
    width: 95% !important;
    max-width: 95% !important;
  }
  
  .swal2-header {
    padding: 1rem 1.25rem 0.75rem !important;
  }
  
  .swal2-title {
    font-size: 1.1rem !important;
  }
  
  .swal2-html-container {
    padding: 1rem 1.25rem !important;
  }
  
  .swal2-actions {
    padding: 0 1.25rem 1rem !important;
    flex-direction: column !important;
  }
  
  .swal2-confirm, .swal2-cancel {
    width: 100% !important;
  }
  
  .form-group {
    margin-bottom: 0.875rem;
  }
}

/* Responsive table */
@media (max-width: 768px) {
  .history-table {
    padding: 1rem;
  }
  
  .table-row td {
    padding: 0.5rem !important;
    font-size: 0.875rem;
  }
  
  .status-badge {
    font-size: 0.625rem;
    padding: 0.2rem 0.5rem;
  }
}

/* Responsive clock */
@media (max-width: 640px) {
  .clock-time {
    font-size: 2.5rem;
  }
  
  .clock-date {
    font-size: 0.875rem;
  }
}

/* Responsive cards */
@media (max-width: 640px) {
  .action-card {
    padding: 1.5rem;
  }
  
  .action-card span.material-icons {
    font-size: 2.5rem;
  }
  
  .status-card {
    padding: 1.5rem;
  }
}
</style>
</head>
<body class="bg-background text-text-secondary">
<div class="min-h-screen flex flex-col p-4 lg:p-8">
@include('karyawan.templet.header')

<main class="flex-grow w-full max-w-7xl mx-auto">
  <h2 class="text-4xl font-bold text-center page-title text-text-primary">ABSENSI KARYAWAN</h2>

  <!-- Clock -->
  <div class="clock-container mb-8 text-center">
    <p class="clock-time" id="clock-time">12:00:00</p>
    <p class="clock-date text-lg mt-2" id="clock-date">Senin, 01 Januari 2025</p>
  </div>

  <!-- Action Cards -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
    <div class="action-card cursor-pointer absensi-btn" data-action="Absen Masuk">
      <span class="material-icons text-primary">login</span>
      <p class="font-semibold text-text-primary">ABSEN MASUK</p>
    </div>
    <div class="action-card cursor-pointer absensi-btn" data-action="Absen Pulang">
      <span class="material-icons text-primary">logout</span>
      <p class="font-semibold text-text-primary">ABSEN PULANG</p>
    </div>
    <div class="action-card cursor-pointer absensi-izin-btn" data-action="Izin">
      <span class="material-icons text-primary">event_busy</span>
      <p class="font-semibold text-text-primary">IZIN</p>
    </div>
    <div class="action-card cursor-pointer absensi-dinas-btn" data-action="Dinas Luar">
      <span class="material-icons text-primary">work_outline</span>
      <p class="font-semibold text-text-primary">DINAS LUAR</p>
    </div>
  </div>

  <!-- Status & History -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="status-card">
      <h3 class="font-bold text-xl mb-4 text-text-primary flex items-center">
        <span class="material-icons text-primary mr-2">assignment</span>
        Status Absensi
      </h3>
      <div class="space-y-3">
        <div class="status-item flex items-center gap-3">
          <span class="material-icons text-primary">login</span>
          <div>
            <p class="font-medium text-text-primary">Absen Masuk</p>
            <p class="text-text-secondary text-sm" id="today-checkin">09:00</p>
          </div>
        </div>
        <div class="status-item flex items-center gap-3">
          <span class="material-icons text-primary">logout</span>
          <div>
            <p class="font-medium text-text-primary">Absen Pulang</p>
            <p class="text-text-secondary text-sm" id="today-checkout">-</p>
          </div>
        </div>
        <div class="status-item flex items-center gap-3">
          <span class="material-icons text-success">check_circle</span>
          <div>
            <p class="font-medium text-text-primary">Status Hari Ini</p>
            <p class="text-success font-medium" id="today-status">Belum Absen</p>
          </div>
        </div>
      </div>
    </div>

    <div class="history-table lg:col-span-2">
      <h3 class="font-bold text-xl mb-4 text-text-primary flex items-center justify-between">
        <span class="flex items-center">
          <span class="material-icons text-primary mr-2">history</span>
          Riwayat Absensi
        </span>
        <button class="text-sm text-primary hover:text-primary-dark transition-colors" id="view-all-btn">Lihat Semua</button>
      </h3>
      <table class="w-full text-left">
        <thead>
          <tr class="text-text-primary border-b border-border-color">
            <th class="pb-3 font-medium">No</th>
            <th class="pb-3 font-medium">Tanggal</th>
            <th class="pb-3 font-medium">Jam Masuk</th>
            <th class="pb-3 font-medium">Jam Pulang</th>
            <th class="pb-3 font-medium">Status</th>
          </tr>
        </thead>
        <tbody class="text-text-secondary" id="history-tbody">
          <!-- Table rows will be dynamically added here -->
        </tbody>
      </table>
    </div>
  </div>
</main>

<footer class="w-full max-w-7xl mx-auto mt-8 text-center text-xs sm:text-sm text-text-secondary py-4">
<p>Copyright ©2025 by digicity.id</p>
</footer>
</div>

<script>
// Sample attendance data
let attendanceData = [
  { no: 1, date: '31 Des 2024', checkIn: '08:55', checkOut: '17:05', status: 'Tepat Waktu', statusType: 'on-time', lateMinutes: 0 },
  { no: 2, date: '30 Des 2024', checkIn: '09:10', checkOut: '17:00', status: 'Terlambat', statusType: 'late', lateMinutes: 10 },
  { no: 3, date: '29 Des 2024', checkIn: '08:45', checkOut: '17:15', status: 'Tepat Waktu', statusType: 'on-time', lateMinutes: 0 },
  { no: 4, date: '28 Des 2024', checkIn: '09:15', checkOut: '17:00', status: 'Terlambat', statusType: 'late', lateMinutes: 15 },
  { no: 5, date: '27 Des 2024', checkIn: '08:40', checkOut: '17:30', status: 'Tepat Waktu', statusType: 'on-time', lateMinutes: 0 },
  { no: 6, date: '26 Des 2024', checkIn: '-', checkOut: '-', status: 'Tidak Masuk', statusType: 'no-show', lateMinutes: 0 },
  { no: 7, date: '25 Des 2024', checkIn: '08:50', checkOut: '17:00', status: 'Tepat Waktu', statusType: 'on-time', lateMinutes: 0 },
  { no: 8, date: '24 Des 2024', checkIn: '09:05', checkOut: '17:20', status: 'Terlambat', statusType: 'late', lateMinutes: 5 },
  { no: 9, date: '23 Des 2024', checkIn: '08:30', checkOut: '17:00', status: 'Tepat Waktu', statusType: 'on-time', lateMinutes: 0 },
  { no: 10, date: '22 Des 2024', checkIn: '09:20', checkOut: '17:00', status: 'Terlambat', statusType: 'late', lateMinutes: 20 }
];

// Update clock
function updateClock() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  
  document.getElementById('clock-time').textContent = `${hours}:${minutes}:${seconds}`;
  
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  
  const dayName = days[now.getDay()];
  const day = String(now.getDate()).padStart(2, '0');
  const month = months[now.getMonth()];
  const year = now.getFullYear();
  
  document.getElementById('clock-date').textContent = `${dayName}, ${day} ${month} ${year}`;
}

updateClock();
setInterval(updateClock, 1000);

// Function to calculate late minutes - FIXED VERSION
function calculateLateMinutes(checkInTime) {
  // Parse the time string (format: HH:MM)
  const timeParts = checkInTime.split(':');
  const hours = parseInt(timeParts[0]);
  const minutes = parseInt(timeParts[1]);
  
  // Convert check-in time to total minutes since midnight
  const checkInTotalMinutes = (hours * 60) + minutes;
  
  // Standard work start time: 9:00 AM = 540 minutes since midnight
  const workStartMinutes = 9 * 60; // 540 minutes
  
  // Calculate late minutes
  if (checkInTotalMinutes > workStartMinutes) {
    return checkInTotalMinutes - workStartMinutes;
  }
  
  return 0; // Not late
}

// Function to check if it's after 12 PM (noon)
function isAfterNoon() {
  const now = new Date();
  const currentHour = now.getHours();
  return currentHour >= 12;
}

// Function to render history table
function renderHistoryTable() {
  const tbody = document.getElementById('history-tbody');
  tbody.innerHTML = '';
  
  // Show only last 5 records
  const recentData = attendanceData.slice(-5).reverse();
  
  recentData.forEach(record => {
    const row = document.createElement('tr');
    row.className = 'table-row border-b border-border-color';
    
    let statusHTML = `<span class="status-badge status-${record.statusType}">${record.status}</span>`;
    
    // Add late minutes badge if late
    if (record.statusType === 'late' && record.lateMinutes > 0) {
      statusHTML += `<span class="late-time-badge">+${record.lateMinutes} menit</span>`;
    }
    
    row.innerHTML = `
      <td class="py-3 text-text-primary">${record.no}</td>
      <td class="py-3">${record.date}</td>
      <td class="py-3">${record.checkIn}</td>
      <td class="py-3">${record.checkOut}</td>
      <td class="py-3">${statusHTML}</td>
    `;
    tbody.appendChild(row);
  });
  
  // Add click event to new rows
  document.querySelectorAll('.table-row').forEach(row => {
    row.addEventListener('click', function() {
      const cells = this.querySelectorAll('td');
      const no = cells[0].textContent;
      const date = cells[1].textContent;
      const checkIn = cells[2].textContent;
      const checkOut = cells[3].textContent;
      const statusBadge = cells[4].querySelector('.status-badge');
      const status = statusBadge ? statusBadge.textContent : '';
      const statusType = statusBadge ? statusBadge.className.split('status-')[1] : '';
      
      let detailHTML = `
        <strong style="color: #1e293b;">Nomor:</strong> ${no}<br>
        <strong style="color: #1e293b;">Tanggal:</strong> ${date}<br>
        <strong style="color: #1e293b;">Jam Masuk:</strong> ${checkIn}<br>
        <strong style="color: #1e293b;">Jam Pulang:</strong> ${checkOut}<br>
        <strong style="color: #1e293b;">Status:</strong> <span class="status-badge status-${statusType}">${status}</span>
      `;
      
      // Add late minutes detail if applicable
      const recordData = attendanceData.find(r => r.no == no);
      if (recordData && recordData.statusType === 'late' && recordData.lateMinutes > 0) {
        detailHTML += `<br><strong style="color: #ef4444;">Terlambat:</strong> ${recordData.lateMinutes} menit`;
      }
      
      let keterangan = '';
      if (statusType === 'on-time') {
        keterangan = 'Anda hadir tepat waktu. Tetap pertahankan kedisiplinan Anda!';
      } else if (statusType === 'late') {
        keterangan = 'Anda terlambat. Mohon datang lebih awal besok.';
      } else if (statusType === 'no-show') {
        keterangan = 'Anda tidak masuk. Pastikan untuk memberi informasi jika ada halangan.';
      } else {
        keterangan = 'Anda sedang izin atau dinas luar.';
      }
      
      Swal.fire({
        title: '<span class="material-icons">info</span> Detail Absensi',
        html: `
          <div style="text-align: left;">
            <div style="margin-bottom: 1rem;">
              ${detailHTML}
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem;">
              <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
                <strong>Keterangan:</strong> ${keterangan}
              </p>
            </div>
          </div>
        `,
        icon: '',
        confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">done</span> OK',
        confirmButtonColor: '#3b82f6',
        width: '400px'
      });
    });
  });
}

// Function to add new attendance record
function addAttendanceRecord(type, time) {
  const now = new Date();
  const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/\//g, ' ');
  const timeStr = time.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  
  // Check if it's after 12 PM for no-show
  const currentHour = now.getHours();
  
  // Check if there's already a record for today
  const todayRecord = attendanceData.find(record => record.date === dateStr);
  
  if (todayRecord) {
    // Update existing record
    if (type === 'checkin') {
      const lateMinutes = calculateLateMinutes(timeStr);
      todayRecord.checkIn = timeStr;
      todayRecord.status = lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
      todayRecord.statusType = lateMinutes > 0 ? 'late' : 'on-time';
      todayRecord.lateMinutes = lateMinutes;
    } else if (type === 'checkout') {
      todayRecord.checkOut = timeStr;
    }
  } else {
    // Add new record
    let status, statusType, lateMinutes = 0;
    
    if (type === 'checkin') {
      lateMinutes = calculateLateMinutes(timeStr);
      status = lateMinutes > 0 ? 'Terlambat' : 'Tepat Waktu';
      statusType = lateMinutes > 0 ? 'late' : 'on-time';
    } else {
      status = 'Tepat Waktu';
      statusType = 'on-time';
    }
    
    const newRecord = {
      no: attendanceData.length + 1,
      date: dateStr,
      checkIn: type === 'checkin' ? timeStr : '-',
      checkOut: type === 'checkout' ? timeStr : '-',
      status: status,
      statusType: statusType,
      lateMinutes: lateMinutes
    };
    
    attendanceData.push(newRecord);
  }
  
  // Re-render the table
  renderHistoryTable();
}

// Function to check for no-show (after 12 PM)
function checkNoShow() {
  const now = new Date();
  const currentHour = now.getHours();
  const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/\//g, ' ');
  
  // Only check after 12 PM
  if (currentHour >= 12) {
    const todayRecord = attendanceData.find(record => record.date === dateStr);
    
    // If no record exists for today or checkin is empty, mark as no-show
    if (!todayRecord || todayRecord.checkIn === '-') {
      if (todayRecord) {
        // Update existing record
        todayRecord.status = 'Tidak Masuk';
        todayRecord.statusType = 'no-show';
        todayRecord.lateMinutes = 0;
      } else {
        // Add new no-show record
        const newRecord = {
          no: attendanceData.length + 1,
          date: dateStr,
          checkIn: '-',
          checkOut: '-',
          status: 'Tidak Masuk',
          statusType: 'no-show',
          lateMinutes: 0
        };
        attendanceData.push(newRecord);
      }
      renderHistoryTable();
    }
  }
}

// Initial render
renderHistoryTable();

// Check for no-show every minute after 12 PM
setInterval(checkNoShow, 60000); // Check every minute

// Absen Masuk & Pulang
document.querySelectorAll('.absensi-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const action = this.getAttribute('data-action');
        const icon = this.querySelector('.material-icons').textContent;
        
        // Check if it's after 12 PM for check-in
        if (action === 'Absen Masuk' && isAfterNoon()) {
            Swal.fire({
                title: 'Perhatian',
                html: `
                    <div class="text-center">
                        <span class="material-icons text-6xl text-warning mb-4">warning</span>
                        <p>Sudah lewat jam 12 siang. Absen masuk tidak dapat dilakukan.</p>
                        <p class="text-sm text-text-secondary mt-2">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                    </div>
                `,
                icon: '',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti'
            });
            return;
        }
        
        Swal.fire({
            title: 'Konfirmasi',
            html: `
                <div class="text-center">
                    <span class="material-icons text-6xl text-primary mb-4">${icon}</span>
                    <p>Apakah kamu yakin ingin melakukan <strong>${action}</strong>?</p>
                </div>
            `,
            icon: '',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 1500);
                });
            }
        }).then((result) => {
            if(result.isConfirmed){
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                
                Swal.fire({
                    title: 'Berhasil!',
                    html: `
                        <div class="text-center">
                            <span class="material-icons text-6xl text-success mb-4">check_circle</span>
                            <p>Kamu berhasil melakukan <strong>${action}</strong>.</p>
                            <p class="text-sm text-text-secondary mt-2">${new Date().toLocaleString('id-ID')}</p>
                        </div>
                    `,
                    icon: '',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'OK'
                });
                
                // Update UI to reflect the change
                if (action === 'Absen Masuk') {
                    const lateMinutes = calculateLateMinutes(timeStr);
                    document.getElementById('today-checkin').textContent = timeStr;
                    
                    // Update status with late info
                    if (lateMinutes > 0) {
                        document.getElementById('today-status').innerHTML = `Terlambat <span class="late-time-badge">+${lateMinutes} menit</span>`;
                        document.getElementById('today-status').className = 'text-warning font-medium';
                    } else {
                        document.getElementById('today-status').textContent = 'Tepat Waktu';
                        document.getElementById('today-status').className = 'text-success font-medium';
                    }
                    
                    // Add to history
                    addAttendanceRecord('checkin', now);
                } else if (action === 'Absen Pulang') {
                    document.getElementById('today-checkout').textContent = timeStr;
                    
                    // Add to history
                    addAttendanceRecord('checkout', now);
                }
            }
        });
    });
});

// Form Izin dengan semua field yang sama persis
document.querySelector('.absensi-izin-btn').addEventListener('click', () => {
    Swal.fire({
        title: '<span class="material-icons">event_busy</span> Form Pengajuan Izin',
        html:
            '<div class="swal2-form-container">' +
            '<div class="form-group">' +
            '<label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="date" id="start-date" class="uniform-input">' +
            '<span class="material-icons">event</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">event</span> Tanggal Selesai <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="date" id="end-date" class="uniform-input">' +
            '<span class="material-icons">event</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">category</span> Tipe Izin <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<select id="type" class="uniform-input">' +
                '<option value="">Pilih Tipe Izin</option>' +
                '<option value="Cuti">Cuti</option>' +
                '<option value="Sakit">Sakit</option>' +
                '<option value="Izin">Izin Pribadi</option>' +
            '</select>' +
            '<span class="material-icons">arrow_drop_down</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">description</span> Alasan <span class="required">*</span></label>' +
            '<textarea id="reason" class="uniform-textarea" placeholder="Jelaskan alasan izin Anda..."></textarea>' +
            '</div>' +
            '</div>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">send</span> Kirim',
        cancelButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">close</span> Batal',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#e2e8f0',
        showLoaderOnConfirm: true,
        width: 'auto',
        didOpen: () => {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start-date').min = today;
            document.getElementById('end-date').min = today;
            
            // Add event listeners for date validation
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            
            startDateInput.addEventListener('change', function() {
                const startDate = this.value;
                if (startDate) {
                    endDateInput.min = startDate;
                    if (endDateInput.value && endDateInput.value < startDate) {
                        endDateInput.value = '';
                    }
                }
            });
            
            endDateInput.addEventListener('change', function() {
                const startDate = startDateInput.value;
                const endDate = this.value;
                if (startDate && endDate && endDate < startDate) {
                    Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                    this.value = '';
                }
            });
        },
        preConfirm: () => {
            const start = Swal.getPopup().querySelector('#start-date').value;
            const end = Swal.getPopup().querySelector('#end-date').value;
            const type = Swal.getPopup().querySelector('#type').value;
            const reason = Swal.getPopup().querySelector('#reason').value;
            
            if(!start || !end || !type || !reason){
                Swal.showValidationMessage('Semua kolom wajib diisi');
                return false;
            }
            
            if(end < start){
                Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                return false;
            }
            
            return { start, end, type, reason };
        }
    }).then(result => {
        if(result.isConfirmed){
            // Format dates for display
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                const dayName = days[date.getDay()];
                const day = String(date.getDate()).padStart(2, '0');
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                
                return `${dayName}, ${day} ${month} ${year}`;
            };
            
            // Add izin record to history
            const startDate = new Date(result.value.start);
            const dateStr = startDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/\//g, ' ');
            
            const newIzinRecord = {
              no: attendanceData.length + 1,
              date: dateStr,
              checkIn: '-',
              checkOut: '-',
              status: result.value.type,
              statusType: 'absent',
              lateMinutes: 0
            };
            
            attendanceData.push(newIzinRecord);
            renderHistoryTable();
            
            Swal.fire({
                title: '<span class="material-icons">check_circle</span> Pengajuan Terkirim!',
                html: `
                    <div class="success-container">
                        <div class="material-icons">task_alt</div>
                        <h3 style="color: #1e293b; margin-bottom: 0.75rem; font-size: 1.1rem;">Pengajuan Berhasil!</h3>
                        <p style="color: #64748b; margin-bottom: 0.25rem; font-size: 0.9rem;">Pengajuan izin (<strong>${result.value.type}</strong>)</p>
                        <p style="color: #64748b; margin-bottom: 0.25rem; font-size: 0.9rem;">dari <strong>${formatDate(result.value.start)}</strong></p>
                        <p style="color: #64748b; margin-bottom: 0.5rem; font-size: 0.9rem;">sampai <strong>${formatDate(result.value.end)}</strong></p>
                        <p style="color: #10b981; font-weight: 500; font-size: 0.9rem;">telah dikirim dan menunggu persetujuan.</p>
                        <div style="background: #f1f5f9; padding: 0.5rem; border-radius: 0.5rem; margin-top: 0.75rem;">
                            <p style="font-size: 0.8rem; color: #64748b;">No. Pengajuan: <strong style="color: #3b82f6;">#${Math.floor(Math.random() * 1000000)}</strong></p>
                        </div>
                    </div>
                `,
                icon: '',
                confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">done</span> OK',
                confirmButtonColor: '#10b981',
                width: 'auto'
            });
        }
    });
});

// Form Dinas Luar dengan lokasi dan tujuan kegiatan
document.querySelector('.absensi-dinas-btn').addEventListener('click', () => {
    Swal.fire({
        title: '<span class="material-icons">work_outline</span> Form Pengajuan Dinas Luar',
        html:
            '<div class="swal2-form-container">' +
            '<div class="form-group">' +
            '<label><span class="material-icons">calendar_today</span> Tanggal Mulai <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="date" id="dinas-start-date" class="uniform-input">' +
            '<span class="material-icons">event</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">event</span> Tanggal Selesai <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="date" id="dinas-end-date" class="uniform-input">' +
            '<span class="material-icons">event</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">location_on</span> Lokasi <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="text" id="location" class="uniform-input" placeholder="Masukkan lokasi dinas">' +
            '<span class="material-icons">place</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">flag</span> Tujuan Kegiatan <span class="required">*</span></label>' +
            '<div class="field-wrapper">' +
            '<input type="text" id="purpose" class="uniform-input" placeholder="Masukkan tujuan kegiatan">' +
            '<span class="material-icons">assignment</span>' +
            '</div>' +
            '</div>' +
            
            '<div class="form-group">' +
            '<label><span class="material-icons">description</span> Deskripsi Singkat <span class="required">*</span></label>' +
            '<textarea id="dinas-description" class="uniform-textarea" placeholder="Jelaskan kegiatan dinas Anda..."></textarea>' +
            '</div>' +
            '</div>',
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">send</span> Kirim',
        cancelButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">close</span> Batal',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#e2e8f0',
        showLoaderOnConfirm: true,
        width: 'auto',
        didOpen: () => {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('dinas-start-date').min = today;
            document.getElementById('dinas-end-date').min = today;
            
            // Add event listeners for date validation
            const startDateInput = document.getElementById('dinas-start-date');
            const endDateInput = document.getElementById('dinas-end-date');
            
            startDateInput.addEventListener('change', function() {
                const startDate = this.value;
                if (startDate) {
                    endDateInput.min = startDate;
                    if (endDateInput.value && endDateInput.value < startDate) {
                        endDateInput.value = '';
                    }
                }
            });
            
            endDateInput.addEventListener('change', function() {
                const startDate = startDateInput.value;
                const endDate = this.value;
                if (startDate && endDate && endDate < startDate) {
                    Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                    this.value = '';
                }
            });
        },
        preConfirm: () => {
            const start = Swal.getPopup().querySelector('#dinas-start-date').value;
            const end = Swal.getPopup().querySelector('#dinas-end-date').value;
            const location = Swal.getPopup().querySelector('#location').value;
            const purpose = Swal.getPopup().querySelector('#purpose').value;
            const description = Swal.getPopup().querySelector('#dinas-description').value;
            
            if(!start || !end || !location || !purpose || !description){
                Swal.showValidationMessage('Semua kolom wajib diisi');
                return false;
            }
            
            if(end < start){
                Swal.showValidationMessage('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                return false;
            }
            
            return { start, end, location, purpose, description };
        }
    }).then(result => {
        if(result.isConfirmed){
            // Format dates for display
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                const dayName = days[date.getDay()];
                const day = String(date.getDate()).padStart(2, '0');
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                
                return `${dayName}, ${day} ${month} ${year}`;
            };
            
            // Add dinas record to history
            const startDate = new Date(result.value.start);
            const dateStr = startDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/\//g, ' ');
            
            const newDinasRecord = {
              no: attendanceData.length + 1,
              date: dateStr,
              checkIn: '-',
              checkOut: '-',
              status: 'Dinas Luar',
              statusType: 'absent',
              lateMinutes: 0
            };
            
            attendanceData.push(newDinasRecord);
            renderHistoryTable();
            
            Swal.fire({
                title: '<span class="material-icons">check_circle</span> Pengajuan Terkirim!',
                html: `
                    <div class="success-container">
                        <div class="material-icons">task_alt</div>
                        <h3 style="color: #1e293b; margin-bottom: 0.75rem; font-size: 1.1rem;">Pengajuan Dinas Luar Berhasil!</h3>
                        <p style="color: #64748b; margin-bottom: 0.25rem; font-size: 0.9rem;">Lokasi: <strong>${result.value.location}</strong></p>
                        <p style="color: #64748b; margin-bottom: 0.25rem; font-size: 0.9rem;">Tujuan: <strong>${result.value.purpose}</strong></p>
                        <p style="color: #64748b; margin-bottom: 0.25rem; font-size: 0.9rem;">dari <strong>${formatDate(result.value.start)}</strong></p>
                        <p style="color: #64748b; margin-bottom: 0.5rem; font-size: 0.9rem;">sampai <strong>${formatDate(result.value.end)}</strong></p>
                        <p style="color: #10b981; font-weight: 500; font-size: 0.9rem;">telah dikirim dan menunggu persetujuan.</p>
                        <div style="background: #f1f5f9; padding: 0.5rem; border-radius: 0.5rem; margin-top: 0.75rem;">
                            <p style="font-size: 0.8rem; color: #64748b;">No. Pengajuan: <strong style="color: #3b82f6;">#${Math.floor(Math.random() * 1000000)}</strong></p>
                        </div>
                    </div>
                `,
                icon: '',
                confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">done</span> OK',
                confirmButtonColor: '#10b981',
                width: 'auto'
            });
        }
    });
});

// View All History functionality
document.getElementById('view-all-btn').addEventListener('click', () => {
    let tableHTML = `
        <div style="max-height: 400px; overflow-y: auto;">
            <table class="history-modal-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    attendanceData.forEach(record => {
        const statusClass = record.statusType === 'on-time' ? 'status-on-time' : 
                           record.statusType === 'late' ? 'status-late' : 
                           record.statusType === 'no-show' ? 'status-no-show' : 'status-absent';
        
        let statusHTML = `<span class="status-badge ${statusClass}">${record.status}</span>`;
        
        // Add late minutes badge if late
        if (record.statusType === 'late' && record.lateMinutes > 0) {
            statusHTML += `<span class="late-time-badge">+${record.lateMinutes} menit</span>`;
        }
        
        tableHTML += `
            <tr>
                <td>${record.no}</td>
                <td>${record.date}</td>
                <td>${record.checkIn}</td>
                <td>${record.checkOut}</td>
                <td>${statusHTML}</td>
            </tr>
        `;
    });
    
    tableHTML += `
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #64748b; font-size: 0.875rem;">Total Kehadiran:</span>
                <span style="color: #1e293b; font-weight: 500; font-size: 0.875rem;">${attendanceData.length} hari</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #64748b; font-size: 0.875rem;">Tepat Waktu:</span>
                <span style="color: #10b981; font-weight: 500; font-size: 0.875rem;">${attendanceData.filter(r => r.statusType === 'on-time').length} hari</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="color: #64748b; font-size: 0.875rem;">Terlambat:</span>
                <span style="color: #ef4444; font-weight: 500; font-size: 0.875rem;">${attendanceData.filter(r => r.statusType === 'late').length} hari</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748b; font-size: 0.875rem;">Tidak Hadir/Izin/Dinas:</span>
                <span style="color: #f59e0b; font-weight: 500; font-size: 0.875rem;">${attendanceData.filter(r => r.statusType === 'absent' || r.statusType === 'no-show').length} hari</span>
            </div>
        </div>
    `;
    
    Swal.fire({
        title: '<span class="material-icons">history</span> Riwayat Absensi Lengkap',
        html: tableHTML,
        icon: '',
        confirmButtonText: '<span class="material-icons" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.5rem;">close</span> Tutup',
        confirmButtonColor: '#3b82f6',
        width: '800px',
        showCloseButton: true
    });
});
</script>
</body>
</html>