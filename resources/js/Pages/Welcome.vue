<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Selamat Datang" />
    <div
        class="min-h-screen bg-gray-100 flex flex-col items-center justify-center"
    >
        <div class="text-center max-w-3xl px-6">
            <h1 class="text-5xl font-extrabold text-blue-600 mb-6">
                Sistem Pelaporan Sarpras & Alarm IoT
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Platform terpadu untuk melaporkan kerusakan fasilitas sekolah
                dan memantau peringatan dini kebakaran dari purwarupa ESP32 di
                Ruang F205.
            </p>

            <div v-if="canLogin" class="space-x-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition"
                >
                    Masuk ke Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-6 py-3 bg-white text-blue-600 border border-blue-600 font-bold rounded-lg hover:bg-blue-50 transition"
                    >
                        Daftar Akun
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>
