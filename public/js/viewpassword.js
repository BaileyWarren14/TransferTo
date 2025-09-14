
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    const passwordConfirm = document.getElementById('password_confirmation');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');

    togglePassword.addEventListener('click', function() {
        if (password.type === 'password') {
            password.type = 'text';
            togglePassword.textContent = '🙈';
        } else {
            password.type = 'password';
            togglePassword.textContent = '👁️';
        }
    });

    togglePasswordConfirm.addEventListener('click', function() {
        if (passwordConfirm.type === 'password') {
            passwordConfirm.type = 'text';
            togglePasswordConfirm.textContent = '🙈';
        } else {
            passwordConfirm.type = 'password';
            togglePasswordConfirm.textContent = '👁️';
        }
    });
});
