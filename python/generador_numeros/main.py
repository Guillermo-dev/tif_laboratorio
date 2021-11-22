#Codigo fuente del script para generar los numeros telefonicos y guardarlos en la bd del punto 3
#Falta terminar
import random;
import itertools;
from models.Connection import Connection;

cur = Connection.getConnection();
cur.execute("SELECT MAX(codigo_area_id) FROM codigos_area;");


repetir = 20;

for _ in itertools.repeat(None, repetir):
	nro = random.randrange(100000, 999999);
	codigo= random.randrange(1,cur);
	localidad = codigo;
	query = str("INSERT INTO numeros (numero,localidad_id,prefijo_internacional_id,codigo_area_id) values ("+str(nro)+","+str(localidad)+",1,"+str(codigo)+");")
	print (query)
	cur.execute(query);
