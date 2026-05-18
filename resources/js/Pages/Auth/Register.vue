<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import vueRecaptcha from "vue3-recaptcha2";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const recaptchaSiteKey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

const recaptchaWidget = ref(null);

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    "g-recaptcha-response": "",
});

const recaptchaVerified = (response) => {
    form["g-recaptcha-response"] = response;
};

const recaptchaExpired = () => {
    form["g-recaptcha-response"] = "";
};

const submit = () => {
    form.post(route("register"), {
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
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
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
                    :href="route('login')"
                    class="text-xs text-slate-500 hover:text-slate-900 transition-colors"
                >
                    Sudah punya akun? Masuk
                </Link>

                <button
                    type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition-all shadow-sm disabled:opacity-50"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Daftar Akun
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
