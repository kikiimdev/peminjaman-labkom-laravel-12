# APELKom - Aplikasi PEminjaman Laboratorium Komputer

Sistem manajemen peminjaman laboratorium komputer untuk Fakultas Ekonomi dan Bisnis Universitas Lambung Mangkurat.

## 📋 Tentang

**APELKom** (Aplikasi PEminjaman Laboratorium Komputer) adalah sistem manajemen peminjaman laboratorium komputer yang dirancang khusus untuk FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS LAMBUNG MANGKURAT. Aplikasi ini menyediakan solusi terintegrasi untuk manajemen jadwal, monitoring fasilitas, pelaporan insiden, dan pemeliharaan lab komputer.

## 🚀 Fitur Utama

### Manajemen Jadwal
- Peminjaman lab komputer dengan sistem approval otomatis
- Notifikasi real-time untuk status persetujuan
- Kalender publik untuk melihat ketersediaan lab
- Histori peminjaman dan penggunaan

### Manajemen Lab
- Monitoring status dan ketersediaan lab komputer
- Manajemen fasilitas dan peralatan lab
- Kapasitas dan utilisasi lab secara real-time
- Status pemeliharaan dan perbaikan

### Pelaporan & Analitik
- Laporan insiden dengan sistem tracking lengkap
- Analitik utilisasi lab dan trend penggunaan
- Dashboard admin dengan statistik komprehensif
- Export laporan dalam berbagai format

### Pemeliharaan
- Jadwal pemeliharaan rutin
- Tracking status perbaikan fasilitas
- Notifikasi otomatis untuk jadwal maintenance
- Histori pemeliharaan lab

## 🛠 Teknologi

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 dengan Flux UI
- **Database**: MySQL/PostgreSQL
- **Testing**: Pest PHP
- **Styling**: Tailwind CSS v4
- **Icons**: Font Awesome

## 📦 Instalasi

### Persyaratan Sistem
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+ atau PostgreSQL 12+

### Langkah Instalasi

1. **Clone repository**
```bash
git clone https://github.com/kikiimdev/peminjaman-labkom-laravel-12
cd peminjaman-labkom-laravel-12
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Konfigurasi environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database**
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apelkom_unlam
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run build
```

7. **Start development server**
```bash
php artisan serve
```

## 👥 Pengguna & Peran

### Admin
- Akses penuh ke semua fitur
- Manajemen pengguna dan peran
- Approval peminjaman lab
- Monitoring dan laporan sistem
- Manajemen pemeliharaan

### User (Dosen/Mahasiswa)
- Ajukan peminjaman lab
- Lihat jadwal dan ketersediaan
- Laporkan insiden
- Lihat jadwal peminjaman pribadi
- Akses ke dashboard personal

## 📂 Struktur Aplikasi

```
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller utama
│   │   ├── Requests/        # Form request validation
│   │   └── Middleware/      # Middleware aplikasi
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   ├── views/components/  # Blade components
│   └── views/layouts/     # Layout templates
├── routes/                # Route definitions
├── tests/                 # Test files
└── .env.example          # Environment template
```

## 🧪 Testing

Jalankan semua test:
```bash
php artisan test
```

Jalankan test spesifik:
```bash
php artisan test --filter=TestName
```

## 🔧 Konfigurasi

### Environment Variables
- `APP_NAME`: Nama aplikasi (default: APELKom)
- `APP_URL`: URL aplikasi
- `DB_*`: Konfigurasi database
- `MAIL_*`: Konfigurasi email untuk notifikasi

### Queue Configuration
Untuk notifikasi email, configure queue:
```bash
php artisan queue:work
```

## 📊 Data Seeder

Aplikasi ini include dengan data sample:
- User admin dan user regular
- Laboratorium komputer dengan fasilitas
- Sample jadwal peminjaman
- Data insiden dan pemeliharaan

Run seeder:
```bash
php artisan db:seed
```

## 🎨 Kustomisasi

### Branding
- Update nama aplikasi di `.env` file (set ke "APELKom")
- Modifikasi logo di `resources/views/components/app-logo.blade.php`
- Sesuaikan warna di Tailwind config
- Custom text logo dengan singkatan APELKom

