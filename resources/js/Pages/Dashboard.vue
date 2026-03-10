<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Utama
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10"
                >
                    <h3 class="text-2xl font-bold mb-4">
                        Selamat Datang, {{ $page.props.auth.user.name }}!
                    </h3>
                    <p class="text-gray-600 mb-8">
                        Pilih menu di bawah ini untuk memulai aktivitas Anda di
                        sistem Sarpras.
                    </p>

                    <div
                        class="flex justify-center space-x-4"
                        v-if="$page.props.auth.user.role === 'user'"
                    >
                        <Link
                            :href="route('reports.create')"
                            class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700"
                        >
                            + Buat Laporan Kerusakan
                        </Link>
                        <Link
                            :href="route('reports.index')"
                            class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-lg shadow hover:bg-gray-300"
                        >
                            Lihat Riwayat Laporan Saya
                        </Link>
                    </div>

                    <div
                        class="flex justify-center"
                        v-if="$page.props.auth.user.role === 'admin'"
                    >
                        <Link
                            :href="route('admin.dashboard')"
                            class="px-6 py-3 bg-purple-600 text-white font-bold rounded-lg shadow hover:bg-purple-700"
                        >
                            Buka Panel Admin
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
