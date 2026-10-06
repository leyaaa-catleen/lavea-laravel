(() => {
  const $ = (s, c = document) => c.querySelector(s), $$ = (s, c = document) => [...c.querySelectorAll(s)];

  // Show / hide password
  $$('[data-eye]').forEach(b => b.addEventListener('click', () => {
    const i = b.parentElement.querySelector('input'); const show = i.type === 'password';
    i.type = show ? 'text' : 'password'; b.textContent = show ? 'Hide' : 'Show';
  }));

  // Password strength meter
  const pw = $('#pw');
  if (pw) pw.addEventListener('input', () => {
    const v = pw.value; let s = 0;
    if (v.length >= 8) s++; if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++; if (/\d/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v) || v.length >= 12) s++;
    const m = $('#meter'); m.style.width = (s * 25) + '%'; m.style.background = ['#b3283f', '#b3283f', '#d98b2b', '#6fa36f', '#3f8f5a'][s];
    $('#meter-txt').textContent = v ? ['Too weak', 'Weak', 'Fair', 'Good', 'Strong'][s] : 'Use 8+ characters with upper, lower case and a number.';
  });

  // Instant client-side validation
  $$('form[data-validate]').forEach(f => {
    const msg = (i) => {
      if (i.validity.valueMissing) return 'This field is required.';
      if (i.validity.typeMismatch) return 'Enter a valid email address.';
      if (i.validity.tooShort) return `Use at least ${i.minLength} characters.`;
      if (i.validity.patternMismatch) return 'Use only letters, numbers, dashes and underscores.';
      if (i.validity.rangeUnderflow) return 'Value cannot be negative.';
      if (i.name === 'password_confirmation' && i.value !== $('#pw').value) return 'Passwords do not match.';
      if (i.name === 'password' && f.querySelector('#pw') && !(/[a-z]/.test(i.value) && /[A-Z]/.test(i.value) && /\d/.test(i.value))) return 'Include upper case, lower case and a number.';
      return '';
    };
    const check = (i) => {
      let e = i.parentElement.querySelector('.err.js'); const m = msg(i);
      i.classList.toggle('bad', !!m);
      if (m) { if (!e) { e = document.createElement('small'); e.className = 'err js'; i.parentElement.appendChild(e); } e.textContent = m; } else e && e.remove();
      return !m;
    };
    const fields = $$('input:not([type=hidden]):not([type=checkbox]):not([type=color]),select', f);
    fields.forEach(i => { i.addEventListener('blur', () => check(i)); i.addEventListener('input', () => i.classList.contains('bad') && check(i)); });
    f.addEventListener('submit', e => {
      let ok = fields.map(check).every(Boolean);
      const t = $('[name=terms]', f); if (t && !t.checked) { ok = false; t.focus(); }
      if (!ok) e.preventDefault(); else $('.btn', f).disabled = true;
    });
  });

  // Collection: filter, tilt, modal
  const modal = $('#pm');
  $$('.chip').forEach(c => c.addEventListener('click', () => {
    $$('.chip').forEach(x => x.classList.remove('on')); c.classList.add('on');
    $$('.card').forEach(k => k.classList.toggle('hide', c.dataset.filter !== 'all' && k.dataset.cat !== c.dataset.filter));
  }));
  $$('.tilt').forEach(c => {
    const open = () => {
      $('#pm-bottle').innerHTML = $('.bottle', c).outerHTML; $('#pm-name').textContent = c.dataset.name;
      $('#pm-notes').textContent = 'Notes: ' + (c.dataset.notes || 'Not listed yet');
      $('#pm-price').textContent = c.dataset.price; $('#pm-stock').textContent = +c.dataset.stock > 0 ? c.dataset.stock + ' in stock' : 'Sold out';
      modal.hidden = false;
    };
    c.addEventListener('click', open); c.addEventListener('keydown', e => e.key === 'Enter' && open());
  });

  // Home: scent family highlight and hero parallax
  $$('[data-family]').forEach(f => f.addEventListener('click', () => { $$('[data-family]').forEach(x => x.classList.remove('on')); f.classList.add('on'); }));

  // Stock: add / edit modal
  const sm = $('#sm'), form = $('#sm-form');
  if (sm) {
    const open = (p) => {
      form.reset(); $('#sm-method').innerHTML = '';
      if (p) {
        form.action = `${location.origin}/stock/${p.id}`; $('#sm-title').textContent = 'Edit ' + p.name;
        $('#sm-method').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        ['name', 'category', 'notes', 'price', 'stock', 'color'].forEach(k => form.elements[k].value = p[k] ?? '');
      } else { form.action = `${location.origin}/stock`; $('#sm-title').textContent = 'Add perfume'; }
      sm.hidden = false; form.elements.name.focus();
    };
    $('[data-add]').addEventListener('click', () => open());
    $$('[data-edit]').forEach(b => b.addEventListener('click', () => open(JSON.parse(b.dataset.edit))));
  }
  $$('[data-close]').forEach(b => b.addEventListener('click', () => b.closest('.modal').hidden = true));
  $$('.modal').forEach(m => m.addEventListener('click', e => e.target === m && (m.hidden = true)));
  document.addEventListener('keydown', e => e.key === 'Escape' && $$('.modal').forEach(m => m.hidden = true));
})();
