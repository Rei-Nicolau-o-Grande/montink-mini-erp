document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('alert');
    if (alert) return setTimeout( () => alert.remove(), 5000);
});
