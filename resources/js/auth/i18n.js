import { createI18n } from "vue-i18n";
import id from "@auth/Locales/id.json";
import en from "@auth/Locales/en.json";

const languages = {
    id: id,
    en: en,
};

const messages = Object.assign(languages);
const locale = import.meta.env.VITE_APP_LOCALE;
const fallbackLocale = import.meta.env.VITE_APP_FALLBACK_LOCALE;

const i18n = createI18n({
    locale: locale,
    fallbackLocale: fallbackLocale,
    messages,
});

export default i18n;
