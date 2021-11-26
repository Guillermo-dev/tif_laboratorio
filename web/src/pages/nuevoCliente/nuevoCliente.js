import '/node_modules/izitoast/dist/js/iziToast.min.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

const clienteForm = document.getElementById('ClienteForm');

clienteForm.onsubmit = function (event) {

    if(clienteForm['razon_social'].value === 'Seleccione una opción'){
        window.iziToast.warning({ message: 'Seleccionar una razon social valida' });
        return false;
    }

    const data = {
        cuil_cuit: clienteForm['cuil_cuit'].value,
        razon_social: clienteForm['razon_social'].value,
        nombre: clienteForm['nombre'].value,
        apellido: clienteForm['apellido'].value,
        telefono: clienteForm['telefono'].value,
        email: clienteForm['email'].value
    }

    fetch(`/api/clientes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(httpResp => httpResp.json()).then(response => {
        if (response.status === 'success') {
            window.iziToast.success({ message: 'El cliente se creo con exito' });
            clienteForm.reset();
        } else {
            window.iziToast.error({ message: response.error.error });
        }
    }).catch(reason => {
        window.iziToast.error({ message: reason.toString() });
    });

    return false;
}