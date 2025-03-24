$(document).ready(function () {
    function updateNavbar() {
        var scrollPosition = $(window).scrollTop();
        var homeSection = $('#home').offset().top;
        var navbar = $('.navbar');
        var navbarBrand = $('.navbar-brand');
        var navbarNav = $('.navbar-nav');

        if (scrollPosition >= homeSection && scrollPosition < $('#sobre').offset().top) {
            navbar.removeClass('bg-dark').addClass('bg-transparent');
            navbarBrand.addClass('d-none');
            navbarNav.addClass('me-auto');
        } else {
            navbar.removeClass('bg-transparent').addClass('bg-dark');
            navbarBrand.removeClass('d-none');
            navbarNav.removeClass('me-auto');
        }
    }

    updateNavbar();
    $(window).on('scroll', updateNavbar);
});