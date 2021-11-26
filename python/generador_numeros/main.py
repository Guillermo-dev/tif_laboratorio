#Codigo fuente del script para generar los numeros telefonicos y guardarlos en la bd del punto 3.
#Para hacer los insert es necesario ejecutar "SET GLOBAL FOREIGN_KEY_CHECKS=0;" en la base de datos
#https://stackoverflow.com/questions/21659691/error-1452-cannot-add-or-update-a-child-row-a-foreign-key-constraint-fails
import random
import itertools
from models.Connection import Connection
from re import split

cur = Connection.getConnection()
cur.execute("SELECT MAX(localidad_id) FROM localidades;")
caracteres = "['(,)']"

for (localidad_id) in cur:
	cantidad_localidades = str(localidad_id)
#Las lineas 15 a 17 son para limpiar los caracteres dejando solo numeros.
#Se usa para elegir de manera aleatoria una localidad al insertar un numero.
#Extraigo los numeros del string (https://www.youtube.com/watch?v=K_KRa-3ZylQ)
cantidad_localidades = split('/D+',cantidad_localidades)
cantidad_localidades = cantidad_localidades[0]
#Elimino los caracteres indicados en la variable caracteres(https://www.delftstack.com/es/howto/python/remove-certain-characters-from-string-python/)
cantidad_localidades = ''.join(x for x in cantidad_localidades if x not in caracteres)


repetir = 5
for _ in itertools.repeat(None, int(repetir)):
	nro = random.randrange(100000, 999999)
	codigo= random.randrange(1,int(cantidad_localidades))
	localidad = codigo
	cur.execute("INSERT INTO numeros(numero,localidad_id,prefijo_internacional_id,codigo_area_id) values (?, ?, ?, ?)", (str(nro),str(localidad),1,str(codigo)))
Connection.database.commit()
Connection.database.close()