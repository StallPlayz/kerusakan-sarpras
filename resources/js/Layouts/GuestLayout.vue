<script setup>
import { Link } from "@inertiajs/vue3";
import { Wrench } from "@lucide/vue";
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 antialiased relative overflow-hidden text-slate-800"
    >
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-60"
        ></div>

        <div class="relative z-10 w-full sm:max-w-md space-y-6">
            <div class="flex flex-col items-center justify-center text-center">
                <Link href="/" class="inline-flex items-center gap-2.5 group">
                    <div
                        class="bg-slate-900 text-white p-2.5 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-200"
                    >
                        <Wrench class="w-5 h-5" />
                    </div>
                    <span
                        class="font-black text-slate-900 tracking-tight text-2xl"
                    >
                        Sarpras<span class="text-blue-600">Alert</span>
                    </span>
                </Link>
                <p
                    class="text-xs text-slate-400 font-medium mt-2 uppercase tracking-wider"
                >
                    Aplikasi Keluhan Fasilitas & Proteksi IoT
                </p>
            </div>

            <div
                class="w-full bg-white px-8 py-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200 rounded-2xl"
            >
                <slot />
            </div>
        </div>
    </div>
</template>
