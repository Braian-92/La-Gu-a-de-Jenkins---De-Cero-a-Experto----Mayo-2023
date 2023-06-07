macloujulian@gmail.com mail de profesor del curso
https://www.osboxes.org/ubuntu/#ubuntu-22-10-vmware

la clave del sistema es la misma que el usuario

//! actualizar sistema = sudo apt-get update
clear = limpiar pantalla
sudo apt-get install vim = instalar editor vin
sudo apt install openssh-server = instalar openssh
sudo systemctl status ssh = verficar que ande bien (tiene que salir en verde activo)
ip a = verificar la ip de la pc
sudo apt-get install net-tools
ifconfig = para traer la IP innet para la conexion ssh (https://www.youtube.com/watch?v=YNKPem3aDPA)
(192.168.1.68 en mi caso de ip local)
ingresar por putty con el uo innet y la clave del sistema operativo
. En la sección "Host Name (or IP address)", introduce osboxes@<IP_UBUNTU> (reemplaza <IP_UBUNTU> con la dirección IP de tu máquina virtual Ubuntu).

instalar cmd windows = https://cmder.app/


instalar docker en ubuntu = https://docs.docker.com/engine/install/ubuntu/

paso 1
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg

paso 2
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

parte 3 (no se visualiza nada al ejecutarlo)
echo \
"deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
"$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt-get update

sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

systemctl start docker (luego pide la contraseña)

sudo usermod -aG docker osboxes (poner el usuario osboxes en este caso)

reiniciar linux
docker ps (para verificar buen funcionamiento)

#########
instalar docker composer
sudo apt install docker-compose

sudo chmod +x /usr/local/bin/docker-compose
(en mi caso no funciono ) [usar este metodo]
"sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose"
--------------
3er metodo funciono
$ sudo curl -o /usr/local/bin/docker-compose -L "https://github.com/docker/compose/releases/download/1.15.0/docker-compose-$(uname -s)-$(uname -m)"
$ sudo chmod +x /usr/local/bin/docker-compose
--------------

se reparo realizando este paso nuevamente  

sudo apt install docker-compose
y luego
sudo chmod 666 /var/run/docker.sock
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version


info ### CI/CD : integración continua y entrega continua (esto se va a realizar con jenkins)

construir = build
entregar = deliver (software)

##########################################
instalacion de jenkins

https://hub.docker.com/ (buscar jenkins/jenkins)
y nos dara este link para instalar en ssh 
docker pull jenkins/jenkins
docker images (para verificar que se encuentre la imagen de jenkins/jenkins)

crear carpetas para trabajar
mkdir -p jenkins/jenkins_home
ls (listar carpetas)
cd $HOME (sirve para volver al root)
chown 1000 jenkins (dar permisos a la carpeta)

cd jenkins
vi docker-compose.yml (crear un archivo en el editor)[para poder arancar hay que apretar la "i"]
se pega lo del archivo docker-compose.yml y se pone:
:wq! (para guardar en el editor)
ls (para verificar que existe el archivo)

cat docker-compose.yml (verificar el contenido del archivo)

EJECUTARLO

docker-compose up -d

tiro error en sintaxis del xml verifique el error con este sitio (https://www.yamllint.com/)

(dio un mensaje en verde con done (correcto))

docker ps (nos da un reporte del estatus del proceso)

para acceder al panel ya se puede ingresar desde la red con la IP http://192.168.1.68:8080/ (como si fuera un router wifi)

este sitio nos da un comando "/var/jenkins_home/secrets/initialAdminPassword" para inicializar la clave aunque hay que ejecutar este de abajo y no este...
1er metodo
cat jenkins_home/secrets/initialAdminPassword
2do metodo mencionado en la web (no funcional)
docker exec -ti jenkins bash

(bc8fc03a9d9140e7b3418cd3418a56cd) - clave

una vez ingresado te da 2 opciones la de la izquierda elegimos que son los paquetes por default e ira instalando todo (si falla poner reintentar)
http://192.168.1.68:8080/
setear usuarios contraseñas y mail posimos todo admin (mail admin@admin.com)

###########################

Configuración virtual box

quitar la red enlazada y poner una en modo NAT luego ir a configuración de red general y crear una con el DHCP deshabilitado y enlazarlo como segundo adaptador (modo anfition) de red a la pc virtual con linux y poner en avanzado el habilitado el modo promis

encender la maquina luego de realizar los cambios y revisar en ifconfig

y editar con el siguiente metodo
sudo vi /etc/network/interfaces (en la consola del ubuntu)
(si molesta el teclado ir a configuración como si fuese android)
el archivo puede estar vacio y hay que llenar con la información estatica del ip de la placa de red
-------------
auto enp0s8
iface enp0s8 innet static
	adrees 192.168.1.68 (este es el ip que designamos a la red anfitriona que fue asignada en la placa numero 2 despues del NAT [127.0.0.1])
	netmask 255.255.255.0
-------------
y guardar el archivo con escape y :wq! para darle OK
INFO ##### hay que buscar un adaptados que se encuentre sin ip en innet en nuestro caso es el "enp0s8" ###

instalar esta app para resetear el adaptador y luego echar un reboot para que los cambios se efectuen
sudo apt install ifupdown
sudo ifup enp0s8
(reboot reiniciar la pc desde comando)

-- 192.168.1.120 text manteniendo la misma red (funcional el ping pero no me da conexion al ssh)

#######################
NO FUNCIONO EL METODO DE PONER LA IP STATICA Y SE RESTABLECIO LA CONFIGURACIÓN BORRANDO EL CONTENIDO DEL ARCHIVO /etc/network/interfaces
DESPUES DE PONER EL RECEPTOR NUEVAMENTE EN MODO PUENTE Y ELIMINANDO AL EDAPTADOR SECUNDARIO
#######################


Jenkins Jobs = automatización de tareas

como se ejecuta
docker-compose start => iniciar
docker-compose stop => parar
docker-compose down => eliminar el servicio
docker-compose up -d => crar el docker compose nuevamente
docker-compose restar => reiniciar el servicio
docker ps => ver los servicios disponibles

####### Iniciar jenkins para acceder de manera remota desde http://192.168.1.68:8080/

cd jenkins
docker-compose start
ingresar a http://192.168.1.68:8080/

podemos usar el "docker-compose start" para iniciarlo como tambien "docker-compose up -d"

docker-compose log -f => verificar las acciones 

una vez logueado con admin-admin podremos ir a tareas y crear una nueva

docker exec -ti jenkins bash => ingresar al contenedor
- para probar que funcione enviar este comando
echo "Bienvenido al curso de Jenkins"

En este ejemplo vamos a relizar una tajea en jenkis conde ejecutar tenga la accion de powershel
donde en el editor que aparece podremos colocar el mismo codigo de hoy por consola, pero este sera ejecutado desde la tarea de manera automatica segun sea configurado

una vez guardado podemos ejecutarlo ingresando en el main al job y presionando el boton construir ahora que nos dan un #1 en la parte inferior izquierda mostando el detalle de la ejecución del comando

Nuevo comando shell
date +"%T" => 02:06:35

AHORA=$(date +"%r")
echo "La hora actual es $AHORA" > /temp/ahora (redirigir el output a la carpeta elegida)
##################
como nos salto un error de que la carpeta no existe la intentamos crear una carpeta de esta menera y no funciono
mkdir -p "$HOME/jenkins/temp/ahora"
mkdir -p "$HOME/jenkins/jenkins_home/temp/ahora"
##################
SOLUCION temp => tmp
AHORA=$(date +"%r")
echo "La hora actual es $AHORA" > /tmp/ahora

cat /tmp/ahora => nos permite visualizar el log guardado anteriormente

//! nuevo comando

#!/bin/bash
nombre="Braian"
#Empezar el loop
for a in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15
do
	#if es igual a 8 que el loop descanse
	if [ $a == 8 ]
	then
		sleep 15
		echo "A descansar de clase $nombre"
	fi
	echo "Clase N° $a"
done

/////////////////// consola
Started by user admin
Running as SYSTEM
Building in workspace /var/jenkins_home/workspace/Primer job del curso
[Primer job del curso] $ /bin/bash /tmp/jenkins5880683296735323634.sh
Clase N° 1
Clase N° 2
Clase N° 3
Clase N° 4
Clase N° 5
Clase N° 6
Clase N° 7
A descansar de clase Braian
Clase N° 8
Clase N° 9
Clase N° 10
Clase N° 11
Clase N° 12
Clase N° 13
Clase N° 14
Clase N° 15
Finished: SUCCESS
///////////////////

//! nuevo comando 02

#!/bin/bash
nombre="Braian"
curso="Jenkins"
#Empezar el loop
for a in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15
do
	#if es igual a 8 que el loop descanse
	if [ $a == 8 ]
	then
		sleep 5
		echo "A descansar de clase $nombre"
	fi
	echo "Clase N° $a"
done
sleep 5
echo "Bien $nombre, terminamos las clases de $curso, nos vemos"

###########################

CREAR comando desde la consola

//! tire un reboot para volver al inicio ya que encontraba dentro de docker
cd jenkins => vamos a la carpeta jenkins
vi jobscript.sh => creamos el archivo 
cat jobscript.sh => visualizamos el contenido
./jobscript.sh => para ejecutar
(como no nos dejo ejecutar por falta de permisos tiramos el siguiente comando para verificar a que es accesible el archivo)
ls -l jobscript.sh

//! -rw-rw-r-- 1 osboxes osboxes 320 Jun  4 23:20 jobscript.sh

######### EJEMPLO DE SIGLAS
En el ejemplo anterior, "x" da permisos de ejecución, "w" es para escritura y "r" da permiso de lectura.

en neustro caso aparecio solamente -rw-rw-r-- que seria lecto escritura pero no es visible la ejecución "x"

sudo chmod +x jobscript.sh => agregar permisos de ejecución al archivo
y luevo volver a intentar ./jobscript.sh (funciono)

//! pasar el archivo al contenedor

como cerramos todo vamos a tener que volver a prender docker}
si ponermos "docker ps" podemos ver que no hay nada activo
entonces pasamos a prenderlo con el siguiente comando "docker-compose start"

docker cp jobscript.sh jenkins:/opt => copiar el script al contenedor
docker exec -ti jenkins bash => para ingresar al contenedor
ls => listar directorios
cd opt
./jobscript.sh => ejecutarlo dentro del contenedor

//! ejecutar comando guardado desde la interfaz web de jenkins (ya que se encuentra dentro del contenedor)

## esta vez en ejecutar ponemos

/opt/jobscript.sh

exit => salir del contenedor de docker

############## ingreso de variables desde jenkins [nombre="Braian" curso="Jenkins"]

vi jobscript.sh y eliminamos las variables
i para editar escape para salir :wq! para guardar

######## el comando quedara de esta manera ######
#!/bin/bash
#Empezar el loop
for a in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15
do
	#if es igual a 8 que el loop descanse
	if [ $a == 8 ]
	then
		sleep 5
		echo "A descansar de clase $nombre"
	fi
	echo "Clase N° $a"
done
sleep 5
echo "Bien $nombre, terminamos las clases de $curso, nos vemos"
############################################################

 docker cp jobscript.sh jenkins:/opt => copiar el archivo el contenedor
 // consola ---  Successfully copied 2.05kB to jenkins:/opt

 docker exec -ti jenkins bash => para ingresar al contenedor nuevamente
 cd opt
 ./jobscript.sh
 /////////////////////////// no se visualizan las variables
 Clase N° 1
 Clase N° 2
 Clase N° 3
 Clase N° 4
 Clase N° 5
 Clase N° 6
 Clase N° 7
 A descansar de clase
 Clase N° 8
 Clase N° 9
 Clase N° 10
 Clase N° 11
 Clase N° 12
 Clase N° 13
 Clase N° 14
 Clase N° 15
 Bien , terminamos las clases de , nos vemos
 ///////////////////////////

 para agregar las variables ir a la tarea de la interfaz de jenkins y agregarlas en la parte superior del codigo
##########
nombre="Braian"
curso="Jenkins"
/opt/jobscript.sh
##########

de esta menera no toma las variables aunque intentando de esta manera manualmente por consola funcional

cd opt
export nombre="Braian"
export curso="Jenkins"
./jobscript.sh

##########################
metodo de ingreso de variables por parametros probenientes de jenkins

////
#!/bin/bash
nombre=$1
curso=$2
#Empezar el loop
for a in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15
do
	#if es igual a 8 que el loop descanse
	if [ $a == 8 ]
	then
		sleep 5
		echo "A descansar de clase $nombre"
	fi
	echo "Clase N° $a"
done
sleep 5
echo "Bien $nombre, terminamos las clases de $curso, nos vemos"
///
nuevamente moverlo al contenedor despues de guardarlo

########## ahora desde el editor de interfaz de jenkis cambiar el comando por parametros a

##########
nombre="Braian"
curso="Jenkins"
/opt/jobscript.sh $nombre $curso
##########

///////////////// Salida (ahora podemos ver que las variables ingresar de manera externa en jenkins)
Clase N° 1
Clase N° 2
Clase N° 3
Clase N° 4
Clase N° 5
Clase N° 6
Clase N° 7
A descansar de clase Braian
Clase N° 8
Clase N° 9
Clase N° 10
Clase N° 11
Clase N° 12
Clase N° 13
Clase N° 14
Clase N° 15
Bien Braian, terminamos las clases de Jenkins, nos vemos
/////////////////

Otro metodo para ingresar las variables de manera externa

##########
########## (pero en el archivo ya tendriamos que borrar las inicializaciones de las variables)
export nombre="Braian"
export curso="Jenkins"
/opt/jobscript.sh
##########


######################## JOBS PARAMETRIZADOS #################

RESUMEN: son como funciones necesitan parametros para incializarse

En editar aparece una opcion que dice:
Esta ejecución debe parametrizarse -> Parámetro de cadena
este permite ingresos multiple (como si fueran las variables multiples que se envian por postman)

y en ejecutar colocamos el siguiente comando "/opt/jobscript.sh" ya que los parametros vienen configurandos en el job

en este caso ya no vamos a nesesitas estos metodos
##### Metodo 01
nombre="Braian"
curso="Jenkins"
##### Metodo 02
nombre=$1
curso=$2
############## 
sino que tenemos que eliminar directamente las variables del script, y ahora cuando ejecutemos el script nos va a pedir los parametros

##############
configurar un mail para notificaciónes en jenkins

Servidor de correo saliente (SMTP) => smtp.gmail.com
contraseña de el mail => poner la clave generado en 2 pasos de la seguridad de gmail
Puerto de SMTP => 465

Guardando conf
agregar credencial donde usuario es nuestro gmail y la contraseña es la generada para apps de 2 pasos y darle add
en donde dice credentials seleccionar la creada

use ssl = true

#Default Triggers
* failure
* before build
* success

para aplicar mensajes de log de errores ir a las tareas generadas y aplicar en acciones enviar mail y habilitar que se envie cuando encuentre errores

#######################

integración de github con maven

ya que el paquete basico viene con el plugin de github instalaremos el maven integration 
una vez que finalice la instalación tendremos que reiniciar el docker "docker-compore stop y luego start"

nuevamente abrir el jenkins desde el navegador y agregar un instalador de maven con el nombre mavenjenkins y poner guardar

vamos a crear una nueva tarea de estilo libre
y en configuración de codigo fuente eligiremos githubn y pegaremos el siguiente repositorio de prueba

https://github.com/macloujulian/simple-java-maven-app.git

cuando ejecutemos la tarea nos dara el directorio donde se creo el repositorio
"/var/jenkins_home/workspace/Java app con maven"
 para visualizar el contenido del repo ingresaremos el siguiente codigo

docker exec -ti jenkins bash
cd "/var/jenkins_home/workspace/Java app con maven"
ls 

//////////// SALIDA /////////
DSL         Dockerfile2   Jenkinsfile3  README.md  pom.xml
Dockerfile  Jenkinsfile1  Jenkinsfile4  jenkins    src
/////////////////////////////


############# BUILD DE LA APP ##############

editar la tarea creada y en Build Steps seleccionar  => Ejecutar tareas 'maven' de nivel superior
Version de Maven => mavenjenkins
Goles => -B -DskipTests clean package

###### DETALLE ########
-DskipTests omita las pruebas 
clean package genera la aplicación java
######################

en ejecutar despues de finalizar enviar un mail con la integración de smtp de gmail

al ejecutar la tarea nos sale todo el proceso y el destino del archivo jar generado

Building jar: "/var/jenkins_home/workspace/Java app con maven/target/my-app-1.0-SNAPSHOT.jar"

para verificar el directorio primero ingresar al espacio de jenkins

docker exec -ti jenkins bash

//! y luego entrar al directorio del archivo generado
cd "/var/jenkins_home/workspace/Java app con maven/target/"
ls

//////// SALIDA ///////////////
classes                 maven-archiver           test-classes
generated-sources       maven-status
generated-test-sources  my-app-1.0-SNAPSHOT.jar
//////////////

############## TEST ############

ir a la tarea nuevamenet editarla y agregar en ejecutar una tarea de maven con:

Version de Maven => mavenjenkins
Goles => test

// CONSOLA //
[INFO] Running com.mycompany.app.AppTest
[INFO] Tests run: 2, Failures: 0, Errors: 0, Skipped: 0, Time elapsed: 0.056 s
/////////////

para ingresar el en reporte del testeo ingresar a este directorio despues de prender y entrar en docker de jenkins
cd "/var/jenkins_home/workspace/Java app con maven/target/surefire-reports/"

para visualizar los reportes xml de test editar la tarea y en acciones despues del mail agregar

Publicar los resultados de tests JUnit
Ficheros XML con los informes de tests => target/surefire-reports/*.xml

y ahora en la tarea se puede visualizar el nuevo reporte "Últimos resultados de tests" como el grafico a la derecha


########## EJECUTAR LA APLICACIÓN JAR ########
editamos la tarea y agregamos la accion de powershel con el siguiente comando

echo "Entrega: Desplegando la aplicación"
java -jar "/var/jenkins_home/workspace/Java app con maven/target/my-app-1.0-SNAPSHOT.jar"

// CONSOLA //
Hello World!
/////////////

con esto finalizamos un ciclo sensillo de CI/CD (integración continua y entrega continua)


##### Para finalizar vamos a guardar los archivos correctos .jar #############

editar la tarea y agregar una nueva sentencia en acciones de tipo "guardar archivos generados"

Ficheros para guardar => target/*.jar

y en avanzado checkeamos el "Archive artifacts only if build is successful" para que solo guarde las compilaciones correctas

(como nos olvidamos configurar el directorio de fichero para guardar nos envio el detalle del error en el mail y luego cuando lo reparamos nos envio este mail)

MAIL #####
Jenkins build is back to normal : Java app con maven #10
########