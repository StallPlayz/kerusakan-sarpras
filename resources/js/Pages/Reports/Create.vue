<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Wrench, MapPin, Layers, FileText, Camera, Send, X } from "@lucide/vue";

const form = useForm({
    category: "",
    room_name: "",
    item_name: "",
    description: "",
    image: null,
});

const imagePreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
};

const submit = () => {
    form.post(route("reports.store"), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Buat Laporan Kerusakan" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-left">
                <h1
                    class="text-2xl font-black text-slate-950 tracking-tight flex items-center gap-2"
                >
                    <Wrench class="w-6 h-6 text-slate-950" /> Buat Laporan
                    Kendala
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Laporkan kerusakan fasilitas atau sarana prasarana sekolah
                    agar segera ditangani oleh Admin.
                </p>
            </div>

            <div
                class="bg-white border border-slate-200 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] overflow-hidden"
            >
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5"
                            >
                                <Layers class="w-3.5 h-3.5 text-slate-800" />
                                Kategori Fasilitas
                            </label>
                            <select
                                v-model="form.category"
                                class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-slate-900 focus:ring-slate-900 transition-colors"
                                required
                            >
                                <option value="" disabled selected>
                                    Pilih Kategori...
                                </option>
                                <option value="Kelistrikan & Lampu">
                                    Kelistrikan & Lampu
                                </option>
                                <option
                                    value="Perangkat Elektronik (AC/Proyektor)"
                                >
                                    Perangkat Elektronik (AC/Proyektor)
                                </option>
                                <option value="Furnitur & Meja Bangku">
                                    Furnitur & Meja Bangku
                                </option>
                                <option
                                    value="Fasilitas Ruangan (Pintu/Jendela)"
                                >
                                    Fasilitas Ruangan (Pintu/Jendela)
                                </option>
                                <option value="Infrastruktur IoT / Jaringan">
                                    Infrastruktur IoT / Jaringan
                                </option>
                            </select>
                            <InputError
                                :message="form.errors.category"
                                class="mt-1"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5"
                            >
                                <MapPin class="w-3.5 h-3.5 text-slate-800" />
                                Lokasi Ruangan
                            </label>
                            <input
                                v-model="form.room_name"
                                type="text"
                                placeholder="Contoh: Ruang F205, Lab RPL"
                                class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-slate-900 focus:ring-slate-900 transition-colors"
                                required
                            />
                            <InputError
                                :message="form.errors.room_name"
                                class="mt-1"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5"
                        >
                            <Wrench class="w-3.5 h-3.5 text-slate-800" /> Nama
                            Barang / Fasilitas
                        </label>
                        <input
                            v-model="form.item_name"
                            type="text"
                            placeholder="Contoh: AC Sharp PK-02, Saklar Lampu Utama"
                            class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-slate-900 focus:ring-slate-900 transition-colors"
                            required
                        />
                        <InputError
                            :message="form.errors.item_name"
                            class="mt-1"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5"
                        >
                            <FileText class="w-3.5 h-3.5 text-slate-800" />
                            Deskripsi Kerusakan
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Jelaskan secara detail mengenai kondisi kerusakan barang agar memudahkan tim teknisi..."
                            class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-slate-900 focus:ring-slate-900 transition-colors resize-none"
                            required
                        ></textarea>
                        <InputError
                            :message="form.errors.description"
                            class="mt-1"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5"
                        >
                            <Camera class="w-3.5 h-3.5 text-slate-800" /> Foto
                            Bukti Kendala (Opsional)
                        </label>

                        <div class="mt-1 flex items-center gap-4">
                            <label
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg shadow-sm text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors"
                            >
                                <Camera class="w-4 h-4 text-slate-600" />
                                <span>Pilih Gambar</span>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageChange"
                                    class="hidden"
                                />
                            </label>

                            <span class="text-xs text-slate-400 font-medium"
                                >Format: JPG, PNG, JPEG. Maksimal 5MB.</span
                            >
                        </div>
                        <InputError :message="form.errors.image" class="mt-1" />

                        <div
                            v-if="imagePreview"
                            class="mt-4 relative inline-block border border-slate-200 rounded-xl overflow-hidden shadow-sm bg-slate-50 max-w-xs"
                        >
                            <img
                                :src="imagePreview"
                                class="h-40 w-auto object-cover"
                            />
                            <button
                                type="button"
                                @click="removeImage"
                                class="absolute top-2 right-2 p-1.5 bg-slate-900/80 text-white rounded-full hover:bg-slate-950 transition-colors shadow"
                                title="Hapus Gambar"
                            >
                                <X class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div
                        class="flex justify-end border-t border-slate-100 pt-5 mt-4"
                    >
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 disabled:opacity-50 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-slate-900/10 group"
                        >
                            <Send
                                class="w-4 h-4 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                            />
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