### Email Templates
- Template email terletak di `resources/views/emails/`
- Kustomisasi notifikasi di `app/Notifications/`

## 🚀 Deployment

### Production Build
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### File Permission
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 🤝 Kontribusi

1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 Lisensi

Proyek ini merupakan bagian dari skripsi di FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS LAMBUNG MANGKURAT.

**APELKom** - Aplikasi PEminjaman Laboratorium Komputer FEB UNLAM

## 📊 Arsitektur Aplikasi

### Diagram Hubungan Entitas (ERD)

```mermaid
erDiagram
    User ||--o{ Ruangan : "memiliki"
    User ||--o{ Jadwal : "meminjam"
    User ||--o{ PersetujuanJadwal : "menyetujui"
    User ||--o{ RiwayatStatusJadwal : "mengubah_status"
    User ||--o{ PemeriksaanRuangan : "memeriksa"
    User ||--o{ Insiden : "melaporkan"
    User ||--o{ Insiden : "menangani"

    Ruangan ||--o{ Jadwal : "dipinjam"
    Ruangan ||--o{ FasilitasRuangan : "memiliki_fasilitas"
    Ruangan ||--o{ PemeriksaanRuangan : "diperiksa"
    Ruangan ||--o{ Insiden : "terjadi_insiden"
    Ruangan ||--o{ PemeliharaanRuangan : "dipelihara"

    Fasilitas ||--o{ FasilitasRuangan : "digunakan_di"

    Jadwal ||--o{ TanggalJadwal : "memiliki_tanggal"
    Jadwal ||--o{ PersetujuanJadwal : "memerlukan_persetujuan"
    Jadwal ||--o{ RiwayatStatusJadwal : "memiliki_riwayat"
    Jadwal ||--o{ PemeriksaanRuangan : "diperiksa_sebelum"
    Jadwal ||--o{ Insiden : "terjadi_selama"

    FasilitasRuangan }|--|| Fasilitas : "fasilitas"
    FasilitasRuangan }|--|| Ruangan : "ruangan"

    User {
        string nama
        string email
        string password
        string peran
        string nama_lengkap
        string nomor_whatsapp
    }

    Ruangan {
        string nama
        string lokasi
        unsignedBigInteger pemilik_id
    }

    Jadwal {
        string keperluan
        string status
        unsignedBigInteger peminjam_id
        unsignedBigInteger ruangan_id
    }

    TanggalJadwal {
        unsignedBigInteger jadwal_id
        date tanggal
        time jam_mulai
        time jam_berakhir
    }

    PersetujuanJadwal {
        unsignedBigInteger jadwal_id
        unsignedBigInteger aktor_id
        string status
        text catatan
    }

    Fasilitas {
        string nama
        string satuan
    }

    FasilitasRuangan {
        unsignedBigInteger fasilitas_id
        unsignedBigInteger ruangan_id
        integer jumlah
    }
```

### Alur Proses Peminjaman Laboratorium

```mermaid
flowchart TD
    A[Pengguna Login] --> B[Dashboard Peminjaman]
    B --> C[Pilih Ruangan]
    C --> D[Isi Form Peminjaman]
    D --> E[Atur Tanggal & Waktu]
    E --> F[Kirim Pengajuan]

    F --> G{Status Ruangan}
    G -->|Tersedia| H[Buat Jadwal]
    G -->|Tidak Tersedia| I[Tampilkan Konflik]

    H --> J[Persetujuan Sistem]
    J --> K{Auto Approve?}
    K -->|Ya| L[Status: DISETUJUI]
    K -->|Tidak| M[Status: MENUNGGU]

    M --> N[Review Admin]
    N --> O{Keputusan Admin}
    O -->|Setuju| L
    O -->|Tolak| P[Status: DITOLAK]

    L --> Q[Kirim Notifikasi]
    P --> Q

    Q --> R[Update Kalender]
    R --> S[Buat Riwayat Status]

    I --> C
```

