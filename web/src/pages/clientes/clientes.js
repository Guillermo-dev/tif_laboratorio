import '/node_modules/izitoast/src/js/iziToast.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

const clientesLista = document.getElementById('ClientesLista');

const clienteTemplate = document.getElementById('ClienteTemplate');

const moreButton = document.getElementById('MoreButton');

const buscadorForm = document.getElementById('Buscador');

buscadorForm.onsubmit = function () {
    try {
        fetchClientes();
    } catch (error) {
        window.iziToast.error({message: error.toString()});
    }
    return false;
}

moreButton.onclick = procesarClientes;

let offset = 5;
let bufferPointer = 0;
let clientesBuffer = [];

function fetchClientes() {
    let query = '';
    if (buscadorForm['search'].value.length > 0) {
        query = `?search=${buscadorForm['search'].value}`
    }

    fetch(`/api/clientes/${query}`)
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === 'success') {
                clientesBuffer = response.data.clientes;

                clientesLista.innerHTML = "";

                bufferPointer = 0;

                procesarClientes();
            } else {
                window.iziToast.error({message: 'Error al obtener los clientes'});
            }
        })
        .catch(reason => {
            window.iziToast.error({message: reason.toString()});
        });
}

function createCliente(cliente) {
    const element = clienteTemplate.content.firstElementChild.cloneNode(true);

    const data = element.querySelectorAll('[data-js="data"]');

    data[0].textContent = cliente.nombre;

    data[1].textContent = cliente.apellido;

    data[2].textContent = cliente.cuil_cuit;

    data[3].textContent = cliente.razon_social;

    data[4].textContent = cliente.telefono;

    data[5].textContent = cliente.email;

    const buttons = element.querySelectorAll('[data-js="button"]');

    buttons[0].onclick = function () {
        location.href =
            `/editarCliente/${cliente.cliente_id}`
        ;
    }

    buttons[1].onclick = function () {
        window.Sweetalert2.fire({
            icon: 'question',
            title: '¿Eliminar cliente?',
            html: 'Estas seguro que deseas eliminar el cliente?',
            showCancelButton: true,
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) {
                fetch(
                    `/api/clientes/${cliente.cliente_id}`
                    , {
                        method: 'DELETE',
                    })
                    .then(httpResp => httpResp.json())
                    .then(response => {
                        if (response.status === 'success') {
                            window.iziToast.success({message: 'El cliente se eliminó con éxito!'});
                            element.remove();
                        } else {
                            window.iziToast.error({message: 'Error al eliminar el cliente'});
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

function procesarClientes() {
    let to = bufferPointer + offset;
    for (let i = bufferPointer; i < to && i < clientesBuffer.length; i++, bufferPointer++) {
        clientesLista.append(createCliente(clientesBuffer[i]));
    }
    if (bufferPointer >= clientesBuffer.length) {
        moreButton.classList.add('d-none');
    }
}

fetchClientes();
