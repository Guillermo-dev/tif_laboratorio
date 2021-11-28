##Codigo fuente del script para generar y enviar por email la tabla de reporte del punto 5
from models.Connection import Connection
from models.Campania import Campania
from models.Cliente import Cliente
from models.Localidad import Localidad
from campaniaToExcel import campaniaToExcel


cursor = Connection.getConnection()

def numeroInvalido(numero):
    try:
        numero = int(numero)
        return False
    except ValueError:
        return True
        
try:
    cursor.execute("SELECT campania_id, nombre, texto_SMS, cantidad_mensajes, estado, fecha_inicio, cliente_id FROM campanias WHERE estado = 'finalizada'")
    campanias = cursor.fetchall()
    i = 1
    print(campanias)
    for campania in campanias:
        print(i,') Nombre:', campania[1],', Fecha inicio:',campania[5])
        i += 1
    res = input('Seleccione la campaña que quiere enviar el reporte:')
    while(numeroInvalido(res)):
        res = input('Seleccione la campaña que quiere enviar el reporte:')
    res = int(res)-1
    
    campania = Campania(campanias[res][0],campanias[res][1],campanias[res][2],campanias[res][3],campanias[res][4],campanias[res][5],campanias[res][6])
    
    cursor.execute("SELECT C.cliente_id, C.cuil_cuit, C.razon_social, C.nombre, C.apellido, C.telefono, C.email FROM clientes C INNER JOIN campanias CA ON CA.cliente_id = CA.cliente_id WHERE CA.campania_id = "+str(campania.get_campania_id()))
    clienteData = cursor.fetchall()
    cliente = Cliente(clienteData[0][0],clienteData[0][1],clienteData[0][2],clienteData[0][3],clienteData[0][4],clienteData[0][5],clienteData[0][6])
    
    cursor.execute("SELECT L.localidad_id, L.pais, L.provincia, L.ciudad FROM campanias_localidades C INNER JOIN localidades L ON L.localidad_id = C.localidad_id WHERE C.campania_id ="+str(campania.get_campania_id()))
    localidadesData= cursor.fetchall()
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
