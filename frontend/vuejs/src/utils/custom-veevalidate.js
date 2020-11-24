/* eslint-disable consistent-return */
/* eslint-disable radix */
/* eslint-disable import/prefer-default-export */
import { extend } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';

export async function loadVeeValidate() {
  const { messages } = await import('vee-validate/dist/locale/vi.json');
  Object.keys(rules).forEach(rule => {
    extend(rule, {
      ...rules[rule], // copies rule configuration
      message: messages[rule] // assign message
    });
  });
}
