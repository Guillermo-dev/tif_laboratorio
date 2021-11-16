Trabajo final integrador laboratorio de lenguajes 2021 UNSAdA

Desarrollo dividido en dos partes, web (HTML, CSS, JS, PHP) y scripts de python

Para el desarrollo se utilizaron los gestores de dependencias npm (Para JavaScript), composer (para PHP) y pip (para python)

Antes de nada comprobar que se tienen instaladas estas herramientas:    node.js (para nmp)
                                                                        composer
                                                                        python
Una vez instaladas (instalacion de dependencias/librerias/frameworks usadas el el proyecto)
    En la apartado web (..\tif_laboratorio\web)
        Ejecutar los siguientes comandos en la terminal:
            npm install
            composer install
            composer dump-autoload
    
    En el apartado de python (..\tif_laboratorio\python)
        Ejecutar el siguiente comando en la terminal:
            pip install -r requirements


Para la coneccion con la base de datos se utilizan archivos de configuracion, para cada cazo (web y python) dirigirse a la clase Connection
Y crear los archivos mencionados 
Para python es necesario crear uno por cada script

