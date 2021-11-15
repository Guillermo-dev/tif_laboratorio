import msaccessdb
import pyodbc
import mysql.connector

"Conexion con la base de datos de los numeros aleatorios"
"""conexion1 = mysql.connector.connect (host = "localhost", user = "root", passwd = "", database = "python")

cursor1 = conexion1.cursor()"""


"Creacion y conexion del archivo Microsoft Access"


msaccessdb.create(r'D:\Escritorio\archivo1.accdb')
conn_str = (
    r'DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};'
    r'DBQ=D:\Escritorio\archivo1.accdb;'
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
