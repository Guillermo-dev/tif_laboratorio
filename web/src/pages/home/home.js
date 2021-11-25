import '/node_modules/izitoast/src/js/iziToast.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

const campaniasLista = document.getElementById('CampaniasLista');

const campaniaTemplate = document.getElementById('CampaniaTemplate');

const moreButton = document.getElementById('MoreButton');

const buscadorForm = document.getElementById('Buscador');

buscadorForm.onsubmit = function () {
    try {
        fetchCampanias();
    } catch (error) {
        window.iziToast.error({message: error.toString()});
    }
    return false;
}

moreButton.onclick = procesarCampanias;

let offset = 5;
let bufferPointer = 0;
let campaniasBuffer = [];

function fetchCampanias() {
    let query = '';

    if (buscadorForm['search'].value.length > 0) {
        query = `?search=${buscadorForm['search'].value}`
    }

    fetch(`/api/campanias${query}`)
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === 'success') {
                campaniasBuffer = response.data.campanias;

                campaniasLista.innerHTML = "";

                bufferPointer = 0;

                procesarCampanias();
            } else {
                window.iziToast.error({message: 'Error al obtener las campañas'});
            }
        })
        .catch(reason => {
            window.iziToast.error({message: reason.toString()});
        });
}

function createCampania(campania) {
    const element = campaniaTemplate.content.firstElementChild.cloneNode(true);

    const data = element.querySelectorAll('[data-js="data"]');

    data[0].textContent = campania.nombre;

    data[1].textContent = campania.fecha_inicio;

    data[2].textContent = campania.cantidad_mensajes;

    data[3].textContent = campania.estado;

    data[4].textContent = campania.texto_SMS;

    const buttons = element.querySelectorAll('[data-js="button"]');

    buttons[0].onclick = function () {
        location.href =
            `/editarCampania/${campania.campania_id}`
        ;
    }

    buttons[1].onclick = function () {
        window.Sweetalert2.fire({
            icon: 'question',
            title: '¿Eliminar campaña?',
            html: 'Estas seguro que deseas eliminar la campaña?',
            showCancelButton: true,
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                fetch(
                    `/api/campanias/${campania.campania_id}`
                    , {
                        method: 'DELETE',
                    })
                    .then(httpResp => httpResp.json())
                    .then(response => {
                        if (response.status === 'success') {
                            window.iziToast.success({message: 'La campaña se eliminó con éxito!'});
                            element.remove();
                        } else {
                            window.iziToast.error({message: 'Error al eliminar la campaña'});
                        }
                    })
                    .catch(reason => {
                        window.iziToast.error({message: reason.toString()});
                    });
            }
        });
    }

    return element
}

function procesarCampanias() {
    let to = bufferPointer + offset;
    for (let i = bufferPointer; i < to && i < campaniasBuffer.length; i++, bufferPointer++) {
        campaniasLista.append(createCampania(campaniasBuffer[i]));
    }
    if (bufferPointer >= campaniasBuffer.length) {
        moreButton.classList.add('d-none');
    }
}

fetchCampanias();