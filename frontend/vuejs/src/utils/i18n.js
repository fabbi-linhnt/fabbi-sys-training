import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Cookies from 'js-cookie';
import { DEFAULT_LANG } from '@/definition/constants';
import store from '@/store/index';

Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: Cookies.get('locale') || 'en',
  fallbackLocale: Cookies.get('locale') || 'en',
  messages: {}
});
/**
 * @param {String} locale
 */
export async function loadMessages(locale) {
  if (Object.keys(i18n.getLocaleMessage(locale)).length === 0) {
    const messages = await import(`./../lang/${locale}.json`);
    i18n.setLocaleMessage(locale, messages);
  }

  if (i18n.locale !== locale) {
    i18n.locale = locale;
  }
}

Vue.prototype.$loadMessages = loadMessages;

(async function () {
  let locale = Cookies.get('locale');
  let check = false;
  if (locale) {
    store.getters['lang/locales'].map(item => {
      if (item === locale) check = true;
      return item;
    });
  }
  if (!check) locale = DEFAULT_LANG;
  await loadMessages(locale);
}());

export default i18n;
