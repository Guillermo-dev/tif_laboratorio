class Localidad(object):
    __localidad_id = 0
    __pais = ''
    __provincia = ''
    __ciudad = ''

    def __init__(self, localidad_id, pais, provincia, ciudad):
        self.__localidad_id = localidad_id
        self.__pais = pais
        self.__provincia = provincia
        self.__ciudad = ciudad

    def get_localidad_id(self):
        return self.__localidad_id

    def get_pais(self):
        return self.__pais

    def get_provincia(self):
        return self.__provincia

    def get_ciudad(self):
        return self.__ciudad
    
