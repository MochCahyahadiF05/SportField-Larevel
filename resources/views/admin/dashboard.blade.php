{{--
    Contoh pemakaian layout admin.
    Simpan sebagai resources/views/admin/dashboard.blade.php (atau sesuai struktur kamu).
--}}
<x-admin-layout title="Dashboard" subtitle="Selamat datang kembali, Admin!">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-1">Total Booking</p>
            <p class="text-2xl font-bold text-gray-900">128</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-1">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900">Rp 12.500.000</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-1">Pelanggan Baru</p>
            <p class="text-2xl font-bold text-gray-900">24</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500 mb-1">Lapangan Aktif</p>
            <p class="text-2xl font-bold text-gray-900">8</p>
        </div>
    </div>

    {{-- ...konten dashboard lainnya... --}}

</x-admin-layout>