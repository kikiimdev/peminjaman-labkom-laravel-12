<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APELKom - Aplikasi PEminjaman Laboratorium Komputer FEB UNLAM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .apelkom-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .hero-pattern {
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .dark .hero-pattern {
            background-color: #111827;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="apelkom-gradient bg-clip-text text-transparent text-2xl font-bold mr-2">APELKom</div>
                        <span class="text-xl font-bold text-gray-900">FEB UNLAM</span>
                    </div>
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            <a href="#features" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Fitur</a>
                            <a href="#about" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Tentang</a>
                            <a href="#contact" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Kontak</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center bg-white">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6">
                    <div class="apelkom-gradient bg-clip-text text-transparent">APELKom</div><br>
                    <span class="text-3xl md:text-4xl text-gray-700">Aplikasi PEminjaman Laboratorium Komputer</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 mb-8 leading-relaxed">
                    Solusi modern untuk manajemen peminjaman lab komputer FEB UNLAM.
                    Efisien, terintegrasi, dan mudah digunakan untuk mendukung pembelajaran.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                            Ke Dashboard
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105 shadow-lg">
                            Mulai Sekarang
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ route('login') }}" class="bg-white hover:bg-gray-50 text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold transition-all border border-gray-300">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Fitur Unggulan APELKom
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Sistem terintegrasi untuk mengelola lab komputer FEB UNLAM dengan teknologi terkini
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-indigo-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-alt text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Manajemen Jadwal</h3>
                    <p class="text-gray-600">
                        Kelola jadwal lab komputer dengan sistem approval otomatis dan notifikasi real-time
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-green-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-door-open text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Manajemen Lab</h3>
                    <p class="text-gray-600">
                        Pantau status lab komputer, fasilitas, dan kapasitas dalam satu dashboard yang intuitif
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-purple-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-tools text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Pemeliharaan Lab</h3>
                    <p class="text-gray-600">
                        Jadwalkan pemeliharaan rutin dan pantau status perbaikan fasilitas lab komputer
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-red-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Laporan Insiden</h3>
                    <p class="text-gray-600">
                        Laporkan dan pantau insiden lab komputer dengan sistem tracking lengkap
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-yellow-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-bar text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analitik & Laporan</h3>
                    <p class="text-gray-600">
                        Dapatkan insight mendalam dengan laporan utilisasi dan analitik data real-time
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-xl hover:shadow-lg transition-shadow border border-gray-100">
                    <div class="bg-blue-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Manajemen Pengguna</h3>
                    <p class="text-gray-600">
                        Kontrol akses pengguna dengan sistem role-based yang fleksibel dan aman
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">
                        Tentang APELKom
                    </h2>
                    <p class="text-lg text-gray-600 mb-6">
                        <strong>APELKom</strong> (Aplikasi PEminjaman Laboratorium Komputer) adalah solusi komprehensif untuk manajemen lab komputer FEB UNLAM.
                        Dirancang khusus untuk mendukung proses pembelajaran dan penelitian di Fakultas Ekonomi dan Bisnis Universitas Lambung Mangkurat.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-600">Sistem approval otomatis untuk peminjaman lab komputer</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-600">Dashboard real-time untuk monitoring utilisasi lab</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-600">Integrasi fasilitas dan jadwal pemeliharaan lab</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-600">Laporan lengkap untuk analisis dan perencanaan</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600">5+</div>
                                <div class="text-sm text-gray-600">Lab Komputer</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">50+</div>
                                <div class="text-sm text-gray-600">Unit PC</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-purple-600">1000+</div>
                                <div class="text-sm text-gray-600">Peminjaman/Bulan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-orange-600">24/7</div>
                                <div class="text-sm text-gray-600">System Monitoring</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Siap Menggunakan APELKom?
            </h2>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Akses APELKom sekarang dan nikmati kemudahan manajemen lab komputer untuk mendukung aktivitas pembelajaran dan penelitian Anda.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105 shadow-lg inline-block">
                    Akses Dashboard Anda
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all transform hover:scale-105 shadow-lg inline-block">
                    Daftar Sekarang Gratis
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="apelkom-gradient bg-clip-text text-transparent text-2xl font-bold mr-2">APELKom</div>
                        <span class="text-xl font-bold text-white">FEB UNLAM</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Aplikasi PEminjaman Laboratorium Komputer untuk mendukung pembelajaran dan penelitian di FEB UNLAM.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold mb-4 text-white">Fitur</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Manajemen Jadwal</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Monitoring Lab</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Laporan Analitik</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4 text-white">Fakultas</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4 text-white">Legal</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Peminjaman Lab') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth scrolling script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>