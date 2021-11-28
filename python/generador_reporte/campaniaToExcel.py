import xlwt

class campaniaToExcel:
    def __init__(self, name):
        self.wb = xlwt.Workbook()
        self.ws = self.wb.add_sheet(name, cell_overwrite_ok=True)
    
        self.ws.write(0, 0, name)
        
        columnas = ["Nombre de campaña", 
                    "Texto del mensaje", 
                    "Cantidad de SMS enviados", 
                    "Fecha de inicio",
                    "Cuil/Cuit del cliente",
                    "Localidades"]
        
        c=0
        for columna in columnas:
            self.ws.write(1, c, columna)
            c = c + 1
            
        self.fila = 2
        
    def agregarPlanilla(self,campania, cliente, localidades):
        self.ws.write(self.fila, 0, campania.get_nombre())
        self.ws.write(self.fila, 1, campania.get_texto_SMS())
        self.ws.write(self.fila, 2, campania.get_cantidad_mensajes())
        self.ws.write(self.fila, 3, campania.get_fecha_inicio())
        self.ws.write(self.fila, 4, cliente.get_cuil_cuit())
        string_localidades=''
        for localidad in localidades:
            string_localidades = string_localidades + localidad.get_pais() +', '+ localidad.get_provincia()+', '+localidad.get_ciudad()+' -- '
        self.ws.write(self.fila, 5, string_localidades)
 
        self.fila = self.fila + 1
        
    def guardarPlanilla(self,archivo):
        self.wb.save(archivo)
        print("Archivo Generado")
