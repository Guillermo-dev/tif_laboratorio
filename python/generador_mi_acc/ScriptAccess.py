import msaccessdb
import pyodbc
from models.Connection import Connection
import random


"Creacion y conexion del archivo Microsoft Access"

cursor = Connection.getConnection()

cursor.execute("SELECT COUNT(*)FROM campanias WHERE estado = 'ejecucion'")
cantCampanias = cursor.fetchall()

cursor.execute("SELECT C.campania_id,cantidad_mensajes FROM campanias C  WHERE estado = 'ejecucion'")
"Esto me trae un array de todas las campañas en ejecucion"


cantArchivos=0

campanias = cursor.fetchall()#arreglo de campanias


cantidadCampanias=int(''.join(map(str, cantCampanias[0])))#me convierte la consulta de count en un entero



while (cantArchivos<cantidadCampanias):
    
    campania = campanias[cantArchivos] #campania que se esta procesando

    numerosPorCampania = []

    cantMensajes = 0

    while(cantMensajes != int(campania[1])):
        msaccessdb.create(r'D:\Escritorio\ArchivosAccess\archivo'+ str(cantArchivos) +'.accdb')
        conn_str = (
            r'DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};'
            r'DBQ=D:\Escritorio\ArchivosAccess\archivo'+str(cantArchivos)+'.accdb;'
            )
        cnxn = pyodbc.connect(conn_str)
        crsr = cnxn.cursor()


        cursor.execute("SELECT localidad_id FROM campanias_localidades WHERE campania_id="+ str(campania[0]))#trae las localidades de la campaña que se esta procesando

        

        localidades = cursor.fetchall()
        
        
        numeros = []#Va a tener los arreglos de numeros por localidad(dependiendo de las localidades, es la cantidad de arreglos que hay)
        
        for localidad_id in localidades:
            
            cursor.execute("SELECT P.prefijo,C.codigo,N.numero FROM numeros N INNER JOIN prefijos_internacionales P ON N.prefijo_internacional_id=P.prefijo_internacional_id INNER JOIN codigos_area C ON N.codigo_area_id=C.codigo_area_id WHERE N.localidad_id="+''.join(map(str, localidad_id)))
            numeros.append(cursor.fetchall())#se guarda una lista de tuplas que contienen numero codigo y prefijo de las localidades

        
        crsr.execute("CREATE TABLE numeros (prefijo VARCHAR (10),codigo VARCHAR (10), numero VARCHAR(15))")
        for i in range (7):
        
            iLocalidad = random.randrange(0, len(numeros)-1)#genera numeros aleatorios entre los distintos id de las localidades
            iNumero = random.randrange(0, len(numeros[iLocalidad])-1)

           
            while(numeros[iLocalidad][iNumero] in numerosPorCampania):#Controla que no sea numero repetido. Si esta repetido, genera nuevamente 2 indices random, y sino lo agrega al arreglo
                iLocalidad = random.randrange(0, len(numeros))
                iNumero = random.randrange(0, len(numeros[iLocalidad]))
            numerosPorCampania.append(numeros[iLocalidad][iNumero])

            "Creacion de la tabla numeros"

            prefijo = ''.join(map(str, numeros[iLocalidad][iNumero][0]))
            codigo = ''.join(map(str, numeros[iLocalidad][iNumero][1]))
            numero = ''.join(map(str, numeros[iLocalidad][iNumero][2]))
            crsr.execute("INSERT INTO numeros (prefijo, codigo, numero) VALUES ("+prefijo+","+codigo+","+numero+")")
        "Commit y cerrar la conexion"
        
        crsr.commit()
        crsr.close()
        cnxn.close()

        
        cantMensajes = cantMensajes + 7000


    cantArchivos = cantArchivos + 1    

    

    


   


    "Insercion de numeros en la tabla"
    "traer las campañas que tengan el estado en ejecucion (select), inner join campañas localidades"
    "luego traer por cada campaña los numeros telefonicos de las localidades asociadas (select de numeros)"

    


    
    
    
    

