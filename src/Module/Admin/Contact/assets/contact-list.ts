import {
  useBs5Tooltip,
  useCheckboxesMultiSelect,
  useDisableOnSubmit,
  useGridComponent, useIframeModal,
} from '@windwalker-io/unicorn-next';
import { IFrameModalElement } from '@windwalker-io/unicorn-next';

const formSelector = '#admin-form';

useBs5Tooltip();

useGridComponent(formSelector);

useDisableOnSubmit(formSelector);

useCheckboxesMultiSelect(formSelector);

useIframeModal().then(() => {
  const previewModal = document.querySelector<IFrameModalElement>('#preview-modal');

  if (previewModal && location.hash && location.hash.startsWith('#contact-')) {
    const hash = location.hash;
    let route = previewModal.dataset.route || '';

    const id = hash.replace('#contact-', '');

    route = route.replace('{id}', id);

    setTimeout(() => {
      previewModal.open(route, { resize: true });
    }, 300);
  }
});
