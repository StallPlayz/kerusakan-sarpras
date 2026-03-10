<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

// Menerima data dari AdminController
const props = defineProps({
    reports: Array,
    users: Array,
    alarm_logs: Array,
});

// Fungsi untuk mengubah status laporan
const updateStatus = (reportId, newStatus) => {
    router.patch(route('admin.reports.update', reportId), { status: newStatus }, {
        preserveScroll: true,
        onSuccess: () => alert('Status berhasil diperbarui!')
    });
};

// Fungsi untuk mengubah role user
const updateRole = (userId, newRole) => {
    router.patch(route('admin.users.role.update', userId), { role: newRole }, {
        preserveScroll: true,
        onSuccess: () => alert('Role berhasil diubah!')
    });
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin (Sarpras & IoT)</h2>
        </template>

        <div class="py-12 space-y-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold mb-4">Daftar Tiket Laporan Kerusakan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Pelapor</th>
                                    <th class="px-6 py-3">Barang/Ruang</th>
                                    <th class="px-6 py-3">Deskripsi</th>
                                    <th class="px-6 py-3">Status Saat Ini</th>
                                    <th class="px-6 py-3">Aksi (Ubah Status)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="report in reports" :key="report.id" class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ report.user.name }}</td>
                                    <td class="px-6 py-4">{{ report.item_or_room }}</td>
                                    <td class="px-6 py-4">{{ report.description }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full"
                                              :class="{'bg-red-100 text-red-800': report.status === 'Belum Dikonfirmasi', 'bg-yellow-100 text-yellow-800': report.status === 'Diproses', 'bg-green-100 text-green-800': report.status === 'Selesai'}">
                                            {{ report.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <button @click="updateStatus(report.id, 'Diproses')" class="text-yellow-600 hover:underline">Proses</button>
                                        <button @click="updateStatus(report.id, 'Selesai')" class="text-green-600 hover:underline">Selesai</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-red-500">
                    <h3 class="text-lg font-bold mb-4 text-red-600">Riwayat Alarm Kebakaran ESP32 (F205)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Ruangan</th>
                                    <th class="px-6 py-3">Level Asap</th>
                                    <th class="px-6 py-3">Waktu Kejadian</th>
                                    <th class="px-6 py-3">Waktu Dipadamkan</th>
                                    <th class="px-6 py-3">Dimatikan Oleh (RFID)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in alarm_logs" :key="log.id" class="bg-white border-b">
                                    <td class="px-6 py-4">{{ log.room_name }}</td>
                                    <td class="px-6 py-4">{{ log.smoke_level }}</td>
                                    <td class="px-6 py-4">{{ new Date(log.triggered_at).toLocaleString() }}</td>
                                    <td class="px-6 py-4">{{ log.resolved_at ? new Date(log.resolved_at).toLocaleString() : 'BELUM PADAM!' }}</td>
                                    <td class="px-6 py-4">{{ log.resolved_by_rfid || '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <h3 class="text-lg font-bold mb-4">Manajemen Role & User</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">RFID UID</th>
                                    <th class="px-6 py-3">Role Saat Ini</th>
                                    <th class="px-6 py-3">Aksi (Ubah Role)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id" class="bg-white border-b">
                                    <td class="px-6 py-4">{{ user.name }}</td>
                                    <td class="px-6 py-4">{{ user.email }}</td>
                                    <td class="px-6 py-4">{{ user.rfid_uid || 'Belum Terdaftar' }}</td>
                                    <td class="px-6 py-4 font-bold uppercase" :class="user.role === 'admin' ? 'text-purple-600' : 'text-gray-600'">{{ user.role }}</td>
                                    <td class="px-6 py-4">
                                        <select @change="updateRole(user.id, $event.target.value)" class="text-sm border-gray-300 rounded-md">
                                            <option value="" disabled selected>Ubah Role...</option>
                                            <option value="user" :disabled="user.role === 'user'">Jadikan User</option>
                                            <option value="admin" :disabled="user.role === 'admin'">Jadikan Admin</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
