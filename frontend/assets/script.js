$(document).ready(function () {
    // FUNÇÃO DE ATUALIZAR A NAVBAR AO SAIR DO HOME
    function updateNavbar() {
        var scrollPosition = $(window).scrollTop();
        var homeSection = $('#home').offset().top;
        var navbar = $('.navbar');
        var navbarBrand = $('.navbar-brand');
        var navbarNav = $('.navbar-nav');
        var navbarToggler = $('.navbar-toggler-icon')

        if (scrollPosition >= homeSection && scrollPosition < $('#sobre').offset().top) {
            navbar.removeClass('bg-dark').addClass('bg-transparent');
            navbarBrand.addClass('d-none');
            navbarToggler.addClass('d-none');
            navbarNav.addClass('me-auto');
        } else {
            navbar.removeClass('bg-transparent').addClass('bg-dark');
            navbarBrand.removeClass('d-none');
            navbarToggler.removeClass('d-none');
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

    // RESPONSIVIDADE
    function adjustDivForMobile() {
        if ($(window).width() <= 768) { // Defina o tamanho para considerar 'mobile'
            $('.ocultar-mobile').removeClass('d-flex').hide();
            $('#sobre-main').removeClass('ms-5 px-5').addClass('text-center');
            $('.text-secondary').removeClass('ms-5 px-5').addClass('text-center');
            $('#sobre').addClass('align-items-center');
            $('#mvv').removeClass('flex-row').addClass('flex-column align-items-center');
            $('#mvv-div').removeClass('row');

            $('#equipe-desktop').hide();
        } else {
            $('.ocultar-mobile').addClass('d-flex').show();
            $('#sobre-main').addClass('ms-5 px-5').removeClass('text-center');
            $('.text-secondary').addClass('ms-5 px-5').removeClass('text-center');
            $('#sobre').removeClass('align-items-center');
            $('#mvv').addClass('flex-row').removeClass('flex-column align-items-center');
            $('#mvv-div').addClass('row');

            $('#equipe-desktop').show();
        }
    }

    // Chamar a função no carregamento da página e quando a janela for redimensionada
    adjustDivForMobile();
    $(window).resize(adjustDivForMobile);


    let inputNome = $('#input-nome');
    let inputSobrenome = $('#input-sobrenome');
    let text = '';

    inputNome.keyup(function (event) {
        inputNome.val(inputNome.val().replace(/\s/g, ""));
        if (event.key === " ") {
            console.log(inputNome.val());
            inputSobrenome.focus();
            event.preventDefault();
        }
    });

    inputSobrenome.keyup(function (event){
        if(event.key === "Backspace"){
            console.log("backspace");
            if(inputSobrenome.val() === ""){
                console.log("teste");
            }
        }
    })

});