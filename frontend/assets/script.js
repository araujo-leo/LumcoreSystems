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
        if ($(window).width() < 850) { 
            $('.servico-info').removeClass('col-md-5').addClass('col-md-6')

        } else {
            $('.servico-info').addClass('col-md-5').removeClass('col-md-6')

        }        if ($(window).width() < 850) { 
            $('.servico-info').removeClass('col-md-5').addClass('col-md-6')

        } else {
            $('.servico-info').addClass('col-md-5').removeClass('col-md-6')

        }
        if ($(window).width() < 1024) { 
            $('.ocultar-mobile').removeClass('d-flex').hide();
            $('#sobre-main').removeClass('ms-5 px-5').addClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.text-secondary').removeClass('ms-5 px-5').addClass('text-center');
            $('#sobre').addClass('align-items-center');
            $('#mvv').removeClass('flex-row').addClass('flex-column align-items-center');
            $('#mvv-div').removeClass('row').addClass('d-flex flex-column align-items-center');
            $('#contato-main').removeClass('me-3 margin-left').addClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.contato-info').addClass('justify-content-center');
            $('#form-div').removeClass('ms-3 margin-right').addClass('d-flex flex-column justify-content-center align-items-center');
            $('#contact-div').removeClass('ms-3 margin-right');
            $('#contato-text').addClass('d-flex flex-column align-items-center');
            $('#footer-row').addClass('d-flex flex-column align-items-center');

            $('#footer-links').hide();
            $('.img-paisagem').hide();
            $('.img-paisagem-mobile').show();
        } else {
            $('.ocultar-mobile').addClass('d-flex').show();
            $('#sobre-main').addClass('ms-5 px-5').removeClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.text-secondary').addClass('ms-5 px-5').removeClass('text-center');
            $('#sobre').removeClass('align-items-center');
            $('#mvv').addClass('flex-row').removeClass('flex-column align-items-center');
            $('#mvv-div').addClass('row').removeClass('d-flex flex-column align-items-center');
            $('#contato-main').addClass('me-3 margin-left').removeClass('text-center d-flex flex-column justify-content-center align-items-center');
            $('.contato-info').removeClass('justify-content-center');
            $('#form-div').addClass('ms-3 margin-right').removeClass('d-flex flex-column justify-content-center align-items-center');
            $('#contact-div').addClass('ms-3 margin-right');
            $('#contato-text').removeClass('d-flex flex-column align-items-center');
            $('#footer-row').removeClass('d-flex flex-column align-items-center');


            $('#footer-links').show();
            $('.img-paisagem').show();
            $('.img-paisagem-mobile').hide();
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
    inputSobrenome.keydown(function (event) {
        if (event.key === "Backspace") {
            if (inputSobrenome.val() === "") {
                inputNome.focus();
            }
        }
    })

    //contact
    const contactForm = $('#contact-form');
    const submitButton = contactForm.find('button[type="submit"]');

    contactForm.submit(function (event) {
        event.preventDefault();
        const originalButtonText = submitButton.html();

        submitButton.prop('disabled', true).html(`<i class="fa fa-spinner fa-spin"></i> Enviando...`);


        let nome = $('#input-nome').val() + ' ' + $('#input-sobrenome').val();
        let email = $('#input-email').val();
        let mensagem = $('#txt-mensagem').val();

        try {            

            fetch('https://talentosdoifsp.gru.br/LumcoreSystems/backend/public/?route=contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: nome, email: email, message: mensagem})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Código: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    alert(data['message']);
                })
                .catch(error =>
                    console.error('Erro na requisição:', error
                    ))
                .finally(() => {
                    submitButton.prop('disabled', false).html(originalButtonText);
                });
        } catch (error) {
            console.log("Erro no try-catch:", error);
        }

    });

    //unsubscribe
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    var email = getUrlParameter('unsubscribe');

    if (email) {
         // Esconder todos os elementos do body
         const bodyElements = $('body').children();
         bodyElements.each(function() {
            $(this).addClass('d-none');
        });
 
         // Mostrar o carregamento
         const loading = $('#loading');
         loading.removeClass('d-none');


        try {
            fetch('https://talentosdoifsp.gru.br/LumcoreSystems/backend/public/?route=unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Código: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    alert(data['message']);

                    window.location.replace('/');
                })
                .catch(error =>
                    console.error('Erro na requisição:', error
                    ));
        } catch (error) {
            console.log("Erro no try-catch:", error);
        }

    };

    function RevealFunction(){
        window.sr = ScrollReveal({duration: 900, distance: '50px', easing: 'ease-out', origin:'bottom'});

        sr.reveal('#sobre-main,.text-secondary,#equipe-title,#servicos-title,#contato-text,#contact-div',{interval:450});
        sr.reveal('.mvv',{interval:350,distance:'100px',delay:750});
        sr.reveal('.img-paisagem',{interval:350,origin:'right'});

        sr.reveal('.perfil',{interval:350,distance:'100px',delay:750});

        sr.reveal('.servico-info',{distance:'100px',delay:750});
        
        sr.reveal('.contato-info',{interval:350,delay:450});
    }

    RevealFunction()
    //subscribe
    const subscribeForm= $('#subscribe-form');
    const submitSubscribeButton = subscribeForm.find('button[type="submit"]');

    subscribeForm.submit(function (event) {
        event.preventDefault();
        const originalButtonText = submitSubscribeButton.html();

        submitSubscribeButton.prop('disabled', true).html(`<i class="fa fa-spinner fa-spin"></i> Enviando...`);

        let email = $('#subscribe-email').val();

        try {            

            fetch('https://talentosdoifsp.gru.br/LumcoreSystems/backend/public/?route=subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Código: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);

                    alert(data['message']);
                })
                .catch(error =>
                    console.error('Erro na requisição:', error
                    ))
                .finally(() => {
                    submitSubscribeButton.prop('disabled', false).html(originalButtonText);
                });
        } catch (error) {
            console.log("Erro no try-catch:", error);
        }

    });

});