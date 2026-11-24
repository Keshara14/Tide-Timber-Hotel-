document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (menuButton && nav) {
        menuButton.addEventListener('click', function () {
            const open = nav.classList.toggle('open');
            menuButton.setAttribute('aria-expanded', String(open));
        });
    }

    const checkIn = document.querySelector('#check-in');
    const checkOut = document.querySelector('#check-out');
    const bookingForm = document.querySelector('#booking-form');
    if (checkIn && checkOut) {
        const today = new Date();
        const todayString = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, '0'),
            String(today.getDate()).padStart(2, '0')
        ].join('-');
        checkIn.min = todayString;

        function updateBookingTotal() {
            if (!checkOut || !bookingForm) return;
            const start = new Date(checkIn.value + 'T00:00:00');
            const end = new Date(checkOut.value + 'T00:00:00');
            const nightsElement = document.querySelector('#nights-count');
            const totalElement = document.querySelector('#booking-total');
            if (!checkIn.value || !checkOut.value || end <= start) {
                if (nightsElement) nightsElement.textContent = '0';
                if (totalElement) totalElement.textContent = 'LKR 0.00';
                return;
            }
            const nights = Math.round((end - start) / 86400000);
            const price = Number(totalElement.dataset.price);
            if (nightsElement) nightsElement.textContent = String(nights);
            if (totalElement) totalElement.textContent = 'LKR ' + (nights * price).toLocaleString('en-LK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        checkIn.addEventListener('change', function () {
            if (checkIn.value) {
                const nextDay = new Date(checkIn.value + 'T00:00:00');
                nextDay.setDate(nextDay.getDate() + 1);
                checkOut.min = nextDay.toISOString().slice(0, 10);
            }
            updateBookingTotal();
        });
        checkOut.addEventListener('change', updateBookingTotal);

        if (bookingForm) {
            bookingForm.addEventListener('submit', function (event) {
                const start = new Date(checkIn.value + 'T00:00:00');
                const end = new Date(checkOut.value + 'T00:00:00');
                if (!checkIn.value || !checkOut.value || end <= start) {
                    event.preventDefault();
                    alert('Please choose valid check-in and check-out dates.');
                }
            });
        }
    }

    const registerForm = document.querySelector('#register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            const password = registerForm.querySelector('[name="password"]');
            const confirm = registerForm.querySelector('[name="confirm_password"]');
            if (password.value.length < 6) {
                event.preventDefault();
                alert('Password must contain at least 6 characters.');
            } else if (password.value !== confirm.value) {
                event.preventDefault();
                alert('Passwords do not match.');
            }
        });
    }
});
