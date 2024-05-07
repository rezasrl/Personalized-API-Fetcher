import { browser } from 'globals';
import { configs as pluginJsConfigs } from '@eslint/eslintrc';

export default {
  globals: {
    browser: true,
    ...browser
  },
  extends: [ pluginJsConfigs.recommended ]
};