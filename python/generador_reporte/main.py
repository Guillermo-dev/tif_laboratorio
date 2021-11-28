##Codigo fuente del script para generar y enviar por email la tabla de reporte del punto 5
from models.Campania import Campania
from models.Cliente import Cliente
from models.Localidad import Localidad
from config import config
#Excel
from campaniaToExcel import campaniaToExcel
#email
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders

def numeroInvalido(numero):
    try:
        numero = int(numero)
        return False
    except ValueError:
        return True
        
try:
    campanias = Campania.getCampanias()
    i = 1
    for campania in campanias:
        print(i,') Nombre:', campania[1],', Fecha inicio:',campania[5])
        i += 1
    res = input('Seleccione la campaña que quiere enviar el reporte:')
    while(numeroInvalido(res)):
        res = input('Seleccione la campaña que quiere enviar el reporte:')
    res = int(res)-1
    
    campania = Campania(campanias[res][0],campanias[res][1],campanias[res][2],campanias[res][3],campanias[res][4],campanias[res][5],campanias[res][6])
    
    clienteData = Cliente.getClienteByCampania(str(campania.get_campania_id()))
    cliente = Cliente(clienteData[0][0],clienteData[0][1],clienteData[0][2],clienteData[0][3],clienteData[0][4],clienteData[0][5],clienteData[0][6])

    localidadesData = Localidad.getLocalidadByCampania(str(campania.get_campania_id()))
    localidades = []
    for localidadData in localidadesData:
        localidad = Localidad(localidadData[0],localidadData[1],localidadData[2],localidadData[3])
        localidades.append(localidad)
         
except Exception as e:
    print ("Error interno:", e)

try:
    m2e = campaniaToExcel('Reporte de Campaña')
    
    m2e.agregarPlanilla(campania, cliente, localidades)
    
    m2e.guardarPlanilla("reporte_campania.xls")
    
except Exception as e:
    print ("Error al crear el reporte:", e)


#ENVIO DE EMAIL
try:
    emisor = "DannaFox.cam@gmail.com"
    receptor = cliente.get_email()

    msg = MIMEMultipart()

    msg['From'] = emisor
    msg['To'] = receptor

    #Asunto
    msg['Subject'] = "Planilla de reporte"

    # Cuerpo
    cuerpo = "Resumen de la campaña publicitaria con nombre:"+campania.get_nombre()

    msg.attach(MIMEText(cuerpo, 'plain'))

    # Agregar excel al email
    filename = "reporte_campania.xls"
    ############################################## CREAR CARPETA config y dentro el archivo config.py que dentro tendra una constante del estilo a  PATH = "C:/Users/guill/OneDrive/Escritorio/Laboratorio de lenguaje/trabajofinal/reporte_campania.xls"
    attachment = open(config.PATH, "rb")

    # instance of MIMEBase and named as p
    p = MIMEBase('application', 'octet-stream')
    p.set_payload((attachment).read())
    encoders.encode_base64(p)
    p.add_header('Content-Disposition', "attachment; filename= %s" % filename)
    
    msg.attach(p)

    # Crear SMTP session
    session = smtplib.SMTP('smtp.gmail.com', 587)
    session.starttls()

    # Login de emisor
    session.login(emisor, "Dana12345")

    # Convertir mensaje a string
    text = msg.as_string()

    # Envio de email
    session.sendmail(emisor, receptor, text)
    session.quit()
    
    print("EMAIL ENVIADO CON EXITO")
except Exception as e:
    print ("Error al enviar el email:", e)