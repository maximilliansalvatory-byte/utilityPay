// UtilityPay - Professional JavaScript
document.addEventListener('DOMContentLoaded', function() {

    // Smooth animations for cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.transitionDelay = (index * 100) + 'ms';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200);
    });

    // Auto-format phone number (Tanzania)
    const phoneInputs = document.querySelectorAll('input[placeholder*="255"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            let val = this.value.replace(/\D/g, '');
            if (val.length > 12) val = val.substring(0, 12);
            this.value = val;
        });
    });

    // Confirm before payment
    const payButtons = document.querySelectorAll('.btn-pay, button[name="pay_mpesa"]');
    payButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Confirm payment of TZS ' + (document.querySelector('input[name="amount"]')?.value || '0') + '?')) {
                e.preventDefault();
            }
        });
    });

    // Chart colors for analytics
    if (document.getElementById('spendingChart')) {
        // Chart.js colors already handled in analytics.php
    }

    // Toast notification function
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
        document.body.appendChild(toast);
        new bootstrap.Toast(toast).show();
        setTimeout(() => toast.remove(), 4000);
    };
});