import '/node_modules/izitoast/dist/js/iziToast.min.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

const clienteForm = document.getElementById('ClienteForm');
const url = window.location.pathname;
const id = url.substring(url.lastIndexOf('/') + 1);

clienteForm.onsubmit = function (event) {
    const data = {
        cuil_cuit: clienteForm['cuil_cuit'].value,
        razon_social: clienteForm['razon_social'].value,
        nombre: clienteForm['nombre'].value,
        apellido: clienteForm['apellido'].value,
        telefono: clienteForm['telefono'].value,
        email: clienteForm['email'].value
    }

    fetch(`/api/clientes/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(httpResp => httpResp.json()).then(response => {
        if (response.status === 'success') {
            window.iziToast.success({ message: 'El cliente se actualizo con exito' });
        } else {
            window.iziToast.error({ message: response.error });
        }
    }).catch(reason => {
        window.iziToast.error({ message: reason.toString() });
    });

    return false;
}

function fecthCliente() {
    fetch(`/api/clientes/${id}`)
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === 'success') {
                procesarCliente(response.data.cliente);
            } else {
                window.iziToast.error({ message: 'Error al obtener el cliente' });
            }
        })
        .catch(reason => {
            window.iziToast.error({ message: reason.toString() });
        });
}

function procesarCliente(cliente) {
    clienteForm['cuil_cuit'].value = cliente.cuilCuit;
    clienteForm['razon_social'].value = cliente.razonSocial;
    clienteForm['nombre'].value = cliente.nombre;
    clienteForm['apellido'].value = cliente.apellido;
    clienteForm['telefono'].value = cliente.telefono;
    clienteForm['email'].value = cliente.email;
}

fecthCliente();