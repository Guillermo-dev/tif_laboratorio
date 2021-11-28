class Cliente(object):
    __cliente_id = 0
    __cuil_cuit = ''
    __razon_social = ''
    __nombre = ''
    __apellido = ''
    __telefono = ''
    __email = ''

    def __init__(self, cliente_id, cuil_cuit, razon_social, nombre, apellido, telefono, email):
        self.cliente_id = cliente_id
        self.__cuil_cuit = cuil_cuit
        self.__razon_social = razon_social
        self.__nombre = nombre
        self.__apellido = apellido
        self.__telefono = telefono
        self.__email = email
        
    def get_cliente_id(self):
        return self.__cliente_id
    
    def get_cuil_cuit(self):
        return self.__cuil_cuit
    
    def get_razon_social(self):
        return self.__razon_social

    def get_nombre(self):
        return self.__nombre

    def get_apellido(self):
        return self.__apellido

    def get__telefono(self):
        return self.__telefono

    def get_email(self):
        return self.__email
