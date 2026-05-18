<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import vueRecaptcha from "vue3-recaptcha2";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const recaptchaSiteKey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

const recaptchaWidget = ref(null);

const form = useForm({
    email: "",
    password: "",
    remember: false,
    "g-recaptcha-response": "",
});

const recaptchaVerified = (response) => {
    form["g-recaptcha-response"] = response;
};

const recaptchaExpired = () => {
    form["g-recaptcha-response"] = "";
};

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password", "g-recaptcha-response");
            if (recaptchaWidget.value) {
                recaptchaWidget.value.reset();
            }
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="mt-4 flex flex-col items-center">
                <vue-recaptcha
                    ref="recaptchaWidget"
                    :sitekey="recaptchaSiteKey"
                    @verify="recaptchaVerified"
                    @expire="recaptchaExpired"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors['g-recaptcha-response']"
                />
            </div>

            <div
                class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4"
            >
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-xs text-slate-500 hover:text-slate-900 transition-colors"
                >
                    Lupa password Anda?
                </Link>

                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm disabled:opacity-50"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Masuk Aplikasi
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
