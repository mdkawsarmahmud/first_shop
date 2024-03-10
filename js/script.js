// profile
const pfBtn = document.getElementById('pfBtn');
const profileFormContainer = document.getElementById('profileFormContainer');
pfBtn.addEventListener('click', () => {

  profileFormContainer.classList.toggle('hidden');
  
});

// window.addEventListener("click", function (e) {
  
// if(e.target.id!==pfBtn)
//     loginFormContainer.classList.remove('hidden');
//     console.log("hi");
    
  
// });

// login
const loginBtn = document.getElementById('loginBtn');
const loginFormContainer = document.getElementById('loginFormContainer');
const registerFormContainer = document.getElementById('registerFormContainer');
loginBtn.addEventListener('click', () => {
  registerFormContainer.classList.add('hidden');
    loginFormContainer.classList.toggle('hidden');
});

const showLoginForm = document.getElementById('showLoginForm');
showLoginForm.addEventListener('click', () => {
  registerFormContainer.classList.add('hidden');
  loginFormContainer.classList.remove('hidden');
});

const showRegisterForm = document.getElementById('showRegisterForm');
showRegisterForm.addEventListener('click', () => {
    // loginFormContainer.classList.add('hidden');
    registerFormContainer.classList.remove('hidden');
});

window.addEventListener('DOMContentLoaded', function () {
  // Smooth scroll functionality
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
          e.preventDefault();

          document.querySelector(this.getAttribute('href')).scrollIntoView({
              behavior: 'smooth'
          });
      });
  });
});