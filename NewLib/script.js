const users = JSON.parse(localStorage.getItem('users')) || [];
const books = JSON.parse(localStorage.getItem('books')) || [];
function showCreateAccountForm() {
    document.querySelector('.login-form').style.display = 'none';
    document.querySelector('.create-account-form').style.display = 'flex';
}
function showLoginForm() {
    document.querySelector('.create-account-form').style.display = 'none';
    document.querySelector('.login-form').style.display = 'flex';
}
function login() {
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value.trim();
    const errorMessage = document.getElementById('loginError');

    const user = users.find(user => user.username === username && user.password === password);

    if (user) {
        window.location.href = 'homepage.html';
    } else {
        errorMessage.textContent = 'Invalid username or password. Please try again.';
    }
}
function createAccount() {
    const username = document.getElementById('createUsername').value.trim();
    const password = document.getElementById('createPassword').value.trim();
    const Mail = document.getElementById('createmailid').value.trim().toLowerCase();
    const errorMessage = document.getElementById('createError');

    if (username && password && Mail) {
        if (users.find(user => user.username === username)) {
            errorMessage.textContent = 'Username already exists. Please choose another.';
        } else {
            users.push({ username, password, Mail });
            localStorage.setItem('users', JSON.stringify(users));
            alert('Account created successfully! You can now log in.');
            showLoginForm();
        }
    } else {
        errorMessage.textContent = 'Please fill all fields correctly.';
    }
}
