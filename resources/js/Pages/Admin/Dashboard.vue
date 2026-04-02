<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Swal from "sweetalert2";

const showModal = ref(false); // Default: modal tertutup

const selectedReport = ref(null); // Default: belum ada laporan yang dipilih

// Fungsi untuk membuka modal dan memasukkan data laporan yang diklik
const openDetail = (report) => {
    selectedReport.value = report;
    showModal.value = true;
};

const props = defineProps({
    reports: Array,
    users: Array,
    alarm_logs: Array,
});

const showToast = (message) => {
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
};

const updateStatus = (reportId, newStatus) => {
    router.patch(
        route("admin.reports.update", reportId),
        { status: newStatus },
        {
            preserveScroll: true,
            onSuccess: () => showToast("Status tiket berhasil diperbarui!"),
        },
    );
};

const updateRole = (userId, newRole) => {
    router.patch(
        route("admin.users.role.update", userId),
        { role: newRole },
        {
            preserveScroll: true,
            onSuccess: () => showToast("Role user berhasil diubah!"),
        },
    );
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Admin (Sarpras & IoT)
            </h2>
        </template>

        <div class="py-12 space-y-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-blue-500"
                >
                    <h3 class="text-lg font-bold mb-4">
                        Daftar Tiket Laporan Kerusakan
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50"
                            >
                                <tr
                                    v-for="report in reports"
                                    :key="report.id"
                                    class="border-b"
                                >
                                    <td class="px-6 py-4">
                                        {{ report.user.name }}
                                    </td>

                                    <td class="px-6 py-4 font-bold">
                                        {{ report.ruang }} - {{ report.barang }}
                                    </td>

                                    <td
                                        class="px-6 py-4 max-w-xs truncate text-gray-500"
                                        :title="report.deskripsi"
                                    >
                                        {{ report.deskripsi }}
                                    </td>

                                    <td class="px-6 py-4"></td>

                                    <td
                                        class="px-6 py-4 flex space-x-2 items-center"
                                    >
                                        <button
                                            @click="openDetail(report)"
                                            class="text-indigo-600 hover:text-indigo-900 font-bold underline text-sm mr-3"
                                        >
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="report in reports"
                                    :key="report.id"
                                    class="bg-white border-b"
                                >
                                    <td
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ report.user.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ report.item_or_room }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ report.description }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full"
                                            :class="{
                                                'bg-red-100 text-red-800':
                                                    report.status ===
                                                    'Belum Dikonfirmasi',
                                                'bg-yellow-100 text-yellow-800':
                                                    report.status ===
                                                    'Diproses',
                                                'bg-green-100 text-green-800':
                                                    report.status === 'Selesai',
                                            }"
                                        >
                                            {{ report.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <button
                                            @click="
                                                updateStatus(
                                                    report.id,
                                                    'Diproses',
                                                )
                                            "
                                            :disabled="
                                                report.status === 'Diproses' ||
                                                report.status === 'Selesai'
                                            "
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-bold rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed transition-all"
                                        >
                                            Proses
                                        </button>

                                        <button
                                            @click="
                                                updateStatus(
                                                    report.id,
                                                    'Selesai',
                                                )
                                            "
                                            :disabled="
                                                report.status === 'Selesai'
                                            "
                                            class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed transition-all"
                                        >
                                            Selesai
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-red-500"
                >
                    <h3 class="text-lg font-bold mb-4 text-red-600">
                        Riwayat Alarm Kebakaran ESP32 (F205)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50"
                            >
                                <tr>
                                    <th class="px-6 py-3">Ruangan</th>
                                    <th class="px-6 py-3">Level Asap</th>
                                    <th class="px-6 py-3">Waktu Kejadian</th>
                                    <th class="px-6 py-3">Waktu Dipadamkan</th>
                                    <th class="px-6 py-3">
                                        Dimatikan Oleh (RFID)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="log in alarm_logs"
                                    :key="log.id"
                                    class="bg-white border-b"
                                >
                                    <td class="px-6 py-4">
                                        {{ log.room_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ log.smoke_level }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{
                                            new Date(
                                                log.triggered_at,
                                            ).toLocaleString()
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{
                                            log.resolved_at
                                                ? new Date(
                                                      log.resolved_at,
                                                  ).toLocaleString()
                                                : "BELUM PADAM!"
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ log.resolved_by_rfid || "-" }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500"
                >
                    <h3 class="text-lg font-bold mb-4">
                        Manajemen Role & User
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50"
                            >
                                <tr>
                                    <th class="px-6 py-3">Nama</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">RFID UID</th>
                                    <th class="px-6 py-3">Role Saat Ini</th>
                                    <th class="px-6 py-3">Aksi (Ubah Role)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in users"
                                    :key="user.id"
                                    class="bg-white border-b"
                                >
                                    <td class="px-6 py-4">{{ user.name }}</td>
                                    <td class="px-6 py-4">{{ user.email }}</td>
                                    <td class="px-6 py-4">
                                        {{ user.rfid_uid || "Belum Terdaftar" }}
                                    </td>
                                    <td
                                        class="px-6 py-4 font-bold uppercase"
                                        :class="
                                            user.role === 'admin'
                                                ? 'text-purple-600'
                                                : 'text-gray-600'
                                        "
                                    >
                                        {{ user.role }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <select
                                            @change="
                                                updateRole(
                                                    user.id,
                                                    $event.target.value,
                                                )
                                            "
                                            class="text-sm border-gray-300 rounded-md"
                                        >
                                            <option value="" disabled selected>
                                                Ubah Role...
                                            </option>
                                            <option
                                                value="user"
                                                :disabled="user.role === 'user'"
                                            >
                                                Jadikan User
                                            </option>
                                            <option
                                                value="admin"
                                                :disabled="
                                                    user.role === 'admin'
                                                "
                                            >
                                                Jadikan Admin
                                            </option>
                                        </select>
                                    </td>
                                    <Link
                                        :href="
                                            route(
                                                'admin.users.destroy',
                                                user.id,
                                            )
                                        "
                                        method="delete"
                                        as="button"
                                        onclick="
                                            return confirm(
                                                'Apakah Anda yakin ingin menghapus user ini secara permanen?',
                                            );
                                        "
                                        class="ml-2 px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600 transition"
                                    >
                                        Hapus
                                    </Link>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm transition-opacity"
        >
            <div
                class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto relative shadow-2xl"
            >
                <button
                    @click="showModal = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-3xl font-bold leading-none"
                >
                    &times;
                </button>

                <h2
                    class="text-2xl font-extrabold mb-4 border-b-2 pb-2 text-gray-800"
                >
                    Detail Laporan Kerusakan
                </h2>

                <div
                    class="space-y-4 text-sm text-gray-700"
                    v-if="selectedReport"
                >
                    <div
                        class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border"
                    >
                        <p>
                            <span class="font-bold text-gray-900 block"
                                >Nama Pelapor:</span
                            >
                            {{ selectedReport.user.name }}
                        </p>
                        <p>
                            <span class="font-bold text-gray-900 block"
                                >Status Saat Ini:</span
                            >
                            {{ selectedReport.status }}
                        </p>
                        <p>
                            <span class="font-bold text-gray-900 block"
                                >Ruangan:</span
                            >
                            {{ selectedReport.room }}
                        </p>
                        <p>
                            <span class="font-bold text-gray-900 block"
                                >Barang:</span
                            >
                            {{ selectedReport.item }}
                        </p>
                    </div>

                    <div>
                        <span class="font-bold text-gray-900 block mb-1"
                            >Deskripsi Lengkap:</span
                        >
                        <p
                            class="bg-gray-100 p-4 rounded-lg border text-gray-800 whitespace-pre-wrap leading-relaxed"
                        >
                            {{ selectedReport.description }}
                        </p>
                    </div>

                    <div
                        v-if="selectedReport.image_path"
                        class="mt-4 border-t pt-4"
                    >
                        <span class="font-bold text-gray-900 block mb-2"
                            >Foto Bukti Keluhan:</span
                        >
                        <img
                            :src="'/storage/' + selectedReport.image_path"
                            alt="Foto Bukti"
                            class="rounded-lg max-h-96 object-contain w-full bg-gray-200 border-2 border-dashed border-gray-300"
                        />
                    </div>
                    <div v-else class="mt-4 border-t pt-4">
                        <p class="text-gray-500 italic">
                            Pelapor tidak melampirkan foto bukti.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        @click="showModal = false"
                        class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-bold transition-colors"
                    >
                        Tutup Jendela
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