### Alur Kerja Persetujuan Jadwal

```mermaid
sequenceDiagram
    participant User as Peminjam
    participant System as Sistem APELKom
    participant Admin as Admin/Penyetuju
    participant Database as Database

    User->>System: Ajukan Peminjaman
    System->>Database: Cek Ketersediaan Ruangan
    Database-->>System: Status Ruangan

    alt Ruangan Tersedia
        System->>Database: Buat Jadwal (Status: MENUNGGU)
        System-->>User: Konfirmasi Pengajuan Diterima

        par Auto-approval untuk Pengguna Tertentu
            System->>System: Cek Peran/Permission Pengguna
            alt Auto-approver
                System->>Database: Update Status: DISETUJUI
                System->>Database: Buat PersetujuanJadwal
                System-->>User: Notifikasi Disetujui
            else Butuh Approval Manual
                System->>Admin: Notifikasi Approval Diperlukan
                Admin->>System: Review Pengajuan
                Admin->>System: Setujui/Tolak
                System->>Database: Update Status & Buat Persetujuan
                System-->>User: Notifikasi Status Akhir
            end
        end

    else Ruangan Tidak Tersedia
        System-->>User: Tampilkan Konflik Jadwal
    end
```

### Manajemen Status Jadwal

```mermaid
stateDiagram-v2
    [*] --> MENUNGGU: Kirim Pengajuan
    MENUNGGU --> DISETUJUI: Auto Approve
    MENUNGGU --> DISETUJUI: Admin Approve
    MENUNGGU --> DITOLAK: Admin Reject

    DISETUJUI --> DIBATALKAN: Batal Pengguna
    DISETUJUI --> SELESAI: Selesai Pemakaian
    DISETUJUI --> INSIDEN: Terjadi Masalah

    DITOLAK --> [*]: Selesai
    DIBATALKAN --> [*]: Selesai
    SELESAI --> [*]: Selesai
    INSIDEN --> SELESAI: Insiden Selesai

    state DISETUJUI {
        [*] --> Aktif
        Aktif --> SedangDigunakan: Mulai Pemakaian
        SedangDigunakan --> Selesai: Selesai Pemakaian
    }
```

### Diagram Aktivitas dengan Subgraph

