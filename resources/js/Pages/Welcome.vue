<script setup>
import { Head, Link } from "@inertiajs/vue3";
import {
    Wrench,
    ShieldAlert,
    ArrowRight,
    LogIn,
    LayoutDashboard,
} from "@lucide/vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Selamat Datang" />

    <div
        class="min-h-screen bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white relative overflow-hidden"
    >
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-60"
        ></div>

        <header
            class="relative z-10 max-w-7xl mx-auto px-6 h-20 flex items-center justify-between border-b border-slate-200/60 bg-white/50 backdrop-blur-sm"
        >
            <div class="flex items-center gap-2.5">
                <div class="bg-slate-900 text-white p-2 rounded-lg shadow-sm">
                    <Wrench class="w-5 h-5" />
                </div>
                <span class="font-bold text-slate-900 tracking-tight text-lg"
                    >Sarpras<span class="text-blue-600">Alert</span></span
                >
            </div>

            <nav v-if="canLogin" class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-lg transition-all shadow-sm"
                >
                    <LayoutDashboard class="w-4 h-4" />
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 hover:text-slate-900 font-semibold text-sm transition-colors"
                    >
                        <LogIn class="w-4 h-4" />
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg transition-all shadow-sm"
                    >
                        Daftar Akun
                    </Link>
                </template>
            </nav>
        </header>

        <main
            class="relative z-10 max-w-7xl mx-auto px-6 pt-16 pb-24 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center"
        >
            <div class="lg:col-span-7 space-y-8 text-left">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold tracking-wide"
                >
                    <span
                        class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"
                    ></span>
                    Purwarupa Sistem Terintegrasi Ruang F205
                </div>

                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]"
                >
                    Sistem Pelaporan Sarpras &
                    <span class="text-blue-600">Alarm Kebakaran IoT</span>
                </h1>

                <p class="text-lg text-slate-600 max-w-2xl leading-relaxed">
                    Platform korporat terpadu untuk efisiensi penanganan
                    kerusakan fasilitas sekolah secara cepat serta pemantauan
                    waktu nyata (*real-time*) ancaman suhu ekstrem dan asap
                    melalui perangkat cerdas ESP32.
                </p>

                <div v-if="canLogin" class="flex flex-wrap items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md group"
                    >
                        Masuk ke Dashboard
                        <ArrowRight
                            class="w-5 h-5 transition-transform group-hover:translate-x-1"
                        />
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-md group"
                        >
                            Laporkan Kerusakan Sekarang
                            <ArrowRight
                                class="w-5 h-5 transition-transform group-hover:translate-x-1"
                            />
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-6 py-3.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm"
                        >
                            Registrasi Akun Baru
                        </Link>
                    </template>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div
                    class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 transition-all duration-200 hover:shadow-md"
                >
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-slate-100 text-slate-900 rounded-xl">
                            <Wrench class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-bold text-slate-900 text-base">
                                Manajemen Kerusakan Sarpras
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Kirim laporan kendala fasilitas, ruangan, atau
                                barang elektronik secara instan terintegrasi
                                bukti dokumentasi foto langsung ke panel
                                administrator.
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 transition-all duration-200 hover:shadow-md"
                >
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-slate-100 text-slate-900 rounded-xl">
                            <ShieldAlert class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-bold text-slate-900 text-base">
                                Sistem Deteksi Dini Kebakaran (IoT)
                            </h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Pemantauan log sensor suhu dan kadar kelayakan
                                udara (asap) secara otomatis dari mikrokontroler
                                dengan perlindungan otentikasi RFID
                                terintegrasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer
            class="absolute bottom-0 w-full text-center py-6 border-t border-slate-200/50 text-xs text-slate-400 font-medium"
        >
            &copy; 2026 SarprasAlert System. Restricted Access.
        </footer>
    </div>
</template>
