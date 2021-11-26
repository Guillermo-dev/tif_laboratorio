import '/node_modules/izitoast/dist/js/iziToast.min.js';
import '/node_modules/sweetalert2/dist/sweetalert2.min.js';

let cliente = null;

/************************************************/

const clienteForm = document.getElementById('ClienteForm');

clienteForm.onsubmit = function (event) {
    try {
        cliente = null;

        if (clienteForm['cuil_cuit'].value.length === 0) {
            window.iziToast.warning({message: 'Debe ingresar un cuil/cuit'});

            clienteForm.classList.remove('cliente-info');

            clienteForm.reset();

            return false;
        }

        fetch(`/api/clientes/?cuil_cuit=${clienteForm['cuil_cuit'].value}`)
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
                    window.iziToast.error({message: response.error.error});
                }
            })
            .catch(reason => {
                window.iziToast.error({message: reason.toString()});
            });
    } catch (error) {
        window.iziToast.error({message: error.toString()});
    }
    return false;
}

function procesarCliente(cliente) {
    clienteForm['nombre'].value = cliente.nombre;

    clienteForm['apellido'].value = cliente.apellido;
}

/************************************************/

const campaniaForm = document.getElementById('CampaniaForm');

const dropLocalidades = document.getElementById('DropLocalidades');

campaniaForm.onsubmit = function (event) {
    try {
        if (!cliente) {
            window.iziToast.warning({message: 'Debe seleccionar un cliente'});
            return false;
        }

        if(campaniaForm['text_SMS'].value.length >= 160){
            window.iziToast.warning({message: 'El mensaje debe ser de menos de 160 caracteres'});
            return false;
        }
        
        let localidadArr = [];
        Array.from(campaniaForm['localidad'].selectedOptions).forEach( localidad =>{
            localidadArr.push(localidad.value);
        })
       
        const data = {
            nombre: campaniaForm['nombre'].value,
            cantidad_mensajes: campaniaForm['cantidad_mensajes'].value,
            text_SMS: campaniaForm['text_SMS'].value,
            fecha_inicio: campaniaForm['fecha_inicio'].value,
            cliente_id: cliente.id,
            localidades: localidadArr
        }

        fetch(`/api/campanias`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(httpResp => httpResp.json()).then(response => {
            if (response.status === 'success') {
                window.iziToast.success({message: 'La campaña se creo con exito'});
                campaniaForm.reset();
            } else {
                window.iziToast.error({message: response.error.error});
            }
        }).catch(reason => {
            window.iziToast.error({message: reason.toString()});
        });
    } catch (error) {
        window.iziToast.error({message: error.toString()});
    }
    return false;
}

function  createOptions(localidades){
    localidades.forEach((localidad) => {
        const option = document.createElement("option");
        option.value = localidad.localidad_id
        option.innerText = localidad.pais + ', ' + localidad.provincia + ', ' + localidad.ciudad;

        dropLocalidades.append(option);
    });
}

function fetchLocalidades(){
    fetch('/api/localidades')
        .then(httpResp => httpResp.json())
        .then(response => {
            if (response.status === 'success') {
                createOptions(response.data.localidades);
            } else {
                window.iziToast.error({ message: 'Error al obtener la campaña' });
            }
        })
        .catch(reason => {
            window.iziToast.error({ message: reason.toString() });
        });
}

fetchLocalidades();