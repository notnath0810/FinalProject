'use strict';

function confirmDelete(visitorName) {
    return confirm('WARNING: This will permanently delete "' + visitorName + '" and ALL their visit history.\n\nThis action cannot be undone. Continue?');
}

document.addEventListener('DOMContentLoaded', function () {
    // Real-time validation borders
    document.querySelectorAll('input[required], select[required]').forEach(function (el) {
        el.addEventListener('blur', function () {
            el.style.borderColor = el.value.trim() === '' ? '#c0392b' : '';
        });
    });

    // Registration form validation
    var regForm = document.getElementById('registerForm');
    if (regForm) {
        regForm.addEventListener('submit', function (e) {
            var errors = [];
            regForm.querySelectorAll('[required]').forEach(function (field) {
                if (field.value.trim() === '') {
                    var g = field.closest('.form-group');
                    errors.push(g ? g.querySelector('label').textContent.replace('*','').trim() : field.name);
                    field.style.borderColor = '#c0392b';
                } else { field.style.borderColor = ''; }
            });
            var ef = regForm.querySelector('[name="email"]');
            if (ef && ef.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(ef.value.trim())) {
                errors.push('Email (invalid format)'); ef.style.borderColor = '#c0392b';
            }
            var pf = regForm.querySelector('[name="contact_number"]');
            if (pf && pf.value.trim() && !/^[0-9+\-\s()]{7,20}$/.test(pf.value.trim())) {
                errors.push('Contact number (invalid format)'); pf.style.borderColor = '#c0392b';
            }
            if (errors.length > 0) { e.preventDefault(); showInlineError(regForm, 'Please fill in or correct: ' + errors.join(', ') + '.'); }
        });
    }

    // Lookup form validation
    var lookupForm = document.getElementById('lookupForm');
    if (lookupForm) {
        lookupForm.addEventListener('submit', function (e) {
            var idVal   = (lookupForm.querySelector('[name="id_number"]')  || {value:''}).value.trim();
            var nameVal = (lookupForm.querySelector('[name="last_name"]') || {value:''}).value.trim();
            if (!idVal && !nameVal) { e.preventDefault(); showInlineError(lookupForm, 'Please enter your ID number or last name to proceed.'); }
        });
    }

    // Search form validation
    var searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            var hasVal = false;
            searchForm.querySelectorAll('input, select').forEach(function (el) { if (el.value.trim()) hasVal = true; });
            if (!hasVal) { e.preventDefault(); showInlineError(searchForm, 'Enter at least one search filter.'); }
        });
    }
});

function showInlineError(form, message) {
    var ex = form.querySelector('.js-error'); if (ex) ex.remove();
    var div = document.createElement('div');
    div.className = 'alert alert-error js-error';
    div.textContent = message;
    form.prepend(div);
    div.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Auto-hide success/info alerts
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert-success, .alert-info').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .6s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 600);
        }, 6000);
    });
});

// Visit history modal
function openHistoryModal(visitorId, visitorName) {
    var modal = document.getElementById('historyModal');
    document.getElementById('modalVisitorName').textContent = 'Visit History — ' + visitorName;
    document.getElementById('modalBody').innerHTML = '<p style="padding:24px;text-align:center;color:#6b7280">Loading...</p>';
    modal.style.display = 'flex';
    fetch('dashboard.php?ajax_history=1&visitor_id=' + encodeURIComponent(visitorId))
        .then(function (r) { return r.text(); })
        .then(function (html) { document.getElementById('modalBody').innerHTML = html; })
        .catch(function () { document.getElementById('modalBody').innerHTML = '<p class="alert alert-error" style="margin:16px">Failed to load history.</p>'; });
}

function closeHistoryModal() {
    document.getElementById('historyModal').style.display = 'none';
}

window.addEventListener('click', function (e) {
    var m = document.getElementById('historyModal');
    if (m && e.target === m) closeHistoryModal();
});
