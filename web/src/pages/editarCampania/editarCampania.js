import '/node_modules/izitoast/dist/js/iziToast.min.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

let cliente = null;

/************************************************/

const clienteForm = document.getElementById('ClienteForm');

clienteForm.onsubmit = function (event) {
    try {
        cliente = null;

        if (clienteForm['cuil_cuit'].value.length === 0) {
            window.iziToast.warning({ message: 'Debe ingresar un cuil/cuit' });

            clienteForm.classList.remove('cliente-info');

            clienteForm.reset();

            return false;
        }
        fetchCliente();
    } catch (error) {
        window.iziToast.error({ message: error.toString() });
    }
    return false;
}

function fetchCliente() {
    fetch(`/api/clientes?cuil_cuit=${clienteForm['cuil_cuit'].value}`)
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === "success") {
                if (response.data.cliente !== null) {
                    clienteForm.classList.add('cliente-info');

                    procesarCliente(response.data.cliente);

                    cliente = response.data.cliente;
                } else {
                    let cuil = clienteForm['cuil_cuit'].value;

                    clienteForm.classList.remove('cliente-info');

                    clienteForm.reset();

                    clienteForm['cuil_cuit'].value = cuil;

                    Sweetalert2.fire({
                        icon: 'question',
                        title: 'El cliente no existe',
                        html: 'Desea crearlo?',
                        showCancelButton: true,
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) {
                            location.href = '/nuevoCliente'
                        }
                    });
                }
            } else {
                window.iziToast.error({ message: response.error });
            }
        })
        .catch(reason => {
            window.iziToast.error({ message: reason.toString() });
        });
}

function procesarCliente(cliente) {
    clienteForm['nombre'].value = cliente.nombre;
    clienteForm['apellido'].value = cliente.apellido;
}

/************************************************/

const campaniaForm = document.getElementById('CampaniaForm');
const url = window.location.pathname;
const id = url.substring(url.lastIndexOf('/') + 1);

campaniaForm.onsubmit = function (event) {
    try {
        if (!cliente) {
            window.iziToast.warning({ message: 'Debe seleccionar un cliente' });
            return false;
        }

        if (campaniaForm['text_SMS'].value.length >= 160) {
            window.iziToast.warning({ message: 'El mensaje debe ser de menos de 160 caracteres' });
            return false;
        }

        const data = {
            nombre: campaniaForm['nombre'].value,
            cantidad_mensajes: campaniaForm['cantidad_mensajes'].value,
            text_SMS: campaniaForm['text_SMS'].value,
            fecha_inicio: campaniaForm['fecha_inicio'].value,
            cliente_id: cliente.cliente_id,
        }

        fetch(`/api/campanias${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(httpResp => httpResp.json()).then(response => {
            if (response.status === 'success') {
                window.iziToast.success({ message: 'El cliente se actualizo con exito' });
                campaniaForm.reset();
            } else {
                window.iziToast.error({ message: response.error });
            }
        }).catch(reason => {
            window.iziToast.error({ message: reason.toString() });
        });
    } catch (error) {
        window.iziToast.error({ message: error.toString() });
    }
    return false;
}

function fetchCampania() {
    fetch(`/api/campanias${id}`)
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === 'success') {
                procesarCampania(
                    response.data.campania,
                    response.data.cliente,
                    response.data.localidades);
            } else {
                window.iziToast.error({ message: 'Error al obtener la campaña' });
            }
        })
        .catch(reason => {
            window.iziToast.error({ message: reason.toString() });
        });
}

function procesarCampania(campania, cliente, localidades) {
    campaniaForm['nombre'].value = campania.nombre;
    campaniaForm['cantidad_mensajes'].value = campania.cantidad_mensajes;
    campaniaForm['text_SMS'].value = campania.texto_SMS;
    campaniaForm['fecha_inicio'].value = campania.fecha_inicio;

    clienteForm['cuil_cuit'].value = cliente.cuil_cuit;
    fetchCliente();

    localidades.forEach( (localidad) => {
        console.log(localidad);
    });
}

fetchCampania();