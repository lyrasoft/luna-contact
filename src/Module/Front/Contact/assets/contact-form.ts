import {
  route,
  useBs5Tooltip,
  useDisableIfStackNotEmpty,
  useDisableOnSubmit,
  useFormComponent,
  useFormValidation, useFormValidationSync,
  useKeepAlive,
} from '@windwalker-io/unicorn-next';

const formSelector = '#contact-form';

useBs5Tooltip();

useFormComponent(formSelector);

useFormValidation().then(() => useDisableOnSubmit(formSelector));

useDisableIfStackNotEmpty();

useKeepAlive(location.href);

const form = document.querySelector<HTMLFormElement>(formSelector);
const button = document.querySelector<HTMLButtonElement>('[data-task="submit"]');

if (form && button) {
  button?.addEventListener('click', () => {
    const validation = useFormValidationSync(formSelector);

    if (!validation) {
      throw new Error('Validation not initialized');
    }

    if (!validation.validateAll()) {
      return;
    }

    form.setAttribute('action', route('@contact'));
    form.requestSubmit();
  });
}
