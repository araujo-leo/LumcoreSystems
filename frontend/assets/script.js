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
        if ($(window).width() <= 1024) { // Defina o tamanho para considerar 'mobile'
            $('.ocultar-mobile').removeClass('d-flex').hide();
            $('#sobre-main').removeClass('ms-5 px-5').addClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.text-secondary').removeClass('ms-5 px-5').addClass('text-center');
            $('#sobre').addClass('align-items-center');
            $('#mvv').removeClass('flex-row').addClass('flex-column align-items-center');
            $('#mvv-div').removeClass('row').addClass('d-flex flex-column align-items-center');
            $('#contato-main').removeClass('me-3 margin-left').addClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.contato-info').addClass('justify-content-center');
            $('#form-div').removeClass('ms-3 margin-right').addClass('d-flex flex-column justify-content-center align-items-center')

        } else {
            $('.ocultar-mobile').addClass('d-flex').show();
            $('#sobre-main').addClass('ms-5 px-5').removeClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.text-secondary').addClass('ms-5 px-5').removeClass('text-center');
            $('#sobre').removeClass('align-items-center');
            $('#mvv').addClass('flex-row').removeClass('flex-column align-items-center');
            $('#mvv-div').addClass('row').removeClass('d-flex flex-column align-items-center');
            $('#contato-main').addClass('me-3 margin-left').removeClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.contato-info').removeClass('justify-content-center');
            $('#form-div').addClass('ms-3 margin-right').removeClass('d-flex flex-column justify-content-center align-items-center')

        }
    }

    // Chamar a função no carregamento da página e quando a janela for redimensionada
    adjustDivForMobile();
    $(window).resize(adjustDivForMobile);

    let inputNome = $('#input-nome');
    let inputSobrenome = $('#input-sobrenome');
    let text = '';

    inputNome.keydown(function (event) {
        inputNome.val(inputNome.val().replace(/\s/g, ""));
        if (event.key === " ") {
            inputSobrenome.focus();
            event.preventDefault();
        }
    });

    //Se usuário apaga todo o sobrenome volta pro nome
    inputSobrenome.keydown(function (event){
        if(event.key === "Backspace"){
            if(inputSobrenome.val() === ""){
                inputNome.focus();
            }
        }
    })

});