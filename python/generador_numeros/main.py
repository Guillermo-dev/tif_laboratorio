#Codigo fuente del script para generar los numeros telefonicos y guardarlos en la bd del punto 3.
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
cantidad_localidades = split('/D+',cantidad_localidades)
cantidad_localidades = cantidad_localidades[0]
cantidad_localidades = ''.join(x for x in cantidad_localidades if x not in caracteres)


repetir = 5
for _ in itertools.repeat(None, int(repetir)):
	nro = random.randrange(100000, 999999)
	codigo= random.randrange(1,int(cantidad_localidades))
	localidad = codigo
	cur.execute("INSERT INTO numeros(numero,localidad_id,prefijo_internacional_id,codigo_area_id) values (?, ?, ?, ?)", (str(nro),str(localidad),1,str(codigo)))
Connection.database.commit()
Connection.database.close()