from datetime import datetime, timedelta
from models.Connection import Connection
from Campania import Campania
from campaniaToExcel import campaniaToExcel



cursor = Connection.getConnection()


try:
    cursor = Connection.getConnection()
    
    query = "SELECT campania_id, nombre, texto_SMS, cantidad_mensajes, estado, cliente_id, fecha_inicio FROM campanias"
    
    cursor.execute(query)
    m2e = campaniaToExcel('Reporte de Campaña')
    
    campanias = cursor.fetchall()
    for campaniaData in campanias:
        print(campaniaData)
        campania = Campania(campaniaData[0],campaniaData[1],campaniaData[2],campaniaData[3],campaniaData[4],campaniaData[5],campaniaData[6])
        campania.listar()
        
        m2e.agregarPlanilla(campania)
    
    m2e.guardarPlanilla("reporte_campania.xls")
except Exception as e:
    print ("Received error:", e)
    
    

