// Client-side validation for login and register forms
function showError(input, message) {
  let error = input.parentElement.querySelector('.error-msg');
  if (!error) {
    error = document.createElement('div');
    error.className = 'error-msg';
    error.style.color = '#d32f2f';
    error.style.fontSize = '0.95em';
    error.style.marginTop = '0.2em';
    input.parentElement.appendChild(error);
  }
  error.textContent = message;
}
function clearError(input) {
  let error = input.parentElement.querySelector('.error-msg');
  if (error) error.textContent = '';
}
function validateLoginForm(e) {
  let valid = true;
  const form = e.target;
  const username = form.username;
  const password = form.password;
  clearError(username); clearError(password);
  if (!username.value.trim()) {
    showError(username, 'Username is required.');
    valid = false;
  }
  if (!password.value.trim()) {
    showError(password, 'Password is required.');
    valid = false;
  }
  if (!valid) e.preventDefault();
}
function validateRegisterForm(e) {
  let valid = true;
  const form = e.target;
  const username = form.username;
  const password = form.password;
  const email = form.email;
  clearError(username); clearError(password); clearError(email);
  if (!username.value.trim()) {
    showError(username, 'Username is required.');
    valid = false;
  }
  if (!password.value.trim()) {
    showError(password, 'Password is required.');
    valid = false;
  } else if (password.value.length < 6) {
    showError(password, 'Password must be at least 6 characters.');
    valid = false;
  }
  if (!email.value.trim()) {
    showError(email, 'Email is required.');
    valid = false;
  } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
    showError(email, 'Enter a valid email address.');
    valid = false;
  }
  if (!valid) e.preventDefault();
}
async function checkUserAvailability(field, value) {
  const params = new URLSearchParams();
  params.append(field, value);
  const res = await fetch('php/check_user.php?' + params.toString());
  return res.json();
}

document.addEventListener('DOMContentLoaded', function() {
  const loginForm = document.querySelector('form[action="php/login.php"]');
  if (loginForm) loginForm.addEventListener('submit', validateLoginForm);
  const registerForm = document.querySelector('form[action="php/register.php"]');
  if (registerForm) {
    registerForm.addEventListener('submit', validateRegisterForm);
    // Username check
    const username = registerForm.username;
    username.addEventListener('blur', async function() {
      clearError(username);
      if (username.value.trim()) {
        const result = await checkUserAvailability('username', username.value.trim());
        if (result.usernameUsed) {
          showError(username, 'Username is already taken.');
        }
      }
    });
    // Email check
    const email = registerForm.email;
    email.addEventListener('blur', async function() {
      clearError(email);
      if (email.value.trim()) {
        const result = await checkUserAvailability('email', email.value.trim());
        if (result.emailUsed) {
          showError(email, 'Email is already registered.');
        }
      }
    });
  }
}); 