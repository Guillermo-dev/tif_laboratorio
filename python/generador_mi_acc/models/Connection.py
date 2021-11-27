import sys
import mariadb
from . import configdb
#Crear archivo configdb.py con las siguientes constantes(cada una con los valores correspondientes a tu base de datos):
#USER='root'
#PASS=''
#DATABASE='laboratorio'
#HOST='localhost'
#PORT=3307

#Coneccion con la bd con patron de diseño Singleton
class Connection:
    __database = None
    
    @staticmethod
    def getConnection():
        if(Connection.__database == None):
            try:
                Connection.__database = mariadb.connect(
                    user=configdb.USER,
                    password=configdb.PASS,
                    host=configdb.HOST,
                    port=configdb.PORT,
                    database=configdb.DATABASE
                )
            except mariadb.Error as e:
                print(f"Error al conectar con la base de datos: {e}")
                sys.exit(1)
                
        return Connection.__database.cursor()
    
    def commit():
        Connection.__database.commit()
        Connection.__database.close()