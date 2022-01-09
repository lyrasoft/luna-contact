/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

import u from '@main';

u.$ui.bootstrap.tooltip();

const form = '#admin-form';

u.grid(form).initComponent();
u.$ui.disableOnSubmit(form);
u.$ui.checkboxesMultiSelect(form);

u.$ui.iframeModal().then(() => {
  const previewModal = u.selectOne('#preview-modal');

  if (previewModal && location.hash && location.hash.startsWith('#contact-')) {
    const hash = location.hash;
    let route = previewModal.dataset.route;

    const id = hash.replace('#contact-', '');

    route = route.replace('{id}', id);

    setTimeout(() => {
      previewModal.open(route, { resize: true });
    }, 300);
  }
});
