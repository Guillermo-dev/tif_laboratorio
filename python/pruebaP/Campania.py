class Campania(object):
    def __init__(self,campania_id, nombre, texto_SMS, cantidad_mensajes, estado, cliente_id, fecha_inicio):
        self.campania_id = campania_id
        self.nombre = nombre
        self.texto_SMS = texto_SMS
        self.cantidad_mensajes = str(cantidad_mensajes)
        self.estado = estado
        self.fecha_inicio = fecha_inicio
        self.cliente_id = cliente_id 
        
    def listar(self):
        print (self.campania_id,self.nombre,self.texto_SMS,self.cantidad_mensajes,self.estado,self.fecha_inicio,self.cliente_id)