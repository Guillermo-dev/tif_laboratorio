class Campania(object):
    __campania_id = 0
    __nombre = ''
    __texto_SMS=''
    __cantidad_mensajes=''
    __estado=''
    __fecha_inicio=''
    __cliente_id=''
    
    def __init__(self,campania_id, nombre, texto_SMS, cantidad_mensajes, estado, fecha_inicio, cliente_id):
        self.__campania_id = campania_id
        self.__nombre = nombre
        self.__texto_SMS = texto_SMS
        self.__cantidad_mensajes = str(cantidad_mensajes)
        self.__estado = estado
        self.__fecha_inicio = fecha_inicio
        self.__cliente_id = cliente_id 
        
    def get_campania_id(self):
        return self.__campania_id
    
    def get_nombre(self):
        return self.__nombre
    
    def get_texto_SMS(self):
        return self.__texto_SMS
    
    def get_cantidad_mensajes(self):
        return self.__cantidad_mensajes
    
    def get_estado(self):
        return self.__estado
    
    def get_fecha_inicio(self):
        return self.__fecha_inicio
    
    def get_cliente_id(self):
        return self.__cliente_id

