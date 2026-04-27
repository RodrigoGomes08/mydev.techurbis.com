// JS do index.html
// Serve para alterar a transparência da navbar ao fazer scroll
document.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    const carousel = document.getElementById("cityCarousel");
 
    if (!carousel) return;
 
    const carouselHeight = carousel.offsetHeight;
 
    if (window.scrollY < carouselHeight - 80) {
        navbar.classList.add("navbar-transparent");
    } else {
        navbar.classList.remove("navbar-transparent");
    }
});
 
// JS do login.html
// Só corre se o formulário existir na página
const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
 
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const errorMsg = document.getElementById("errorMsg");
 
        if (email === "a@a" && password === "12345") {
            sessionStorage.setItem('loggedIn', 'true');
            window.location.href = "PortalADMGeral.html";
        } else {
            errorMsg.style.display = "block";
        }
    });
}
 