```mermaid
flowchart LR
    subgraph "Alur Pengguna"
        Start([Mulai]) --> Login[Login]
        Login --> Dashboard[Dashboard]
        Dashboard --> Logout[Logout]
        Dashboard --> ChooseFeature[Pilih Fitur]
    end

    subgraph "Manajemen Jadwal"
        MJ_Start([Mulai]) --> ViewCalendar[Lihat Kalender]
        ViewCalendar --> CheckAvailability[Cek Ketersediaan]
        CheckAvailability --> Available{Tersedia?}

        subgraph "Jalur Tersedia"
            Available -->|Ya| FillForm[Isi Formulir]
            FillForm --> SelectDateTime[Pilih Tanggal & Waktu]
            SelectDateTime --> SubmitRequest[Kirim Permintaan]
            SubmitRequest --> AutoApproval{Auto Approve?}

            subgraph "Proses Persetujuan"
                AutoApproval -->|Ya| Approved[Disetujui Otomatis]
                AutoApproval -->|Tidak| ManualReview[Review Manual]
                ManualReview --> AdminDecision{Keputusan Admin}
                AdminDecision -->|Setuju| Approved
                AdminDecision -->|Tolak| Rejected[Ditolak]
            end

            Approved --> NotifyNotif[Kirim Notifikasi]
            Rejected --> NotifyNotif
            NotifyNotif --> UpdateCalendar[Update Kalender]
        end

        Available -->|Tidak| NotAvailable
        subgraph "Jalur Tidak Tersedia"
            NotAvailable[Tampilkan Konflik] --> SuggestAlternative[Saran Alternatif]
        end
    end

    subgraph "Manajemen Lab"
        ML_Start([Mulai]) --> ViewLabList[Lihat Daftar Lab]
        ViewLabList --> ViewLabDetails[Lihat Detail Lab]
        ViewLabDetails --> ManageFacilities[Kelola Fasilitas]

        subgraph "Manajemen Fasilitas"
            ManageFacilities --> AddFacility[Tambah Fasilitas]
            ManageFacilities --> EditFacility[Edit Fasilitas]
            ManageFacilities --> DeleteFacility[Hapus Fasilitas]
        end

        ViewLabDetails --> MonitorStatus[Monitor Status]
        MonitorStatus --> UpdateStatus[Update Status]
    end

    subgraph "Pelaporan & Analitik"
        PA_Start([Mulai]) --> ViewDashboard[Lihat Dashboard]
        ViewDashboard --> GenerateReports[Generate Laporan]
        ViewDashboard --> ReportIncident[Lapor Insiden]

        subgraph "Generasi Laporan"
            GenerateReports --> UsageAnalytics[Analitik Penggunaan]
            GenerateReports --> IncidentReports[Laporan Insiden]
            GenerateReports --> MaintenanceReports[Laporan Pemeliharaan]
        end

        subgraph "Pelaporan Insiden"
            ReportIncident --> FillIncidentForm[Isi Formulir Insiden]
            FillIncidentForm --> AttachEvidence[Lampirkan Bukti]
            AttachEvidence --> SubmitIncident[Kirim Laporan]
            SubmitIncident --> TrackStatus[Monitor Status]
        end
    end

    subgraph "Pemeliharaan"
        PM_Start([Mulai]) --> ScheduleMaintenance[Jadwalkan Pemeliharaan]
        ScheduleMaintenance --> CreateWorkOrder[Buat Work Order]
        CreateWorkOrder --> AssignTechnician[Assign Teknisi]
        AssignTechnician --> ExecuteMaintenance[Eksekusi Pemeliharaan]

        subgraph "Eksekusi Pemeliharaan"
            ExecuteMaintenance --> PreCheck[Check Pra-Pemeliharaan]
            PreCheck --> PerformMaintenance[Lakukan Pemeliharaan]
            PerformMaintenance --> PostCheck[Check Pasca-Pemeliharaan]
            PostCheck --> UpdateMaintenanceStatus[Update Status]
        end

        ScheduleMaintenance --> TrackMaintenance[Monitor Pemeliharaan]
        TrackMaintenance --> GenerateReport[Generate Laporan]
    end

    ChooseFeature --> MJ_Start
    ChooseFeature --> ML_Start
    ChooseFeature --> PA_Start
    ChooseFeature --> PM_Start
```

### Diagram Aktivitas Detail: Manajemen Jadwal

