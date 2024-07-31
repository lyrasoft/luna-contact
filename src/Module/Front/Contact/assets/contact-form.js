/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import u from '@main';

u.$ui.bootstrap.tooltip();

const formSelector = '#contact-form';

u.formValidation()
  .then(() => u.$ui.disableOnSubmit(formSelector));
u.form(formSelector).initComponent();
u.$ui.keepAlive(location.href);

const form = u.selectOne(formSelector);
const button = u.selectOne('[data-task="submit"]');

button.addEventListener('click', () => {
  const action = form.action;

  const validation = u.$validation.get(formSelector);

  if (!validation.validateAll()) {
    return;
  }

  form.setAttribute('action', u.route('@contact'));
  form.requestSubmit();
});
