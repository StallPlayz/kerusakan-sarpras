<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import Swal from "sweetalert2";
import {
    FileText,
    Clock,
    Users,
    X,
    CreditCard,
    Activity,
    Ticket,
    CheckCircle2,
    AlertTriangle,
} from "@lucide/vue";

const showModal = ref(false);
const selectedReport = ref(null);

const openDetail = (report) => {
    selectedReport.value = report;
    showModal.value = true;
};

const props = defineProps({
    reports: Array,
    users: Array,
    alarm_logs: Array,
});

const localReports = ref(props.reports || []);

onMounted(() => {
    window.Echo.channel("reports").listen("ReportCreated", (e) => {
        localReports.value.unshift(e.report);
        alert(`Laporan Baru: ${e.report.item_name} di ${e.report.room_name}!`);
    });
});

onUnmounted(() => {
    window.Echo.leaveChannel("reports");
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
    const index = localReports.value.findIndex(r => r.id === reportId);
    if (index !== -1) {
        localReports.value[index].status = newStatus;
    }

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

const deleteUser = (userId) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Akun user ini akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.users.destroy", userId), {
                preserveScroll: true,
                onSuccess: () =>
                    showToast("User berhasil dihapus secara permanen!"),
            });
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Admin Dashboard" />

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >
                                    Total Laporan Masuk
                                </p>
                                <h3 class="text-3xl font-bold text-slate-800">
                                    {{ reports.length }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-blue-50 text-blue-600 rounded-lg"
                            >
                                <FileText class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >
                                    Menunggu Penanganan
                                </p>
                                <h3 class="text-3xl font-bold text-slate-800">
                                    {{
                                        reports.filter(
                                            (r) => r.status === "pending",
                                        ).length
                                    }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-amber-50 text-amber-600 rounded-lg"
                            >
                                <Clock class="w-6 h-6" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 transition-all duration-200 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >
                                    Total Pengguna Aktif
                                </p>
                                <h3 class="text-3xl font-bold text-slate-800">
                                    {{ users.length }}
                                </h3>
                            </div>
                            <div
                                class="p-3 bg-emerald-50 text-emerald-600 rounded-lg"
                            >
                                <Users class="w-6 h-6" />
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                >
                    <div class="px-6 py-5 border-b border-slate-200 bg-white">
                        <h2
                            class="text-lg font-semibold text-slate-800 flex items-center gap-2"
                        >
                            <Activity class="w-5 h-5 text-black" /> Log Sensor
                            Bahaya (IoT)
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Riwayat deteksi suhu ekstrem dan asap dari perangkat
                            ESP32.
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50/50 border-b border-slate-200"
                                >
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Waktu Kejadian
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Ruangan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Level Asap
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Suhu
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Dimatikan Oleh (RFID)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr
                                    v-for="log in alarm_logs"
                                    :key="log.id"
                                    class="hover:bg-slate-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap"
                                    >
                                        {{
                                            new Date(
                                                log.created_at,
                                            ).toLocaleString("id-ID")
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-slate-900"
                                    >
                                        {{ log.room_name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-slate-700 font-mono"
                                    >
                                        {{ log.smoke_level }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-slate-700 font-mono"
                                    >
                                        {{ log.temperature }} °C
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            v-if="log.status === 'BAHAYA'"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200"
                                        >
                                            Bahaya Aktif
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200"
                                        >
                                            Telah Dimatikan
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-slate-500 font-mono"
                                    >
                                        {{
                                            log.resolved_by_rfid
                                                ? log.resolved_by_rfid
                                                : "-"
                                        }}
                                    </td>
                                </tr>
                                <tr v-if="alarm_logs.length === 0">
                                    <td
                                        colspan="6"
                                        class="px-6 py-8 text-center text-slate-500 text-sm"
                                    >
                                        Belum ada log sensor yang terekam.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                >
                    <div class="px-6 py-5 border-b border-slate-200 bg-white">
                        <h2
                            class="text-lg font-semibold text-slate-800 flex items-center gap-2"
                        >
                            <Ticket class="w-5 h-5 text-black" /> Manajemen
                            Tiket Kerusakan
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Daftar laporan kerusakan fasilitas dari pengguna
                            (Klik baris untuk melihat detail).
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50/50 border-b border-slate-200"
                                >
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Waktu
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Pelapor
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Kategori
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Lokasi Ruangan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Nama Barang
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Status Tiket
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr
                                    v-for="report in localReports"
                                    :key="report.id"
                                    @click="openDetail(report)"
                                    class="hover:bg-slate-50 transition-colors cursor-pointer"
                                >
                                    <td
                                        class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap"
                                    >
                                        {{
                                            report.created_at
                                                ? new Date(
                                                      report.created_at,
                                                  ).toLocaleDateString("id-ID")
                                                : "-"
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-slate-900"
                                    >
                                        {{
                                            report.user?.name || "User Dihapus"
                                        }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-600 text-xs font-medium border border-slate-200"
                                        >
                                            {{ report.category || "-" }}
                                        </span>
                                    </td>

                                    <td
                                        class="px-6 py-4 text-sm text-slate-700"
                                    >
                                        {{ report.room_name || "-" }}
                                    </td>

                                    <td
                                        class="px-6 py-4 text-sm font-medium text-slate-700 max-w-xs truncate"
                                        :title="report.item_name"
                                    >
                                        {{ report.item_name || "-" }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <select
                                            @change="
                                                updateStatus(
                                                    report.id,
                                                    $event.target.value,
                                                )
                                            "
                                            @click.stop
                                            class="text-sm border-slate-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors cursor-pointer"
                                            :class="{
                                                'bg-amber-50 text-amber-800 border-amber-200':
                                                    report.status ===
                                                    'Belum Dikonfirmasi',
                                                'bg-blue-50 text-blue-800 border-blue-200':
                                                    report.status ===
                                                    'Diproses',
                                                'bg-emerald-50 text-emerald-800 border-emerald-200':
                                                    report.status === 'Selesai',
                                            }"
                                        >
                                            <option
                                                value="Belum Dikonfirmasi"
                                                :selected="
                                                    report.status ===
                                                    'Belum Dikonfirmasi'
                                                "
                                            >
                                                Menunggu
                                            </option>
                                            <option
                                                value="Diproses"
                                                :selected="
                                                    report.status === 'Diproses'
                                                "
                                            >
                                                Diproses
                                            </option>
                                            <option
                                                value="Selesai"
                                                :selected="
                                                    report.status === 'Selesai'
                                                "
                                            >
                                                Selesai
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr v-if="localReports.length === 0">
                                    <td
                                        colspan="6"
                                        class="px-6 py-8 text-center text-slate-500 text-sm"
                                    >
                                        Belum ada tiket laporan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                >
                    <div class="px-6 py-5 border-b border-slate-200 bg-white">
                        <h2
                            class="text-lg font-semibold text-slate-800 flex items-center gap-2"
                        >
                            <Users class="w-5 h-5 text-black" /> Manajemen
                            Pengguna
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Atur hak akses, akun, dan kartu RFID pengguna
                            sistem.
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-slate-50/50 border-b border-slate-200"
                                >
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Nama
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        RFID UID
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider"
                                    >
                                        Hak Akses
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right"
                                    >
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr
                                    v-for="user in users || []"
                                    :key="user.id"
                                    class="hover:bg-slate-50 transition-colors"
                                >
                                    <td
                                        class="px-6 py-4 text-sm font-medium text-slate-900"
                                    >
                                        {{ user.name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-sm text-slate-600"
                                    >
                                        {{ user.email }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-mono">
                                        <span
                                            v-if="user.rfid_uid"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200"
                                        >
                                            <CreditCard
                                                class="w-3.5 h-3.5 text-slate-500"
                                            />
                                            {{ user.rfid_uid }}
                                        </span>
                                        <span
                                            v-else
                                            class="text-slate-400 italic text-xs"
                                            >Belum Terdaftar</span
                                        >
                                    </td>

                                    <td class="px-6 py-4">
                                        <select
                                            @change="
                                                updateRole(
                                                    user.id,
                                                    $event.target.value,
                                                )
                                            "
                                            :disabled="
                                                user.id ===
                                                $page.props.auth.user.id
                                            "
                                            class="text-sm border-slate-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:bg-slate-100"
                                        >
                                            <option
                                                value="user"
                                                :selected="user.role === 'user'"
                                            >
                                                Pengguna Biasa
                                            </option>
                                            <option
                                                value="admin"
                                                :selected="
                                                    user.role === 'admin'
                                                "
                                            >
                                                Administrator
                                            </option>
                                        </select>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right text-sm font-medium"
                                    >
                                        <button
                                            v-if="
                                                user.id !==
                                                $page.props.auth.user.id
                                            "
                                            @click="deleteUser(user.id)"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                        <span
                                            v-else
                                            class="text-slate-400 italic text-xs"
                                            >Anda Sendiri</span
                                        >
                                    </td>
                                </tr>
                                <tr v-if="(users || []).length === 0">
                                    <td
                                        colspan="5"
                                        class="px-6 py-8 text-center text-slate-500 text-sm"
                                    >
                                        Belum ada pengguna terdaftar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Transition
            enter-active-class="transition-opacity ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showModal"
                class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm"
                @click="showModal = false"
            ></div>
        </Transition>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none"
            >
                <div
                    class="bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden w-full max-w-2xl relative pointer-events-auto"
                >
                    <div
                        class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50"
                    >
                        <h3 class="text-lg font-bold text-slate-800">
                            Detail Laporan
                        </h3>
                        <button
                            @click="showModal = false"
                            class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                        >
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div
                        class="p-6 max-h-[70vh] overflow-y-auto space-y-5 text-left"
                    >
                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >Pelapor</span
                            >
                            <p class="text-slate-900 font-medium">
                                {{ selectedReport.user.name }}
                                <span class="text-slate-500 font-normal"
                                    >({{ selectedReport.user.email }})</span
                                >
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span
                                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                    >Kategori</span
                                >
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200"
                                >
                                    {{ selectedReport.category }}
                                </span>
                            </div>
                            <div>
                                <span
                                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                    >Lokasi Ruangan</span
                                >
                                <p class="text-slate-900">
                                    {{ selectedReport.room_name }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >Nama Barang</span
                            >
                            <p class="text-slate-900 font-medium">
                                {{ selectedReport?.item_name || "-" }}
                            </p>
                        </div>

                        <div>
                            <span
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1"
                                >Deskripsi Detail</span
                            >
                            <p
                                class="text-slate-700 bg-slate-50 p-4 rounded-lg border border-slate-100 whitespace-pre-wrap leading-relaxed"
                            >
                                {{ selectedReport.description }}
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <span
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3"
                                >Foto Bukti Keluhan</span
                            >
                            <div v-if="selectedReport.image_path">
                                <img
                                    :src="
                                        selectedReport.image_path.startsWith(
                                            'http',
                                        )
                                            ? selectedReport.image_path
                                            : '/storage/' +
                                              selectedReport.image_path
                                    "
                                    alt="Foto Bukti"
                                    class="w-full h-auto rounded-lg border border-slate-200"
                                />
                            </div>
                            <div
                                v-else
                                class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-lg text-center"
                            >
                                <p class="text-slate-500 italic text-sm">
                                    Pelapor tidak melampirkan foto bukti.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end"
                    >
                        <button
                            @click="showModal = false"
                            class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition-colors shadow-sm"
                        >
                            Tutup Jendela
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>
