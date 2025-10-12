import { fadeOutMessages } from "./messages.js";

export function validateFields(inputs, form, msgClass) {
  let valid = true;
  inputs.each(function () {
    if ($.trim($(this).val()) === "") valid = false;
  });

  if (!valid) {
    form.prepend(`
      <div class="${msgClass} bg-red-100 text-red-600 text-sm p-2 rounded mb-2">
        Please fill in all required fields.
      </div>
    `);
    fadeOutMessages(`.${msgClass}`);
  }

  return valid;
}
