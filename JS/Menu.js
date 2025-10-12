function displayMenu() {
    let menuNav = document.getElementById("menu-nav");

    if (menuNav.classList.contains("menu-visible")) {
        menuNav.classList.remove("menu-visible"); 
    } else {
        menuNav.classList.add("menu-visible"); 
    }
}

document.addEventListener("click", function (event) {
    let menuNav = document.getElementById("menu-nav");
    let menu = document.querySelector(".menu");

    if (!menuNav.contains(event.target) && !menu.contains(event.target)) {
        menuNav.classList.remove("menu-visible");
    }
});
