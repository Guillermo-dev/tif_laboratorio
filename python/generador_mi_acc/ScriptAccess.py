import msaccessdb
import pyodbc
from models.Connection import Connection


"Creacion y conexion del archivo Microsoft Access"

'cantCampanias=Connection.__database.cursor("SELECT COUNT(*)FROM campanias")'
i=0
while (i<10 and 'i<cantCampanias'):
    msaccessdb.create(r'D:\Escritorio\archivo'+ str(i) +'.accdb')
    conn_str = (
        r'DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};'
        r'DBQ=D:\Escritorio\archivo'+str(i)+'.accdb;'
        )
    cnxn = pyodbc.connect(conn_str)
    crsr = cnxn.cursor()


    "Creacion de la tabla numeros"


    crsr.execute("CREATE TABLE numeros (caracteristica VARCHAR(8),numero VARCHAR(20))")


    "Insercion de numeros en la tabla"


    crsr.execute("INSERT INTO numeros (caracteristica, numero) VALUES (2325,680723)")


    "Commit y cerrar la conexion"
    crsr.commit()
    crsr.close()
    cnxn.close()

    i=i+1
    

