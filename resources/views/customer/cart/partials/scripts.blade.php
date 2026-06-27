<script>
function confirmDelete(key) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        const card = document.getElementById('cart-card-' + key);
        if (card) {
            // Apply premium removal animation
            card.classList.add('cart-item-removing');
            
            // Wait for slide-out transition to complete
            setTimeout(() => {
                document.getElementById('delete-form-' + key).submit();
            }, 400);
        } else {
            document.getElementById('delete-form-' + key).submit();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox:not(#selectAllCheckbox)');
    const selectAllCb = document.getElementById('selectAllCheckbox');
    const totalHargaEl = document.getElementById('summaryTotalHarga');
    const totalDpEl = document.getElementById('summaryTotalDp');
    const totalSisaEl = document.getElementById('summaryTotalSisa');
    const checkoutBtn = document.getElementById('checkoutBtn');

    if (checkboxes.length > 0) {
        function calculateSummary() {
            let totalHarga = 0;
            let totalDp = 0;
            let totalSisa = 0;
            let checkedCount = 0;

            checkboxes.forEach((cb) => {
                if (cb.checked) {
                    totalHarga += Number(cb.dataset.price);
                    totalDp += Number(cb.dataset.dp);
                    totalSisa += Number(cb.dataset.remaining);
                    checkedCount++;
                }
            });

            const formatter = new Intl.NumberFormat('id-ID');
            
            // Animate number updates dynamically
            updatePriceWithAnimation(totalHargaEl, totalHarga, formatter);
            updatePriceWithAnimation(totalDpEl, totalDp, formatter);
            updatePriceWithAnimation(totalSisaEl, totalSisa, formatter);

            if (checkoutBtn) {
                if (checkedCount === 0) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.innerHTML = 'Pilih Layanan';
                } else {
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = 'Checkout <i class="bi bi-arrow-right"></i>';
                }
            }

            // Sync selectAllCheckbox state
            if (selectAllCb) {
                selectAllCb.checked = (checkedCount === checkboxes.length);
            }
        }

        // Smooth text transition helper
        function updatePriceWithAnimation(element, newValue, formatter) {
            if (!element) return;
            const newText = 'Rp' + formatter.format(newValue);
            if (element.textContent !== newText) {
                element.style.opacity = 0;
                element.style.transform = 'translateY(-3px)';
                element.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                
                setTimeout(() => {
                    element.textContent = newText;
                    element.style.opacity = 1;
                    element.style.transform = 'translateY(0)';
                }, 150);
            }
        }

        checkboxes.forEach((cb) => {
            cb.addEventListener('change', () => {
                // Pulse checkbox on toggle
                if (cb.checked) {
                    cb.style.transform = 'scale(1.15)';
                    setTimeout(() => cb.style.transform = '', 150);
                }
                calculateSummary();
            });
        });

        // Event listener for Select All
        if (selectAllCb) {
            selectAllCb.addEventListener('change', () => {
                const isChecked = selectAllCb.checked;
                checkboxes.forEach((cb) => {
                    if (cb.checked !== isChecked) {
                        cb.checked = isChecked;
                        // Pulse animation on checked
                        if (isChecked) {
                            cb.style.transform = 'scale(1.15)';
                            setTimeout(() => cb.style.transform = '', 150);
                        }
                    }
                });
                calculateSummary();
            });
        }

        // Run once on load
        calculateSummary();
    }
});
</script>
