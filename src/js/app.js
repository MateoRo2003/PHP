document.addEventListener('DOMContentLoaded', () => {
    const alertas = document.querySelectorAll('.alerta.error');
    alertas.forEach((alerta, index) => {
        setTimeout(() => {
            console.log(`Aplicando animación a la alerta ${index}`);
            alerta.classList.add('animar');
        }, index * 2000);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.form');
    const inputs = form.querySelectorAll('input');

    function aplicarError(input) {
        input.classList.add('input-error');
    }

    function quitarError(input) {
        input.classList.remove('input-error');
    }

    form.addEventListener('submit', function(event) {
        let hayErrores = false;

        inputs.forEach(input => {
            if (!input.value) {
                aplicarError(input);
                hayErrores = true;
            } else {
                quitarError(input);
            }
        });

        if (hayErrores) {
            event.preventDefault();
        }
    });

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (!this.value) {
                aplicarError(this);
            } else {
                quitarError(this);
            }
        });
    });
});


let paso = 1;
const pasoInicial=1;
const pasoFinal=3;

    document.addEventListener('DOMContentLoaded', function(){
        iniciarApp();
    });
    function iniciarApp(){

        mostrarSeccion();
        tabs();
        botonesPaginador();
        paginaSiguiente();
        paginaAnterior();
    }
    
    function mostrarSeccion(){
        //Ocultar la seccion

        const seccionAnterior = document.querySelector('.mostrar');
        if(seccionAnterior){
            seccionAnterior.classList.remove('mostrar');
        }
        
        //seleciona la seccion
        const pasoSelector= `#paso-${paso}`;
        const seccion= document.querySelector(pasoSelector);
        seccion.classList.add('mostrar');

        //quitar la clase marcada(color)
        const tabAnterior=document.querySelector('.actual');
        if(tabAnterior){
            tabAnterior.classList.remove('actual');
        }

        //resaltar tabs
        const tab=document.querySelector(`[data-paso="${paso}"]`);
        tab.classList.add('actual');
    
    
    }


    function tabs(){

        const botones = document.querySelectorAll('.tabs button');
        console.log(botones);

        botones.forEach( boton =>  {
            boton.addEventListener('click',function(e){
                paso = parseInt(e.target.dataset.paso);

                mostrarSeccion();
                botonesPaginador();
            });
        } );
    }

    function botonesPaginador(){

        const paginaAnterior = document.querySelector('#anterior');
        const paginaSiguiente = document.querySelector('#siguiente');
        

        if(paso === 1){
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar');
        }
        else if(paso===3){
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
        }else{
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.remove('ocultar');
        }
        mostrarSeccion();
    }


    function paginaAnterior(){
        const paginaAnterior = document.querySelector('#anterior');
        paginaAnterior.addEventListener('click', function() {
            if(paso<=pasoInicial)return;           
            paso--;
            botonesPaginador();
        });
    }
    function paginaSiguiente(){
        const paginaSiguiente = document.querySelector('#siguiente');
        paginaSiguiente.addEventListener('click', function() {
            if(paso>=pasoFinal)return;           
            paso++;
            botonesPaginador();
        });
    }



    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.form');
        const password = document.querySelector('#password');
        const passwordConfirm = document.querySelector('#passwordConfirmado');
    
        form.addEventListener('submit', function(event) {
            // Validación de que las contraseñas coincidan
            if (password.value !== passwordConfirm.value) {
                event.preventDefault(); // Previene el envío del formulario
                alert('Las contraseñas no coinciden');
                return;
            }
    
            // Validación de seguridad de la contraseña
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/; // Al menos una letra minúscula, una mayúscula, y un número
            if (!regex.test(password.value)) {
                event.preventDefault();
                alert('La contraseña debe tener al menos una letra minúscula, una mayúscula, y un número');
                return;
            }
        });
    });
    