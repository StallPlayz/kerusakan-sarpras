<template>
    <div class="flex items-center gap-2" v-bind="$attrs">
        <div
            class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-900 text-white shrink-0 shadow-sm"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path
                    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"
                />
            </svg>
        </div>

        <span class="text-xl font-extrabold tracking-tight text-slate-900">
            Sarpras<span class="text-blue-600">Alert</span>
        </span>
    </div>
</template>
