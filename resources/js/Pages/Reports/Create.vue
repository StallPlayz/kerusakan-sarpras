<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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

                            <div>
                                <InputLabel for="image" value="Foto Bukti (Opsional namun disarankan)" class="pt-6"/>
                                <input
                                    type="file"
                                    id="image"
                                    @input="form.image = $event.target.files[0]"
                                    accept="image/jpeg, image/png, image/jpg"
                                    class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                />
                                <InputError class="mt-2" :message="form.errors.image" />
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 5MB.</p>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Kirim Laporan
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