```mermaid
flowchart LR
    subgraph "Proses Peminjaman"
        P_Start([Mulai]) --> SelectRoom[Pilih Ruangan]
        SelectRoom --> CheckSchedule[Cek Jadwal Ruangan]
        CheckSchedule --> RoomAvailable{Tersedia?}

        RoomAvailable -->|Ya| FillBorrowForm[Isi Formulir Peminjaman]
        FillBorrowForm --> SetSchedule[Atur Jadwal]
        SetSchedule --> SubmitApp[Kirim Aplikasi]
        SubmitApp --> Validation[Proses Validasi]

        subgraph "Proses Validasi"
            Validation --> CheckUser[Cek Kelayakan Pengguna]
            CheckUser --> CheckTime[Cek Slot Waktu]
            CheckTime --> CheckCapacity[Cek Kapasitas]
            CheckCapacity --> ValidateData[Validasi Data]
        end

        Validation --> AutoApproval{Auto Approve?}

        subgraph "Proses Persetujuan"
            AutoApproval -->|Ya| AutoApprove[Setujui Otomatis]
            AutoApproval -->|Tidak| ManualReview[Review Manual]
            ManualReview --> AdminDecision{Keputusan Admin}
            AdminDecision -->|Setuju| Approve[Setujui]
            AdminDecision -->|Tolak| Reject[Tolak]
            Approve --> SendApproveNotif[Kirim Notifikasi Setuju]
            Reject --> SendRejectNotif[Kirim Notifikasi Tolak]
        end

        AutoApprove --> SendApproveNotif
        SendApproveNotif --> UpdateSchedule[Update Jadwal]
        UpdateSchedule --> GenerateEntry[Generate Entri Kalender]
        GenerateEntry --> SendConfirmation[Kirim Konfirmasi]

        RoomAvailable -->|Tidak| ShowConflict[Tampilkan Konflik]
    end

    subgraph "Proses Persetujuan"
        A_Start([Mulai]) --> ReviewApps[Review Aplikasi]
        ReviewApps --> CheckDetails[Cek Detail Aplikasi]
        CheckDetails --> VerifyAvailability[Verifikasi Ketersediaan]
        VerifyAvailability --> MakeDecision[Buat Keputusan]

        subgraph "Pembuatan Keputusan"
            MakeDecision --> ApproveApp[Setujui Aplikasi]
            MakeDecision --> RejectApp[Tolak Aplikasi]

            ApproveApp --> AddNotes[Tambah Catatan]
            RejectApp --> AddReason[Tambah Alasan Penolakan]

            AddNotes --> UpdateStatus[Update Status]
            AddReason --> UpdateStatus
        end

        MakeDecision --> SendNotification[Kirim Notifikasi]
        SendNotification --> LogHistory[Catat Riwayat]
    end

    subgraph "Pemantauan Status"
        T_Start([Mulai]) --> MonitorStatus[Monitor Status Jadwal]
        MonitorStatus --> StatusChange[Perubahan Status]

        subgraph "Alur Status"
            StatusChange --> Approved[Disetujui]
            Approved --> InProgress[Dalam Proses]
            InProgress --> Completed[Selesai]
            Completed --> Cancelled[Dibatalkan]

            InProgress --> Incident[Insiden Terjadi]
            Incident --> Investigation[Dalam Investigasi]
            Investigation --> Resolved[Selesai]
            Resolved --> Completed
        end

        StatusChange --> UpdateHistory[Update Riwayat]
        UpdateHistory --> SendStatusNotif[Kirim Notifikasi Status]
    end
```

### Diagram Aktivitas Detail: Manajemen Lab

```mermaid
flowchart LR
    subgraph "Manajemen Lab"
        LM_Start([Mulai]) --> ManageRooms[Kelola Ruangan]
        ManageRooms --> ManageFacilities[Kelola Fasilitas]
        ManageFacilities --> MonitorStatus[Monitor Status]

        subgraph "Operasi Ruangan"
            ManageRooms --> AddRoom[Tambah Ruangan]
            ManageRooms --> EditRoom[Edit Ruangan]
            ManageRooms --> DeactivateRoom[Nonaktifkan Ruangan]

            AddRoom --> SetDetails[Atur Detail Ruangan]
            SetDetails --> ConfigureCapacity[Konfigurasi Kapasitas]
            ConfigureCapacity --> AssignOwner[Assign Pemilik]

            EditRoom --> UpdateInfo[Update Info]
            DeactivateRoom --> ArchiveData[Arsip Data]
        end

        subgraph "Operasi Fasilitas"
            ManageFacilities --> AddFacility[Tambah Fasilitas]
            ManageFacilities --> AssignToRoom[Assign ke Ruangan]
            ManageFacilities --> RemoveFacility[Hapus Fasilitas]

            AddFacility --> SetSpecs[Atur Spesifikasi]
            AssignToRoom --> SetQuantity[Atur Jumlah]
            SetQuantity --> UpdateInventory[Update Inventori]
            RemoveFacility --> UpdateConfig[Update Konfigurasi]
        end

        subgraph "Pemantauan Status"
            MonitorStatus --> CheckRealTime[Cek Status Real-time]
            CheckRealTime --> UpdateIndicator[Update Indikator]
            MonitorStatus --> GenerateUsageReport[Generate Laporan Penggunaan]
            GenerateUsageReport --> AnalyzeUtilization[Analisis Utilisasi]
        end
    end

    subgraph "Inspeksi Fasilitas"
        FI_Start([Mulai]) --> ScheduleInspection[Jadwalkan Inspeksi]
        ScheduleInspection --> ConductInspection[Lakukan Inspeksi]
        ConductInspection --> DocumentFindings[Dokumentasi Temuan]
        DocumentFindings --> CreateReport[Buat Laporan]

        subgraph "Proses Inspeksi"
            ConductInspection --> CheckPhysical[Cek Kondisi Fisik]
            CheckPhysical --> TestFunction[Test Fungsi]
            TestFunction --> VerifySafety[Verifikasi Keamanan]
            VerifySafety --> CheckCleanliness[Cek Kebersihan]
        end

        CreateReport --> RecommendActions[Rekomendasikan Tindakan]
        RecommendActions --> ScheduleMaintenance[Jadwalkan Pemeliharaan]
    end
```

