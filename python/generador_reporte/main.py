##Codigo fuente del script para generar y enviar por email la tabla de reporte del punto 5
from models.Campania import Campania
from models.Cliente import Cliente
from models.Localidad import Localidad
from campaniaToExcel import campaniaToExcel

def numeroInvalido(numero):
    try:
        numero = int(numero)
        return False
    except ValueError:
        return True
        
try:
    campanias = Campania.getCampanias()
    i = 1
    for campania in campanias:
        print(i,') Nombre:', campania[1],', Fecha inicio:',campania[5])
        i += 1
    res = input('Seleccione la campaña que quiere enviar el reporte:')
    while(numeroInvalido(res)):
        res = input('Seleccione la campaña que quiere enviar el reporte:')
    res = int(res)-1
    
    campania = Campania(campanias[res][0],campanias[res][1],campanias[res][2],campanias[res][3],campanias[res][4],campanias[res][5],campanias[res][6])
    
    clienteData = Cliente.getClienteByCampania(str(campania.get_campania_id()))
    cliente = Cliente(clienteData[0][0],clienteData[0][1],clienteData[0][2],clienteData[0][3],clienteData[0][4],clienteData[0][5],clienteData[0][6])

    localidadesData = Localidad.getLocalidadByCampania(str(campania.get_campania_id()))
    localidades = []
    for localidadData in localidadesData:
        localidad = Localidad(localidadData[0],localidadData[1],localidadData[2],localidadData[3])
        localidades.append(localidad)
         
except Exception as e:
    print ("Error interno:", e)

try:
    m2e = campaniaToExcel('Reporte de Campaña')
    
    m2e.agregarPlanilla(campania, cliente, localidades)
    
    m2e.guardarPlanilla("reporte_campania.xls")
    
except Exception as e:
    print ("Error al crear el reporte:", e)
