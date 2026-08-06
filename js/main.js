// Auto-hide flash messages after four seconds for better UX.
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.classList.remove('show');
            alert.classList.add('fade');
        }, 4000);
    });
});
