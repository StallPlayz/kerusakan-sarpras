<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    Ticket,
    Plus,
    MapPin,
    Layers,
    Clock,
    CheckCircle2,
    AlertTriangle,
    Eye,
} from "@lucide/vue";
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    reports: Array,
});

const localReports = ref(props.reports || []);

onMounted(() => {
    window.Echo.channel("reports").listen("ReportUpdated", (e) => {
        const index = localReports.value.findIndex((r) => r.id === e.report.id);
        if (index !== -1) {
            localReports.value[index] = e.report;
        }
    });
});

onUnmounted(() => {
    window.Echo.leaveChannel("reports");
});
</script>

<template>
    <Head title="Riwayat Laporan" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 text-left"
            >
                <div>
                    <h1
                        class="text-2xl font-black text-slate-950 tracking-tight flex items-center gap-2"
                    >
                        <Ticket class="w-6 h-6 text-slate-950" /> Riwayat
                        Laporan Anda
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Pantau status penanganan dan perbaikan fasilitas yang
                        telah Anda laporkan secara real-time.
                    </p>
                </div>

                <div>
                    <Link
                        :href="route('reports.create')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-slate-900/10"
                    >
                        <Plus class="w-4 h-4" />
                        Buat Laporan Baru
                    </Link>
                </div>
            </div>

            <div
                v-if="localReports.length === 0"
                class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm"
            >
                <div
                    class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-4 border border-slate-200"
                >
                    <Ticket class="w-6 h-6 text-slate-400" />
                </div>
                <h3 class="text-base font-bold text-slate-900">
                    Belum ada laporan kendala
                </h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1 mb-6">
                    Semua tiket keluhan fasilitas atau barang yang Anda kirimkan
                    akan muncul di halaman riwayat ini.
                </p>
                <Link
                    :href="route('reports.create')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-semibold rounded-lg transition-colors shadow-sm"
                >
                    Laporkan Sekarang
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div
                    v-for="report in localReports"
                    :key="report.id"
                    class="bg-white border border-slate-200 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.01)] p-6 flex flex-col justify-between transition-all duration-200 hover:shadow-md relative overflow-hidden"
                >
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 text-xs font-semibold border border-slate-200"
                        >
                            <Layers class="w-3.5 h-3.5 text-slate-800" />
                            {{ report.category || "Umum" }}
                        </span>

                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border"
                            :class="{
                                'bg-amber-50 text-amber-800 border-amber-200':
                                    report.status === 'Belum Dikonfirmasi',
                                'bg-blue-50 text-blue-800 border-blue-200':
                                    report.status === 'Diproses',
                                'bg-emerald-50 text-emerald-800 border-emerald-200':
                                    report.status === 'Selesai',
                            }"
                        >
                            <Clock
                                v-if="report.status === 'Belum Dikonfirmasi'"
                                class="w-3.5 h-3.5"
                            />
                            <Clock
                                v-else-if="report.status === 'Diproses'"
                                class="w-3.5 h-3.5"
                            />
                            <CheckCircle2 v-else class="w-3.5 h-3.5" />
                            {{ report.status }}
                        </span>
                    </div>

                    <div class="space-y-2 flex-1">
                        <h3
                            class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight"
                        >
                            {{ report.item_name || report.item || "-" }}
                        </h3>
                        <p
                            class="text-sm text-slate-600 line-clamp-3 leading-relaxed"
                        >
                            {{ report.description }}
                        </p>
                    </div>

                    <hr class="border-slate-100 my-4" />

                    <div
                        class="flex items-center justify-between gap-4 text-xs font-medium text-slate-400"
                    >
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <MapPin class="w-3.5 h-3.5 text-slate-800" />
                            <span>{{
                                report.room_name || report.room || "-"
                            }}</span>
                        </div>

                        <div>
                            {{
                                report.created_at
                                    ? new Date(
                                          report.created_at,
                                      ).toLocaleDateString("id-ID", {
                                          day: "numeric",
                                          month: "short",
                                          year: "numeric",
                                      })
                                    : "-"
                            }}
                        </div>
                    </div>

                    <div
                        v-if="report.image_path"
                        class="absolute bottom-6 right-6 opacity-10 pointer-events-none"
                    ></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
