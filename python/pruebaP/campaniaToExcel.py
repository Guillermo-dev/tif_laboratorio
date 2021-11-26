import xlwt
from datetime import datetime

class campaniaToExcel:
    def __init__(self, name):
        self.wb = xlwt.Workbook()
        self.ws = self.wb.add_sheet(name, cell_overwrite_ok=True)
    
        self.ws.write(0, 0, name)
        
        columnas = ["idCampaña", 
                    "nombre", 
                    "texto", 
                    "cantSMS", 
                    "estado", 
                    "fecha", 
                    "Cliente"]
        
        c=0
        for columna in columnas:
            self.ws.write(1, c, columna)
            c = c + 1
            
        self.fila = 2
        
    def agregarPlanilla(self,item):
        self.ws.write(self.fila, 0, item.campania_id)
        self.ws.write(self.fila, 1, item.nombre)
        self.ws.write(self.fila, 2, item.texto_SMS)
        self.ws.write(self.fila, 3, item.cantidad_mensajes)
        self.ws.write(self.fila, 4, item.estado)
        self.ws.write(self.fila, 5, item.fecha_inicio)
        self.ws.write(self.fila, 6, item.cliente_id)
        
        self.fila = self.fila + 1
        
    def guardarPlanilla(self,archivo):
        self.wb.save(archivo)
        print("Archivo Generado")