### Diagram Aktivitas Detail: Pelaporan & Analitik

```mermaid
flowchart LR
    subgraph "Sistem Pelaporan"
        RS_Start([Mulai]) --> GenerateReports[Generate Laporan]
        GenerateReports --> AnalyzeData[Analisis Data]
        AnalyzeData --> ExportResults[Export Hasil]

        subgraph "Generasi Laporan"
            GenerateReports --> SelectType[Pilih Tipe Laporan]
            SelectType --> SetDateRange[Atur Rentang Tanggal]
            SetDateRange --> ApplyFilters[Terapkan Filter]
            ApplyFilters --> ExecuteQuery[Eksekusi Query]
            ExecuteQuery --> CompileData[Kompilasi Data]
        end

        subgraph "Analisis Data"
            AnalyzeData --> CalculateMetrics[Hitung Metrik]
            CalculateMetrics --> IdentifyTrends[Identifikasi Tren]
            IdentifyTrends --> GenerateInsights[Generate Wawasan]
            GenerateInsights --> CreateVisualizations[Buat Visualisasi]
        end

        subgraph "Proses Export"
            ExportResults --> ChooseFormat[Pilih Format]
            ChooseFormat --> PrepareData[Siapkan Data]
            PrepareData --> GenerateFile[Generate File]
            GenerateFile --> DownloadFile[Download File]
        end
    end

    subgraph "Manajemen Insiden"
        IM_Start([Mulai]) --> ReportIncident[Lapor Insiden]
        ReportIncident --> ProcessIncident[Proses Insiden]
        ProcessIncident --> ResolveIncident[Selesaikan Insiden]

        subgraph "Pelaporan Insiden"
            ReportIncident --> FillDetails[Isi Detail]
            FillDetails --> UploadEvidence[Upload Bukti]
            UploadEvidence --> Categorize[Kategorisasi]
            Categorize --> SetPriority[Atur Prioritas]
            SetPriority --> SubmitReport[Submit Laporan]
        end

        subgraph "Proses Insiden"
            ProcessIncident --> Review[Review Insiden]
            Review --> AssignHandler[Assign Penangan]
            AssignHandler --> Investigate[Investigasi Penyebab]
            Investigate --> DetermineSolution[Tentukan Solusi]
        end

        subgraph "Proses Resolusi"
            ResolveIncident --> Implement[Implement Solusi]
            Implement --> Verify[Verifikasi Resolusi]
            Verify --> Document[Dokumentasi Resolusi]
            Document --> Close[Tutup Insiden]
        end
    end

    subgraph "Dashboard Analitik"
        AD_Start([Mulai]) --> LoadDashboard[Load Dashboard]
        LoadDashboard --> RefreshData[Refresh Data]
        RefreshData --> DisplayMetrics[Tampilkan Metrik]

        subgraph "Tampilan Metrik"
            DisplayMetrics --> ShowStats[Tampilkan Statistik]
            ShowStats --> DisplayTrends[Tampilkan Tren]
            DisplayTrends --> ShowIndicators[Tampilkan Indikator]
            ShowIndicators --> HighlightAlerts[Highlight Peringatan]
        end

        DisplayMetrics --> GenerateRecommendations[Generate Rekomendasi]
        GenerateRecommendations --> ExportInsights[Export Wawasan]
    end
```

### Diagram Aktivitas Detail: Pemeliharaan

