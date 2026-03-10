<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Inertia useForm untuk menangani input data
const form = useForm({
    item_or_room: '',
    description: '',
});

// Fungsi untuk mengirim data ke ReportController
const submitReport = () => {
    form.post(route('reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            alert('Laporan berhasil dikirim ke Admin!');
        },
    });
};
</script>

<template>
    <Head title="Dashboard User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Homepage User - Pelaporan Sarpras</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Laporkan Kerusakan Baru</h3>

                    <form @submit.prevent="submitReport" class="space-y-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nama Barang / Ruangan</label>
                            <input v-model="form.item_or_room" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Contoh: AC Ruang F205">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Deskripsi Kerusakan</label>
                            <textarea v-model="form.description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required placeholder="Contoh: AC meneteskan air dan tidak dingin."></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kirim Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
