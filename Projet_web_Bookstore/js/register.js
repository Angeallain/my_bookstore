const container = document.getElementById('container2');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
        
const mobileRegister = document.getElementById('mobile-register');
const mobileLogin = document.getElementById('mobile-login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});
        
loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});


mobileRegister.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.add("active");
});

mobileLogin.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.remove("active");
});


/*--------------------------------------------------------------------------------------*/



        
