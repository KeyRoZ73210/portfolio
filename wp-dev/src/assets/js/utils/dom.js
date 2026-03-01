export const qs = (sel, root = document) => root.querySelector(sel);
export const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

export function on(el, event, handler) {
  if (!el) return;
  el.addEventListener(event, handler);
}