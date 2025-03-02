const container = document.querySelector(".container"),
      signUp = document.querySelector(".signup-link"),
      login = document.querySelector(".login-link");

// Form toggle (if elements exist)
if(signUp && login) {
    signUp.addEventListener("click", () => {
        container.classList.add("active");
    });
    login.addEventListener("click", () => {
        container.classList.remove("active");
    });
}

document.querySelector("form").addEventListener("submit", function(event) {
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!/^[a-zA-Z ]*$/.test(name)) {
        alert("Name must contain only letters.");
        event.preventDefault();
    }
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert("Invalid email format.");
        event.preventDefault();
    }
    if (!/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password)) {
        alert("Password must be at least 8 characters long, include letters, numbers, and special characters.");
        event.preventDefault();
    }
});

// Password visibility toggle functionality
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmpassword');
const showPasswordIcon = document.getElementById('showpassword');
const showConfirmPasswordIcon = document.getElementById('showconfirmpassword');

function togglePasswordVisibility(inputField, icon) {
    if (inputField.type === 'password') {
        inputField.type = 'text';
        icon.classList.remove('uil-eye-slash');
        icon.classList.add('uil-eye');
    } else {
        inputField.type = 'password';
        icon.classList.remove('uil-eye');
        icon.classList.add('uil-eye-slash');
    }
}

// For main password
if (showPasswordIcon) {
    showPasswordIcon.addEventListener('click', () => {
        togglePasswordVisibility(passwordInput, showPasswordIcon);
    });
}

// For confirm password
if (showConfirmPasswordIcon) {
    showConfirmPasswordIcon.addEventListener('click', () => {
        togglePasswordVisibility(confirmPasswordInput, showConfirmPasswordIcon);
    });
}
