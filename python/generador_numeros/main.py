#Codigo fuente del script para generar los numeros telefonicos y guardarlos en la bd del punto 3.
import random
import itertools
from models.Connection import Connection
from re import split

cur = Connection.getConnection()
caracteres = "['(,)']"
cur.execute("SELECT localidad_id FROM localidades;")
localidades = cur.fetchall()


repetir = 75
for _ in itertools.repeat(None, int(repetir)):
	#Genero un numero random
	nro = random.randrange(100000, 999999)
	# -----------------------------------------------------------------------------------
	# - Las lineas 22-27 son para limpiar los caracteres dejando solo numeros.			-
	# - Se usa para elegir de manera aleatoria una localidad							-
	# -----------------------------------------------------------------------------------
	#Tomo una localidad aleatoria de la lista
	codigo = str(localidades[random.randrange(0, int(len(localidades)))]) 
	#Extraigo los numeros del string (https://www.youtube.com/watch?v=K_KRa-3ZylQ)
	codigo = split('/D+',codigo) 
	codigo = codigo[0]
	#Elimino los caracteres indicados en la variable caracteres(https://www.delftstack.com/es/howto/python/remove-certain-characters-from-string-python/)
	codigo = ''.join(x for x in codigo if x not in caracteres)
	#El id del codigo de area y localidad coinciden
	localidad = codigo 
	cur.execute("INSERT INTO numeros (numero, localidad_id, prefijo_internacional_id, codigo_area_id) VALUES (?, ?, ?, ?)", (str(nro), localidad, 1, codigo))
Connection.commit()
