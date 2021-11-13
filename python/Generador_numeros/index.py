#Codigo fuente del script para generar los numeros telefonicos y guardarlos en la bd del punto 3
#Falta terminar 
import random;
import itertools;

codigo_area=[3329,4482,9948];
repetir = 20;

for _ in itertools.repeat(None, repetir):
	nro = random.randrange(100000, 999999);
	#telefono = styr(codigo_area[random.randrange(1,3)]) + " " + str(nro);
	codigo= random.randrange(10,9999);
	print("-");
	print("codigo area: ", codigo);
	print ("telefono: ",nro);
