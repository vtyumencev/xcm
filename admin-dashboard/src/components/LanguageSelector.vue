<script setup lang="ts">

import {computed, ref} from "vue";

const props = defineProps<{
    languageSettings: {
        current: string,
        list: string[]
    },
    modelValue: string
}>();


const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>();

const value = ref(props.modelValue);

const languages = [
    {
        code: 'af',
        english_name: 'Afrikaans',
        native_name: 'Afrikaans'
    },
    {
        code: 'ar',
        english_name: 'Arabic',
        native_name: 'العربية'
    },
    {
        code: 'ary',
        english_name: 'Moroccan Arabic',
        native_name: 'العربية المغربية'
    },
    {
        code: 'as',
        english_name: 'Assamese',
        native_name: 'অসমীয়া'
    },
    {
        code: 'az',
        english_name: 'Azerbaijani',
        native_name: 'Azərbaycan dili'
    },
    {
        code: 'azb',
        english_name: 'South Azerbaijani',
        native_name: 'گؤنئی آذربایجان'
    },
    {
        code: 'bel',
        english_name: 'Belarusian',
        native_name: 'Беларуская мова'
    },
    {
        code: 'bg_BG',
        english_name: 'Bulgarian',
        native_name: 'Български'
    },
    {
        code: 'bn_BD',
        english_name: 'Bengali (Bangladesh)',
        native_name: 'বাংলা'
    },
    {
        code: 'bo',
        english_name: 'Tibetan',
        native_name: 'བོད་ཡིག'
    },
    {
        code: 'bs_BA',
        english_name: 'Bosnian',
        native_name: 'Bosanski'
    },
    {
        code: 'ca',
        english_name: 'Catalan',
        native_name: 'Català'
    },
    {
        code: 'ceb',
        english_name: 'Cebuano',
        native_name: 'Cebuano'
    },
    {
        code: 'ckb',
        english_name: 'Kurdish (Sorani)',
        native_name: 'كوردی‎'
    },
    {
        code: 'cs_CZ',
        english_name: 'Czech',
        native_name: 'Čeština'
    },
    {
        code: 'cy',
        english_name: 'Welsh',
        native_name: 'Cymraeg'
    },
    {
        code: 'da_DK',
        english_name: 'Danish',
        native_name: 'Dansk'
    },
    {
        code: 'de_AT',
        english_name: 'German (Austria)',
        native_name: 'Deutsch (Österreich)'
    },
    {
        code: 'de_CH',
        english_name: 'German (Switzerland)',
        native_name: 'Deutsch (Schweiz)'
    },
    {
        code: 'de_CH_informal',
        english_name: 'German (Switzerland, Informal)',
        native_name: 'Deutsch (Schweiz, Du)'
    },
    {
        code: 'de_DE',
        english_name: 'German',
        native_name: 'Deutsch'
    },
    {
        code: 'de_DE_formal',
        english_name: 'German (Formal)',
        native_name: 'Deutsch (Sie)'
    },
    {
        code: 'dzo',
        english_name: 'Dzongkha',
        native_name: 'རྫོང་ཁ'
    },
    {
        code: 'el',
        english_name: 'Greek',
        native_name: 'Ελληνικά'
    },
    {
        code: 'en_AU',
        english_name: 'English (Australia)',
        native_name: 'English (Australia)'
    },
    {
        code: 'en_CA',
        english_name: 'English (Canada)',
        native_name: 'English (Canada)'
    },
    {
        code: 'en_GB',
        english_name: 'English (UK)',
        native_name: 'English (UK)'
    },
    {
        code: 'en_NZ',
        english_name: 'English (New Zealand)',
        native_name: 'English (New Zealand)'
    },
    {
        code: 'en_US',
        english_name: 'English (United States)',
        native_name: 'English (United States)'
    },
    {
        code: 'en_ZA',
        english_name: 'English (South Africa)',
        native_name: 'English (South Africa)'
    },
    {
        code: 'eo',
        english_name: 'Esperanto',
        native_name: 'Esperanto'
    },
    {
        code: 'es_AR',
        english_name: 'Spanish (Argentina)',
        native_name: 'Español de Argentina'
    },
    {
        code: 'es_CL',
        english_name: 'Spanish (Chile)',
        native_name: 'Español de Chile'
    },
    {
        code: 'es_CO',
        english_name: 'Spanish (Colombia)',
        native_name: 'Español de Colombia'
    },
    {
        code: 'es_CR',
        english_name: 'Spanish (Costa Rica)',
        native_name: 'Español de Costa Rica'
    },
    {
        code: 'es_ES',
        english_name: 'Spanish (Spain)',
        native_name: 'Español'
    },
    {
        code: 'es_GT',
        english_name: 'Spanish (Guatemala)',
        native_name: 'Español de Guatemala'
    },
    {
        code: 'es_MX',
        english_name: 'Spanish (Mexico)',
        native_name: 'Español de México'
    },
    {
        code: 'es_PE',
        english_name: 'Spanish (Peru)',
        native_name: 'Español de Perú'
    },
    {
        code: 'es_UY',
        english_name: 'Spanish (Uruguay)',
        native_name: 'Español de Uruguay'
    },
    {
        code: 'es_VE',
        english_name: 'Spanish (Venezuela)',
        native_name: 'Español de Venezuela'
    },
    {
        code: 'et',
        english_name: 'Estonian',
        native_name: 'Eesti'
    },
    {
        code: 'eu',
        english_name: 'Basque',
        native_name: 'Euskara'
    },
    {
        code: 'fa_IR',
        english_name: 'Persian',
        native_name: 'فارسی'
    },
    {
        code: 'fi',
        english_name: 'Finnish',
        native_name: 'Suomi'
    },
    {
        code: 'fr_BE',
        english_name: 'French (Belgium)',
        native_name: 'Français de Belgique'
    },
    {
        code: 'fr_CA',
        english_name: 'French (Canada)',
        native_name: 'Français du Canada'
    },
    {
        code: 'fr_FR',
        english_name: 'French (France)',
        native_name: 'Français'
    },
    {
        code: 'fur',
        english_name: 'Friulian',
        native_name: 'Friulian'
    },
    {
        code: 'gd',
        english_name: 'Scottish Gaelic',
        native_name: 'Gàidhlig'
    },
    {
        code: 'gl_ES',
        english_name: 'Galician',
        native_name: 'Galego'
    },
    {
        code: 'gu',
        english_name: 'Gujarati',
        native_name: 'ગુજરાતી'
    },
    {
        code: 'haz',
        english_name: 'Hazaragi',
        native_name: 'هزاره گی'
    },
    {
        code: 'he_IL',
        english_name: 'Hebrew',
        native_name: 'עִבְרִית'
    },
    {
        code: 'hi_IN',
        english_name: 'Hindi',
        native_name: 'हिन्दी'
    },
    {
        code: 'hr',
        english_name: 'Croatian',
        native_name: 'Hrvatski'
    },
    {
        code: 'hsb',
        english_name: 'Upper Sorbian',
        native_name: 'Hornjoserbšćina'
    },
    {
        code: 'hu_HU',
        english_name: 'Hungarian',
        native_name: 'Magyar'
    },
    {
        code: 'hy',
        english_name: 'Armenian',
        native_name: 'Հայերեն'
    },
    {
        code: 'id_ID',
        english_name: 'Indonesian',
        native_name: 'Bahasa Indonesia'
    },
    {
        code: 'is_IS',
        english_name: 'Icelandic',
        native_name: 'Íslenska'
    },
    {
        code: 'it_IT',
        english_name: 'Italian',
        native_name: 'Italiano'
    },
    {
        code: 'ja',
        english_name: 'Japanese',
        native_name: '日本語'
    },
    {
        code: 'jv_ID',
        english_name: 'Javanese',
        native_name: 'Basa Jawa'
    },
    {
        code: 'kab',
        english_name: 'Kabyle',
        native_name: 'Taqbaylit'
    },
    {
        code: 'ka_GE',
        english_name: 'Georgian',
        native_name: 'ქართული'
    },
    {
        code: 'kk',
        english_name: 'Kazakh',
        native_name: 'Қазақ тілі'
    },
    {
        code: 'km',
        english_name: 'Khmer',
        native_name: 'ភាសាខ្មែរ'
    },
    {
        code: 'kn',
        english_name: 'Kannada',
        native_name: 'ಕನ್ನಡ'
    },
    {
        code: 'ko_KR',
        english_name: 'Korean',
        native_name: '한국어'
    },
    {
        code: 'lo',
        english_name: 'Lao',
        native_name: 'ພາສາລາວ'
    },
    {
        code: 'lt_LT',
        english_name: 'Lithuanian',
        native_name: 'Lietuvių kalba'
    },
    {
        code: 'lv',
        english_name: 'Latvian',
        native_name: 'Latviešu valoda'
    },
    {
        code: 'mk_MK',
        english_name: 'Macedonian',
        native_name: 'Македонски јазик'
    },
    {
        code: 'ml_IN',
        english_name: 'Malayalam',
        native_name: 'മലയാളം'
    },
    {
        code: 'mn',
        english_name: 'Mongolian',
        native_name: 'Монгол'
    },
    {
        code: 'mr',
        english_name: 'Marathi',
        native_name: 'मराठी'
    },
    {
        code: 'ms_MY',
        english_name: 'Malay',
        native_name: 'Bahasa Melayu'
    },
    {
        code: 'my_MM',
        english_name: 'Myanmar (Burmese)',
        native_name: 'ဗမာစာ'
    },
    {
        code: 'nb_NO',
        english_name: 'Norwegian (Bokmål)',
        native_name: 'Norsk bokmål'
    },
    {
        code: 'ne_NP',
        english_name: 'Nepali',
        native_name: 'नेपाली'
    },
    {
        code: 'nl_BE',
        english_name: 'Dutch (Belgium)',
        native_name: 'Nederlands (België)'
    },
    {
        code: 'nl_NL',
        english_name: 'Dutch',
        native_name: 'Nederlands'
    },
    {
        code: 'nl_NL_formal',
        english_name: 'Dutch (Formal)',
        native_name: 'Nederlands (Formeel)'
    },
    {
        code: 'nn_NO',
        english_name: 'Norwegian (Nynorsk)',
        native_name: 'Norsk nynorsk'
    },
    {
        code: 'oci',
        english_name: 'Occitan',
        native_name: 'Occitan'
    },
    {
        code: 'pa_IN',
        english_name: 'Punjabi',
        native_name: 'ਪੰਜਾਬੀ'
    },
    {
        code: 'pl_PL',
        english_name: 'Polish',
        native_name: 'Polski'
    },
    {
        code: 'ps',
        english_name: 'Pashto',
        native_name: 'پښتو'
    },
    {
        code: 'pt_AO',
        english_name: 'Portuguese (Angola)',
        native_name: 'Português de Angola'
    },
    {
        code: 'pt_BR',
        english_name: 'Portuguese (Brazil)',
        native_name: 'Português do Brasil'
    },
    {
        code: 'pt_PT',
        english_name: 'Portuguese (Portugal)',
        native_name: 'Português'
    },
    {
        code: 'pt_PT_ao90',
        english_name: 'Portuguese (Portugal, AO90)',
        native_name: 'Português (AO90)'
    },
    {
        code: 'rhg',
        english_name: 'Rohingya',
        native_name: 'Ruáinga'
    },
    {
        code: 'ro_RO',
        english_name: 'Romanian',
        native_name: 'Română'
    },
    {
        code: 'ru_RU',
        english_name: 'Russian',
        native_name: 'Русский'
    },
    {
        code: 'sah',
        english_name: 'Sakha',
        native_name: 'Сахалыы'
    },
    {
        code: 'si_LK',
        english_name: 'Sinhala',
        native_name: 'සිංහල'
    },
    {
        code: 'skr',
        english_name: 'Saraiki',
        native_name: 'سرائیکی'
    },
    {
        code: 'sk_SK',
        english_name: 'Slovak',
        native_name: 'Slovenčina'
    },
    {
        code: 'sl_SI',
        english_name: 'Slovenian',
        native_name: 'Slovenščina'
    },
    {
        code: 'snd',
        english_name: 'Sindhi',
        native_name: 'سنڌي'
    },
    {
        code: 'sq',
        english_name: 'Albanian',
        native_name: 'Shqip'
    },
    {
        code: 'sr_RS',
        english_name: 'Serbian',
        native_name: 'Српски језик'
    },
    {
        code: 'sv_SE',
        english_name: 'Swedish',
        native_name: 'Svenska'
    },
    {
        code: 'sw',
        english_name: 'Swahili',
        native_name: 'Kiswahili'
    },
    {
        code: 'szl',
        english_name: 'Silesian',
        native_name: 'Ślōnskŏ gŏdka'
    },
    {
        code: 'tah',
        english_name: 'Tahitian',
        native_name: 'Reo Tahiti'
    },
    {
        code: 'ta_IN',
        english_name: 'Tamil',
        native_name: 'தமிழ்'
    },
    {
        code: 'te',
        english_name: 'Telugu',
        native_name: 'తెలుగు'
    },
    {
        code: 'th',
        english_name: 'Thai',
        native_name: 'ไทย'
    },
    {
        code: 'tl',
        english_name: 'Tagalog',
        native_name: 'Tagalog'
    },
    {
        code: 'tr_TR',
        english_name: 'Turkish',
        native_name: 'Türkçe'
    },
    {
        code: 'tt_RU',
        english_name: 'Tatar',
        native_name: 'Татар теле'
    },
    {
        code: 'ug_CN',
        english_name: 'Uighur',
        native_name: 'ئۇيغۇرچە'
    },
    {
        code: 'uk',
        english_name: 'Ukrainian',
        native_name: 'Українська'
    },
    {
        code: 'ur',
        english_name: 'Urdu',
        native_name: 'اردو'
    },
    {
        code: 'uz_UZ',
        english_name: 'Uzbek',
        native_name: 'O‘zbekcha'
    },
    {
        code: 'vi',
        english_name: 'Vietnamese',
        native_name: 'Tiếng Việt'
    },
    {
        code: 'zh_CN',
        english_name: 'Chinese (China)',
        native_name: '简体中文'
    },
    {
        code: 'zh_HK',
        english_name: 'Chinese (Hong Kong)',
        native_name: '香港中文版'
    },
    {
        code: 'zh_TW',
        english_name: 'Chinese (Taiwan)',
        native_name: '繁體中文'
    }
];

const getLanguagesByCodes = computed(() => {
    return languages.filter((l) => {
        return props.languageSettings.list.includes(l.code)
    })
});

const change = (e: Event) => {
    emit('update:modelValue', (<HTMLInputElement>e.target).value)
}

</script>

<template>
    <select @change="change" v-model="value">
        <option v-for="language of getLanguagesByCodes" :value="language.code">
            {{ language.native_name }}
        </option>
    </select>
</template>

<style scoped>

</style>