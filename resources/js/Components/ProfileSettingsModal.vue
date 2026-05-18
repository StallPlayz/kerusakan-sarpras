<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, onMounted, onUnmounted } from "vue";
import { User, Mail, Key, AlertTriangle, X } from "@lucide/vue";

const props = defineProps({ show: Boolean });
const emit = defineEmits(["close"]);

const user = usePage().props.auth.user;

const profileForm = useForm({ name: user.name, email: user.email });
const passwordForm = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});
const deleteForm = useForm({ password: "" });

const showPasswordForm = ref(false);
const showDeleteForm = ref(false);

const updateProfile = () => {
    profileForm.patch(route("profile.update"), {
        preserveScroll: true,
    });
};

const updatePassword = () => {
    passwordForm.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            showPasswordForm.value = false;
        },
    });
};

const deleteAccount = () => {
    deleteForm.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    emit("close");
    showPasswordForm.value = false;
    showDeleteForm.value = false;
    passwordForm.reset();
    deleteForm.reset();
    passwordForm.clearErrors();
    deleteForm.clearErrors();
};

const closeOnOutside = () => {
    if (props.show) {
        closeModal();
    }
};

const closeOnEscape = (e) => {
    if (props.show && e.key === "Escape") {
        closeModal();
    }
};

onMounted(() => {
    document.addEventListener("click", closeOnOutside);
    document.addEventListener("keydown", closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener("click", closeOnOutside);
    document.removeEventListener("keydown", closeOnEscape);
});
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-200 origin-top-right"
        enter-from-class="opacity-0 scale-95 translate-x-4"
        enter-to-class="opacity-100 scale-100 translate-x-0"
        leave-active-class="transition ease-in duration-150 origin-top-right"
        leave-from-class="opacity-100 scale-100 translate-x-0"
        leave-to-class="opacity-0 scale-95 translate-x-4"
    >
        <div
            v-show="show"
            @click.stop
            class="absolute right-[100%] top-0 mr-1 z-50 w-[320px] bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-slate-200 overflow-hidden"
        >
            <div class="p-5">
                <div
                    class="flex justify-between items-center mb-5 border-b border-slate-100 pb-3"
                >
                    <h2 class="text-sm font-bold text-slate-800">
                        Profile Settings
                    </h2>
                    <button
                        @click="closeModal"
                        class="text-slate-400 hover:text-slate-600 transition-colors"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>

                <div class="space-y-4 mb-5">
                    <div class="flex items-center justify-between gap-3">
                        <User class="w-5 h-5 text-slate-500 shrink-0" />
                        <div class="flex-1">
                            <input
                                v-model="profileForm.name"
                                type="text"
                                class="w-full text-sm border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                placeholder="Nama"
                            />
                            <InputError
                                :message="profileForm.errors.name"
                                class="mt-1 text-xs"
                            />
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <Mail class="w-5 h-5 text-slate-500 shrink-0" />
                        <div class="flex-1">
                            <input
                                v-model="profileForm.email"
                                type="email"
                                class="w-full text-sm border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                placeholder="Email"
                            />
                            <InputError
                                :message="profileForm.errors.email"
                                class="mt-1 text-xs"
                            />
                        </div>
                    </div>
                    <div class="flex justify-end mt-1">
                        <button
                            @click="updateProfile"
                            :disabled="profileForm.processing"
                            class="px-3 py-1.5 bg-slate-50 text-slate-700 hover:bg-slate-100 text-xs font-semibold rounded border border-slate-200 transition-colors"
                        >
                            {{
                                profileForm.recentlySuccessful
                                    ? "Saved ✓"
                                    : "Update Info"
                            }}
                        </button>
                    </div>
                </div>

                <hr class="border-slate-100 mb-5" />

                <div class="mb-5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <Key class="w-5 h-5 text-slate-500 shrink-0" />
                            <span class="text-sm font-medium text-slate-700"
                                >Password</span
                            >
                        </div>
                        <button
                            v-if="!showPasswordForm"
                            @click="showPasswordForm = true"
                            class="px-3 py-1.5 bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-semibold rounded transition-colors border border-slate-200"
                        >
                            Change
                        </button>
                    </div>

                    <div
                        v-if="showPasswordForm"
                        class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-100 space-y-3"
                    >
                        <div>
                            <input
                                v-model="passwordForm.current_password"
                                type="password"
                                placeholder="Current Password"
                                class="w-full text-sm border-slate-300 rounded-md shadow-sm"
                            />
                            <InputError
                                :message="passwordForm.errors.current_password"
                                class="mt-1 text-xs"
                            />
                        </div>
                        <div>
                            <input
                                v-model="passwordForm.password"
                                type="password"
                                placeholder="New Password"
                                class="w-full text-sm border-slate-300 rounded-md shadow-sm"
                            />
                            <InputError
                                :message="passwordForm.errors.password"
                                class="mt-1 text-xs"
                            />
                        </div>
                        <div>
                            <input
                                v-model="passwordForm.password_confirmation"
                                type="password"
                                placeholder="Confirm New Password"
                                class="w-full text-sm border-slate-300 rounded-md shadow-sm"
                            />
                            <InputError
                                :message="
                                    passwordForm.errors.password_confirmation
                                "
                                class="mt-1 text-xs"
                            />
                        </div>
                        <div class="flex justify-end gap-2 mt-2">
                            <button
                                @click="showPasswordForm = false"
                                class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-700"
                            >
                                Cancel
                            </button>
                            <button
                                @click="updatePassword"
                                :disabled="passwordForm.processing"
                                class="px-3 py-1.5 bg-slate-800 text-white hover:bg-slate-700 text-xs font-semibold rounded shadow-sm transition-colors"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 mb-5" />

                <div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <AlertTriangle
                                class="w-5 h-5 text-red-500 shrink-0"
                            />
                            <span class="text-sm font-medium text-red-600"
                                >Danger Zone</span
                            >
                        </div>
                        <button
                            v-if="!showDeleteForm"
                            @click="showDeleteForm = true"
                            class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold rounded transition-colors border border-red-200"
                        >
                            Delete
                        </button>
                    </div>

                    <div
                        v-if="showDeleteForm"
                        class="mt-3 p-3 bg-red-50 rounded-lg border border-red-100 space-y-3"
                    >
                        <p class="text-[11px] text-red-600 font-medium mb-1">
                            Account deletion is permanent.
                        </p>
                        <input
                            v-model="deleteForm.password"
                            type="password"
                            placeholder="Password to confirm"
                            class="w-full text-sm border-red-200 rounded-md focus:border-red-500 focus:ring-red-500 shadow-sm"
                            @keyup.enter="deleteAccount"
                        />
                        <InputError
                            :message="deleteForm.errors.password"
                            class="mt-1 text-xs"
                        />
                        <div class="flex justify-end gap-2 mt-2">
                            <button
                                @click="showDeleteForm = false"
                                class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-700"
                            >
                                Cancel
                            </button>
                            <button
                                @click="deleteAccount"
                                :disabled="deleteForm.processing"
                                class="px-3 py-1.5 bg-red-600 text-white hover:bg-red-700 text-xs font-semibold rounded shadow-sm transition-colors"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
