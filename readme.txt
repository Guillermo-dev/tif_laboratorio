Trabajo final integrador laboratori de lenguajes 2021 UNSAdA

Desarrollo dividido en dos partes, por un lado desarrollo web y por otro scripts de python

Para el desarrollo se utilizaron los gestores de dependencias npm (Para JavaScript), composer (para PHP) y pip (para python)

Antes de nada comprobar que se tienen instaladas estas herramientas:    node.js (para nmp)
                                                                        composer
                                                                        python
Una vez esto
    En la apartado web (..\tif_laboratorio\web)
        Ejecutar los siguientes comandos en la terminal:
            npm install
            composer install
            composer dump-autoload
    
    Luego, en el apartado de python (..\tif_laboratorio\python)
        Ejecutar el siguiente comando en la terminal:
            pip install -r requirements

Para la coneccion con la base de datos se utilizan archivos de configuracion, paracada cazo (web y python) dirigirce a la clase Connection
Y crear los archivos mensionados 