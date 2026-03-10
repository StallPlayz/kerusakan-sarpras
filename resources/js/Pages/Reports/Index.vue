<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    reports: Array,
});
</script>

<template>
    <Head title="Riwayat Laporan Saya" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Laporan Saya
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"
                >
                    <div v-if="reports.length === 0" class="text-center py-10">
                        <p class="text-gray-500 mb-4">
                            Anda belum pernah membuat laporan kerusakan.
                        </p>
                        <Link
                            :href="route('reports.create')"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                        >
                            Buat Laporan Pertama
                        </Link>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100"
                            >
                                <tr>
                                    <th class="px-6 py-3 rounded-tl-lg">
                                        Tanggal Lapor
                                    </th>
                                    <th class="px-6 py-3">Barang / Ruangan</th>
                                    <th class="px-6 py-3">Deskripsi</th>
                                    <th class="px-6 py-3 rounded-tr-lg">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="report in reports"
                                    :key="report.id"
                                    class="bg-white border-b hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">
                                        {{
                                            new Date(
                                                report.created_at,
                                            ).toLocaleDateString("id-ID")
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ report.item_or_room }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ report.description }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-3 py-1 text-xs font-bold rounded-full"
                                            :class="{
                                                'bg-red-100 text-red-700':
                                                    report.status ===
                                                    'Belum Dikonfirmasi',
                                                'bg-yellow-100 text-yellow-700':
                                                    report.status ===
                                                    'Diproses',
                                                'bg-green-100 text-green-700':
                                                    report.status === 'Selesai',
                                            }"
                                        >
                                            {{ report.status }}
                                        </span>
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