```mermaid
flowchart LR
    subgraph "Manajemen Pemeliharaan"
        MM_Start([Mulai]) --> PlanMaintenance[Perencanaan Pemeliharaan]
        PlanMaintenance --> ExecuteMaintenance[Eksekusi Pemeliharaan]
        ExecuteMaintenance --> TrackMaintenance[Monitor Pemeliharaan]

        subgraph "Proses Perencanaan"
            PlanMaintenance --> IdentifyNeeds[Identifikasi Kebutuhan]
            IdentifyNeeds --> ScheduleDate[Jadwalkan Tanggal]
            ScheduleDate --> AssignTeam[Assign Tim]
            AssignTeam --> PrepareResources[Siapkan Sumber Daya]
            PrepareResources --> CreateWorkOrder[Buat Work Order]
        end

        subgraph "Proses Eksekusi"
            ExecuteMaintenance --> PreCheck[Check Pra-Pemeliharaan]
            PreCheck --> PerformTasks[Lakukan Tugas]
            PerformTasks --> PostCheck[Check Pasca-Pemeliharaan]
            PostCheck --> Document[Dokumentasi]
        end

        subgraph "Proses Pemantauan"
            TrackMaintenance --> MonitorProgress[Monitor Kemajuan]
            MonitorProgress --> UpdateStatus[Update Status]
            UpdateStatus --> RecordTime[Catat Waktu]
            RecordTime --> EvaluateQuality[Evaluasi Kualitas]
        end
    end

    subgraph "Pemeliharaan Preventif"
        PM_Start([Mulai]) --> CreateSchedule[Buat Jadwal]
        CreateSchedule --> SetRecurring[Atur Berulang]
        SetRecurring --> ConfigureReminders[Konfigurasi Pengingat]
        ConfigureReminders --> GenerateCalendar[Generate Kalender]

        subgraph "Pembuatan Jadwal"
            CreateSchedule --> DefineFrequency[Definisikan Frekuensi]
            DefineFrequency --> ListItems[Daftar Item]
            ListItems --> EstimateDuration[Estimasi Durasi]
            EstimateDuration --> AllocateResources[Alokasikan Sumber Daya]
        end

        GenerateCalendar --> SendAlerts[Kirim Peringatan]
        SendAlerts --> TrackCompliance[Monitor Kepatuhan]
        TrackCompliance --> GenerateComplianceReport[Generate Laporan]
    end

    subgraph "Pemeliharaan Darurat"
        EM_Start([Mulai]) --> ReceiveReport[Terima Laporan]
        ReceiveReport --> AssessSeverity[Asses Keparahan]
        AssessSeverity --> DeployTeam[Deploy Tim]
        DeployTeam --> ExecuteRepair[Eksekusi Perbaikan]

        subgraph "Assesmen Keparahan"
            AssessSeverity --> EvaluateRisk[Evaluasi Risiko]
            EvaluateRisk --> DetermineUrgency[Tentukan Urgensi]
            DetermineUrgency --> ClassifyType[Klasifikasikan Tipe]
        end

        ExecuteRepair --> DocumentActions[Dokumentasi Aksi]
        DocumentActions --> RootCauseAnalysis[Analisis Akar Masalah]
        RootCauseAnalysis --> ImplementPreventive[Implement Preventif]
    end
```

## 🙏 Ucapan Terima Kasih

- Tim pengembang Laravel dan komunitas open source
- FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS LAMBUNG MANGKURAT
- Semua pihak yang telah mendukung pengembangan sistem ini

## 📞 Kontak

Untuk informasi lebih lanjut tentang sistem ini, silakan hubungi:

**Fakultas Ekonomi dan Bisnis**
**Universitas Lambung Mangkurat**

- Email: [feb@ulm.ac.id](mailto:feb@ulm.ac.id)
- Website: [https://feb.ulm.ac.id](https://feb.ulm.ac.id)

---

© 2025 FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS LAMBUNG MANGKURAT. All rights reserved.

**APELKom** - Aplikasi PEminjaman Laboratorium Komputer
