import i18n from "i18next";
import { initReactI18next } from "react-i18next";

// Import translations
import enTranslations from './locales/en.json';
import kaTranslations from './locales/ka.json';

i18n
    .use(initReactI18next)
    .init({
        resources: {
            en: { translation: enTranslations },
            ka: { translation: kaTranslations },
        },
        lng: "ka",
        fallbackLng: "ka",
        interpolation: {
            escapeValue: false,
        },
    });

export default i18n;