$(document).ready(function () {
    // FUNÇÃO DE ATUALIZAR A NAVBAR AO SAIR DO HOME
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

    // FUNÇÃO PARA CHECAR E ATUALIZAR SEÇÃO ATUAL DA PÁGINA
    function updateActiveSection() {
        let scrollPosition = $(window).scrollTop();
        let offset = $(window).height() / 3; // Ajuste para ativar antes da seção ocupar a tela inteira

        $('section').each(function () {
            let sectionTop = $(this).offset().top - offset;
            let sectionBottom = sectionTop + $(this).outerHeight();
            let sectionId = $(this).attr('id');

            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                $('.nav-link').removeClass('active');
                $(`.nav-link[href="#${sectionId}"]`).addClass('active');
            }
        });
    }

    updateActiveSection();
    $(window).on('scroll', updateActiveSection);
});