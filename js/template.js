document.addEventListener('DOMContentLoaded', function () {
    /* ---- Folder Accordion ---- */
    const folderAccordion = document.getElementById('folder-accordion');
    if (folderAccordion) {
        const header = folderAccordion.querySelector('.folder-accordion-header');
        const content = folderAccordion.querySelector('.folder-accordion-content');
        const toggleText = folderAccordion.querySelector('.folder-accordion-toggle-text');
        const caret = folderAccordion.querySelector('.folder-accordion-caret');
        const collapseText = folderAccordion.dataset.collapseText || 'Collapse';
        const expandText = folderAccordion.dataset.expandText || 'Expand';

        // Set initial max-height based on actual content
        content.style.maxHeight = content.scrollHeight + 'px';

        header.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                content.style.maxHeight = '0px';
                content.classList.remove('border-t');
                if (toggleText) toggleText.textContent = expandText;
                if (caret) caret.classList.remove('rotate-180');
            } else {
                this.setAttribute('aria-expanded', 'true');
                content.style.maxHeight = content.scrollHeight + 'px';
                content.classList.add('border-t');
                if (toggleText) toggleText.textContent = collapseText;
                if (caret) caret.classList.add('rotate-180');
            }
        });
    }

    const zipBtn = document.getElementById('zip_download');
    const selectAll = document.getElementById('select-all');

    updateSelectionSummary();


    function getCheckboxes() {
        return Array.from(document.querySelectorAll('.file-checkbox'));
    }

    function anyChecked() {
        return getCheckboxes().some(cb => cb.checked === true);
    }

    function updateZipButton() {
        if (!zipBtn) return;
        if (anyChecked()) {
            zipBtn.classList.remove('disabled');
            zipBtn.removeAttribute('aria-disabled');
        } else {
            zipBtn.classList.add('disabled');
            zipBtn.setAttribute('aria-disabled', 'true');
        }
    }

    function updateSelectAllState() {
        if (!selectAll) return;
        const checkboxes = getCheckboxes();
        const checkedCount = checkboxes.filter(cb => cb.checked).length;
        selectAll.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
    }

    function updateCardSelectionState() {
        document.querySelectorAll('.file-card').forEach(card => {
            const checkbox = card.querySelector('.file-checkbox');
            card.classList.toggle('is-selected', checkbox && checkbox.checked);
        });
    }

    function updateSelectionSummary() {
        const summary = document.getElementById('selection-summary');
        if (!summary) return;

        const total = getCheckboxes().length;
        const checked = getCheckboxes().filter(cb => cb.checked).length;
        const template = summary.dataset.textTemplate || '%s of %s items selected for download.';

        let text = template.replace('%s', checked).replace('%s', total);
        summary.textContent = text;
    }

    function toggleAll(checked) {
        getCheckboxes().forEach(cb => {
            cb.checked = checked;
        });
        updateZipButton();
        updateSelectAllState();
        updateCardSelectionState();
        updateSelectionSummary();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        const exponent = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, exponent)).toFixed(1).replace(/\.0$/, '') + ' ' + units[exponent];
    }

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${encodeURIComponent(value)};expires=${date.toUTCString()};path=/`;
    }

    function getCookie(name) {
        const nameEQ = `${name}=`;
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length));
            }
        }
        return null;
    }

    function getSelectedFiles() {
        const checkboxes = getCheckboxes().filter(cb => cb.checked);
        const ids = checkboxes.map(cb => cb.value);
        const totalSize = checkboxes.reduce((sum, cb) => sum + Number(cb.dataset.size || 0), 0);
        return { ids, count: checkboxes.length, totalSize };
    }

    function estimateZipTimeMs(totalBytes) {
        if (totalBytes <= 0) {
            return 25000;
        }
        const bytesPerSecond = 20 * 1024 * 1024; // 20 MB/s server-side estimate
        return Math.max(25000, Math.round((totalBytes / bytesPerSecond) * 1000));
    }

    function showDownloadOverlay(fileCount, totalSize, estimatedMs) {
        if (document.getElementById('ps-download-overlay')) return;
        const overlay = document.createElement('div');
        overlay.id = 'ps-download-overlay';
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.35)';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = '9999';
        overlay.innerHTML = `
            <div style="max-width:320px;padding:20px 24px;background:#fff;border-radius:12px;text-align:center;color:#111;line-height:1.5;">
                <div style="font-weight:700;margin-bottom:10px;">Preparing download…</div>
                <div style="font-size:0.95rem;color:#4b5563;">
                    Selected ${fileCount} file${fileCount === 1 ? '' : 's'} totaling ${formatBytes(totalSize)}.
                </div>
                <div style="margin-top:10px;font-size:0.9rem;color:#6b7280;">
                    This may take up to ${Math.ceil(estimatedMs / 60000)} minute${Math.ceil(estimatedMs / 60000) === 1 ? '' : 's'}.
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
    }

    function hideDownloadOverlay() {
        const overlay = document.getElementById('ps-download-overlay');
        if (overlay && overlay.parentNode) {
            overlay.parentNode.removeChild(overlay);
        }
    }

    function handleZipDownload(event) {
        event.preventDefault();
        if (!zipBtn || zipBtn.classList.contains('disabled')) return;

        const selected = getSelectedFiles();
        if (selected.ids.length === 0) return;

        const estimatedMs = estimateZipTimeMs(selected.totalSize);
        showDownloadOverlay(selected.count, selected.totalSize, estimatedMs);
        setCookie('download_started', 0, 100);

        const iframe = document.createElement('iframe');
        iframe.id = 'ps-download-iframe';
        iframe.style.display = 'none';
        iframe.src = base_url + 'process.php?do=download_zip&files=' + encodeURIComponent(selected.ids.join(','));
        document.body.appendChild(iframe);

        let downloadCheckerTimer = null;
        const downloadChecker = function () {
            if (getCookie('download_started') == 1) {
                setCookie('download_started', 'false', 100);
                hideDownloadOverlay();
                if (iframe && iframe.parentNode) iframe.parentNode.removeChild(iframe);
                if (downloadCheckerTimer) {
                    clearTimeout(downloadCheckerTimer);
                }
                return;
            }

            downloadCheckerTimer = setTimeout(downloadChecker, 1000);
        };

        downloadCheckerTimer = setTimeout(downloadChecker, 1000);
        setTimeout(function () {
            clearTimeout(downloadCheckerTimer);
            hideDownloadOverlay();
            if (iframe && iframe.parentNode) iframe.parentNode.removeChild(iframe);
        }, Math.min(Math.max(Math.round(estimatedMs * 1.5), 25000), 300000));
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            toggleAll(this.checked);
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList && e.target.classList.contains('file-checkbox')) {
            updateZipButton();
            updateSelectAllState();
            updateCardSelectionState();
            updateSelectionSummary();
        }
    });

    document.addEventListener('click', function (e) {
        const card = e.target.closest('.file-card');
        if (!card) return;

        if (e.target.closest('.file-checkbox') || e.target.closest('.download-action')) return;

        const checkbox = card.querySelector('.file-checkbox');
        if (!checkbox) return;

        e.preventDefault();
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        updateZipButton();
        updateSelectAllState();
        updateCardSelectionState();
    });

    document.addEventListener('keydown', function (e) {
        const card = e.target.closest('.file-card');
        if (!card || (e.key !== 'Enter' && e.key !== ' ')) return;
        if (e.target.closest('.download-action') || e.target.closest('.file-checkbox')) return;
        e.preventDefault();
        const checkbox = card.querySelector('.file-checkbox');
        if (!checkbox) return;
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        updateZipButton();
        updateSelectAllState();
        updateCardSelectionState();
    });

    if (zipBtn) {
        zipBtn.addEventListener('click', handleZipDownload);
    }

    updateZipButton();
    updateSelectAllState();
    updateCardSelectionState();
});
