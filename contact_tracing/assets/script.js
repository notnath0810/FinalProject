/* Contact Tracing System — vanilla JS utilities */

'use strict';

/* ── Delete confirmation dialog ────────────────────────────── */
function confirmDelete(visitorName) {
    return confirm(
        'WARNING: This will permanently delete "' + visitorName +
        '" and ALL their visit history.\n\nThis action cannot be undone. Continue?'
    );
}

/* ── Client-side form validation helpers ───────────────────── */
document.addEventListener('DOMContentLoaded', function () {

    /* Attach real-time validation styling */
    document.querySelectorAll('input[required], select[required]').forEach(function (el) {
        el.addEventListener('blur', function () {
            if (el.value.trim() === '') {
                el.style.borderColor = '#cc0000';
            } else {
                el.style.borderColor = '';
            }
        });
    });

    /* Validate registration form before submit */
    var regForm = document.getElementById('registerForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            var errors = [];
            var req = regForm.querySelectorAll('[required]');
            req.forEach(function (field) {
                if (field.value.trim() === '') {
                    errors.push(field.closest('.form-group') ?
                        field.closest('.form-group').querySelector('label').textContent.replace('*','').trim() :
                        field.name);
                    field.style.borderColor = '#cc0000';
                } else {
                    field.style.borderColor = '';
                }
            });

            var emailField = regForm.querySelector('[name="email"]');
            if (emailField && emailField.value.trim() !== '') {
                var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRe.test(emailField.value.trim())) {
                    errors.push('Email (invalid format)');
                    emailField.style.borderColor = '#cc0000';
                }
            }

            var phoneField = regForm.querySelector('[name="contact_number"]');
            if (phoneField && phoneField.value.trim() !== '') {
                var phoneRe = /^[0-9+\-\s()]{7,20}$/;
                if (!phoneRe.test(phoneField.value.trim())) {
                    errors.push('Contact number (invalid format)');
                    phoneField.style.borderColor = '#cc0000';
                }
            }

            if (errors.length > 0) {
                e.preventDefault();
                showInlineError(regForm, 'Please fill in or correct: ' + errors.join(', ') + '.');
            }
        });
    }

    /* Returning / sign-out lookup form */
    var lookupForm = document.getElementById('lookupForm');
    if (lookupForm) {
        lookupForm.addEventListener('submit', function (e) {
            var idField   = lookupForm.querySelector('[name="id_number"]');
            var nameField = lookupForm.querySelector('[name="last_name"]');
            var idVal   = idField   ? idField.value.trim()   : '';
            var nameVal = nameField ? nameField.value.trim() : '';

            if (idVal === '' && nameVal === '') {
                e.preventDefault();
                showInlineError(lookupForm, 'Please enter your ID number or last name to proceed.');
            }
        });
    }

    /* Admin search form — at least one filter must be filled */
    var searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            var inputs  = searchForm.querySelectorAll('input, select');
            var hasVal  = false;
            inputs.forEach(function (el) { if (el.value.trim() !== '') hasVal = true; });
            if (!hasVal) {
                e.preventDefault();
                showInlineError(searchForm, 'Enter at least one search filter.');
            }
        });
    }
});

/* ── Inline error banner ────────────────────────────────────── */
function showInlineError(form, message) {
    var existing = form.querySelector('.js-error');
    if (existing) existing.remove();

    var div = document.createElement('div');
    div.className = 'alert alert-error js-error';
    div.textContent = message;
    form.prepend(div);
    div.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── Auto-hide alerts after 6 seconds ──────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert-success, .alert-info').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .6s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 600);
        }, 6000);
    });
});

/* ── Visit history modal (admin dashboard) ──────────────────── */
function openHistoryModal(visitorId, visitorName) {
    var modal = document.getElementById('historyModal');
    var title = document.getElementById('modalVisitorName');
    var body  = document.getElementById('modalBody');

    title.textContent = 'Visit History — ' + visitorName;
    body.innerHTML    = '<p style="padding:16px">Loading...</p>';
    modal.style.display = 'flex';

    fetch('dashboard.php?ajax_history=1&visitor_id=' + encodeURIComponent(visitorId))
        .then(function (r) { return r.text(); })
        .then(function (html) { body.innerHTML = html; })
        .catch(function ()   { body.innerHTML = '<p class="alert alert-error" style="margin:16px">Failed to load history.</p>'; });
}

function closeHistoryModal() {
    document.getElementById('historyModal').style.display = 'none';
}

/* Close modal when clicking the backdrop */
window.addEventListener('click', function (e) {
    var modal = document.getElementById('historyModal');
    if (modal && e.target === modal) closeHistoryModal();
});
