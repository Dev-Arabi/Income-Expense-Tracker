const togglePassword = document.getElementById('toggle-password');
const passwordField = document.getElementById('password');

togglePassword.addEventListener('change', function () {
    passwordField.type = this.checked ? 'text' : 'password';
});
