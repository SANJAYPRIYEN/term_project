document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            const password = form.querySelector('input[name="password"]');
            if (password && password.value.length < 6) {
                alert("Password must be at least 6 characters long.");
                e.preventDefault();
            }
        });
    });
});