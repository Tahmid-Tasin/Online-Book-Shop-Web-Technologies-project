function validateRegisterForm() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var password = document.getElementById('password').value;
    var address = document.getElementById('address').value.trim();
    var phone = document.getElementById('phone').value.trim();

    if (name === '' || email === '' || address === '' || phone === '') {
        alert('Please fill all required fields.');
        return false;
    }
    if (password.length < 8) {
        alert('Password must be at least 8 characters.');
        return false;
    }
    return true;
}

function validateLoginForm() {
    var email = document.getElementById('login_email').value.trim();
    var password = document.getElementById('login_password').value;

    if (email === '' || password === '') {
        alert('Email and password are required.');
        return false;
    }
    return true;
}

function validateProfileForm() {
    var name = document.getElementById('profile_name').value.trim();
    var email = document.getElementById('profile_email').value.trim();
    var address = document.getElementById('profile_address').value.trim();
    var phone = document.getElementById('profile_phone').value.trim();
    var newPassword = document.getElementById('new_password').value;

    if (name === '' || email === '' || address === '' || phone === '') {
        alert('Please fill name, email, address and phone.');
        return false;
    }
    if (newPassword !== '' && newPassword.length < 8) {
        alert('New password must be at least 8 characters.');
        return false;
    }
    return true;
}
