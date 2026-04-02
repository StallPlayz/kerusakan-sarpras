<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const form = useForm({
    room: "",
    item: "",
    description: "",
    image: null,
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
                            <InputLabel
                                for="room"
                                value="Nama Ruangan (Contoh: Lab Komputer 1)"
                            />
                            <TextInput
                                id="room"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.room"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.room"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="item"
                                value="Nama Barang (Contoh: AC Panasonic / PC No. 12)"
                            />
                            <TextInput
                                id="item"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.item"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.item"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="description"
                                value="Deskripsi Kerusakan"
                            />
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.description"
                                rows="3"
                                required
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
