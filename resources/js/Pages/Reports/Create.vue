<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const form = useForm({
    item_or_room: "",
    description: "",
});

const submitReport = () => {
    form.post(route("reports.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();

            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Laporan berhasil dikirim ke Admin!",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        },
    });
};
</script>

<template>
    <Head title="Buat Laporan Baru" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Laporan Kerusakan Baru
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-t-4 border-blue-500"
                >
                    <form @submit.prevent="submitReport" class="space-y-6">
                        <div>
                            <label
                                class="block font-bold text-sm text-gray-700 mb-1"
                                >Nama Barang / Ruangan</label
                            >
                            <input
                                v-model="form.item_or_room"
                                type="text"
                                class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                required
                                placeholder="Contoh: Proyektor Ruang F205"
                            />
                        </div>

                        <div>
                            <label
                                class="block font-bold text-sm text-gray-700 mb-1"
                                >Deskripsi Kerusakan</label
                            >
                            <textarea
                                v-model="form.description"
                                class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                rows="4"
                                required
                                placeholder="Contoh: Kabel power putus dan lampu indikator mati."
                            ></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700 transition disabled:opacity-50"
                            >
                                Kirim Laporan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
