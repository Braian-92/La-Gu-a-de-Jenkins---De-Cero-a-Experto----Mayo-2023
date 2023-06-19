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


###################################################################

Que son los dockerfiles => son archivos con configuración de formato imagen que permite inicializar un ambiente configurado con las dependencias necesarias

#############################################

Instalación de docket en el contenedor de jenkins
cd jenkins 
git clone https://github.com/macloujulian/dockerjenkins.git (contiene un dockerfile)
cd dockerjenkins
cat Dockerfile (visualizar contenido del archivo "Dockerfile")
cd .. (retroceder)
vi docker-compose.yml

////////// CONTENIDO ACTUAL /////////////////
version: '3'
services:
  jenkins:
    image: jenkins/jenkins
    ports:
      - 8080:8080
      - 50000:50000
    container_name: jenkins
    volumes:
      - $PWD/jenkins_home:/var/jenkins_home
    networks:
      - net
networks:
  net:
////////// CONTENIDO NUEVO /////////////////
version: '3'
services:
  jenkins:
    image: jenkins/docker
    build:
      context: dockerjenkins
    ports:
      - 8080:8080
      - 50000:50000
    container_name: jenkins
    volumes:
      - $PWD/jenkins_home:/var/jenkins_home
      - /var/run/docker.sock:/var/run/docker.sock (permite ingresar por fuera del contenedor)
    networks:
      - net
networks:
  net:
//////////////////////////////////////////////////
apretar esc y :wq! para guardar
cat docker-compose.yml (visualizar el contenido)
docker-compose stop
docker-compose build (compila el nuevo yml)
docker images | grep docker (para visualizar la imagen de docker)
// SALIDA //
jenkins/docker    latest    cc6935239530   About a minute ago   1.05GB
//////////

docker-compose up -d (para recrear el contenedor de jenkins)
docker ps (verifica recreación )
docker exec -ti jenkins bash (ingresar al contenedor)
docker ps (nos saldra permiso denegado)
exit (salimos del contenedor)
docker exec -ti --user root jenkins bash (ingresar con permisos root)
chown jenkins /var/run/docker.sock (asignar permisos de ejecución)
exit
docker exec -ti jenkins bash
docker ps (ya podemos ver los contenedores de docker en el contenedor de jenkins, podemos ejecutar cualquier comando de docker desde el contenedor jenkins) (resumen instalamos docker en docker)


################ NodeJS ####################

instalar el plugin de nodejs en jenkins

crear una tarea llamada Aplicación nodeJS

y que el origen sea de GIT
con el siguiente link https://github.com/macloujulian/nodejsapp.git

y en ejecutar (Build Steps) colocamos un nuevo comando de shell con el siguiente comando
npm install
y habilitamos en la parte superior "Provide Node & npm bin/ folder to PATH" 

estando en el root de la consola ingresamos el siguiente comando

docker exec -ti jenkins bash
cd /var/jenkins_home/workspace
ls

y veremos que aparece "Aplicación nodeJS"

cd "Aplicación nodeJS"
ls
cd node_modules
ls

y visualizaremos todas las dependencias

ahora instalaremos el plugin "CloudBees Docker Build and Publish plugin"

y reabriendo la tarea ingresaremos una nueva ejecucuión (build steps) de "Docker Build and Publish" 

primero antes de llenar los campos ir al sitio
https://hub.docker.com/
y crear un nuevo repositorio

nombre => nodejsapp
desc => App nodejs hello word para jebnkins

una vez creado utilizar el directorio que nos da para colocarlo en el repositorio name de build and publish
Repository Name => braianzamudio/nodejsapp
Tag => App1
y en Registry credentials agregar las de docker hub

en nuestro caso para que tome las credenciales hay que reiniciar el servidor completamente
finalmente guardar

######### loguear docker hub en consola para que pueda vincular jenkins con nuestro repositorio de docker hub #####

docker login (inicio) y nos pedira usuario y contraseña

al final el ingreso se realizaba por un tocken generado en el sitio
https://forums.docker.com/t/error-response-from-daemon-get-https-registry-1-docker-io-v2-unauthorized-incorrect-username-or-password/130981/2

docker login -u tockenUser
ti9ohjasojdas tockern

no compila 
infica que para bajar la imagen seria
docker pull braianzamudio/nodejsapp

para crerlo
docker run -p 3000:3000 -d --name nodejsapp braianzamudio/nodejsapp

y ejecutarlo 
curl localhost:3000
// salida si funcionara : hello world! Gola Mundo!

y en el navegador seria http://192.168.1.42:3000


########## seguridad jenkins ###########

Jenkins desde configuración permite habilitar el creado de cuentas ademas se puede realizar desde consola

en este caso vamos a utilizar el siguiente plugin "Role-based Authorization Strategy"

para activarlo : "Panel de Control => Administrar Jenkins => Security"

Autorización => Role based Strategy

y ahora veremos la siguiente categoria en seguridad => "Manage and Assign Roles"
y en este podremos configurar grupos para configuraciones generales para usuarios

########## infraestructura como código #############

control de versión CSM

Introducción a DSL (domain specific lenguage)

instalar el plugin Job DSl

primera etapa crear los "seed jobs"

creamos una nueva tareas DSL Job

y vamos directamente a ejecutar y seleccionamos "Process Job DSLs"
////// DOCU: https://jenkinsci.github.io/job-dsl-plugin/#


check => Use the provided DSL script
y colocamos el siguiente comando

job('ejemplo-job-DSL') {

}

guardarlo y activarlo en configuraciones en la sección "In-process Script Approval" docu (https://www.youtube.com/watch?v=tCqWYgZmJtg)


job('ejemplo-job-DSL') {
	description('Job DSL de ejemplo para curso de jenkins')
}

############ Source Control Management SCM ##########


job('ejemplo-job-DSL') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
}

------------

job('ejemplo-job-DSL') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
  parameters {
  	stringParam('nombre', defaultValue = 'Braian', description = 'parametro de cadena para el job booleano')
  	choiceParam('planeta', ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Jupiter', 'Saturno', 'Urano', 'Neptuno'])
  	booleanParam('Agente', false)
  }
}

########## disparadores de ejecución CRON #########

job('ejemplo-job-DSL') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
  parameters {
  	stringParam('nombre', defaultValue = 'Braian', description = 'parametro de cadena para el job booleano')
  	choiceParam('planeta', ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Jupiter', 'Saturno', 'Urano', 'Neptuno'])
  	booleanParam('Agente', false)
  }
  triggers {
  	cron('H/7 * * * *')
  }
}

########### STEPS (EJECUTAR) ########

job('ejemplo-job-DSL') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
  parameters {
  	stringParam('nombre', defaultValue = 'Braian', description = 'parametro de cadena para el job booleano')
  	choiceParam('planeta', ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Jupiter', 'Saturno', 'Urano', 'Neptuno'])
  	booleanParam('Agente', false)
  }
  triggers {
  	cron('H/7 * * * *')
  }
  steps {
  	shell("bash jobscript.sh")
  }
}

########## PUBLISHERS ###########
RECORDAR QUE TODA LA DOCUMENTACIÓN SE ENCUENTRA EN.... ("https://jenkinsci.github.io/job-dsl-plugin/#method/javaposse.jobdsl.dsl.helpers.ScmContext.git")
RECORDAR QUE TODA LA DOCUMENTACIÓN SE ENCUENTRA EN.... ("https://jenkinsci.github.io/job-dsl-plugin/#method/javaposse.jobdsl.dsl.helpers.ScmContext.git")
RECORDAR QUE TODA LA DOCUMENTACIÓN SE ENCUENTRA EN.... ("https://jenkinsci.github.io/job-dsl-plugin/#method/javaposse.jobdsl.dsl.helpers.ScmContext.git")
RECORDAR QUE TODA LA DOCUMENTACIÓN SE ENCUENTRA EN.... ("https://jenkinsci.github.io/job-dsl-plugin/#method/javaposse.jobdsl.dsl.helpers.ScmContext.git")

ejemplo --> activar Artifacts en Maven

ejemplo --> activar mailer

job('ejemplo-job-DSL') {
  description('Job DSL de ejemplo para el curso de Jenkins')
  scm {
    git('https://github.com/macloujulian/jenkins.job.parametrizado.git', 'main') { node ->
      node / gitConfigName('macloujulian')
      node / gitConfigEmail('macloujulian@gmail.com')
    }
  }
  parameters {
  	stringParam('nombre', defaultValue = 'Braian', description = 'parametro de cadena para el job booleano')
  	choiceParam('planeta', ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Jupiter', 'Saturno', 'Urano', 'Neptuno'])
  	booleanParam('Agente', false)
  }
  triggers {
  	cron('H/7 * * * *')
  }
  steps {
  	shell("bash jobscript.sh")
  }
  publishers {
  	mailer('zamudiobraianhernan@gmail.com', true, true)
  }
}


######## QUITAR RESTRICCION POR COMANDOS DSL ############
Aclaración: esto no venia en el curso pero aceleraria las practicas sin tener que andar validando los script en la configuración de seguridad
https://issues.jenkins.io/browse/JENKINS-32681?page=com.atlassian.jira.plugin.system.issuetabpanels%3Acomment-tabpanel&showAll=true
https://plugins.jenkins.io/permissive-script-security/
instalar plugin => permissive-script-security.
########################################################


####### Parametrizado en repo propio #######
crear una nueva tarea y en origen de fuente colocamos el siguiente repositorio que contien el .groovy
https://github.com/Braian-92/La-Gu-a-de-Jenkins---De-Cero-a-Experto----Mayo-2023.git

Branches to build
--> Branch Specifier (blank for 'any') => */main (colocar rama)

Process Job DSLs => Look on Filesystem => ./github01/parametrizadoDSL.groovy

######### Trigger automatico con github #############

es necesario generar un webhook y tener una ip accesible desde internet
en github se da el enlace al ip del jenkins que hace resonar el trigger

############# Ejemplo compilar app java con groovy ############

job('Java Maven App DSL 2') {
    description('Java Maven App con DSL para el curso de Jenkins')
    scm {
        git('https://github.com/macloujulian/simple-java-maven-app.git', 'master') { node ->
            node / gitConfigName('macloujulian')
            node / gitConfigEmail('macloujulian@gmail.com')
        }
    }
    steps {
        maven {
          mavenInstallation('mavenjenkins')
          goals('-B -DskipTests clean package')
        }
        maven {
          mavenInstallation('mavenjenkins')
          goals('test')
        }
        shell('''
          echo "Entrega: Desplegando la aplicación" 
          java -jar "/var/jenkins_home/workspace/Java Maven App DSL 2/target/my-app-1.0-SNAPSHOT.jar"
        ''')  
    }
    publishers {
        archiveArtifacts('target/*.jar')
        archiveJunit('target/surefire-reports/*.xml')
		slackNotifier { -- en nuestro caso no usamos slark sino que usamos el simple mail
        notifyAborted(true)
        notifyEveryFailure(true)
        notifyNotBuilt(false)
        notifyUnstable(false)
        notifyBackToNormal(true)
        notifySuccess(true)
        notifyRepeatedFailure(false)
        startNotification(false)
        includeTestSummary(false)
        includeCustomMessage(false)
        customMessage(null)
        sendAs(null)
        commitInfoChoice('NONE')
        teamDomain(null)
        authToken(null)
      }
    }
}

#### Quitar seguridad de los scripts #######
#### Quitar seguridad de los scripts #######
#### Quitar seguridad de los scripts #######

configuración => seguridad y destildar (Enable script security for Job DSL scripts) #########


##### BUSQUEDA EN LINUX => (find ~ -name 'npm')

####### REPASO CD/CD ##############

01 - CÓDIGO (Proyecto) =>
02 - Integración Continua (commit/contrucción/testeos) =>
03 - Distribución continua (deploy/testing/Env/testeos) =>
04 - Implementación continua (deploy producción Env)

##############################
####### Jenkins pipeline (jenkinsfile) #####
=> nos da la capacidad de escribir los los build steep en forma de codigo

BUILD => TEST => DEPLOY

realizar primer proyecto

# verificar tener instalado el plugin pipeline
# crear tarea "Primer pipeline" de tipo pipeline (en vez de estilo libre)

en Pipeline => definition colocar :

#####
pipeline {
    agent any 
    stages {
        stage('Build') { 
            steps {
                echo 'Construyendo la Aplicación' 
            }
        }
        stage('Test') { 
            steps {
                echo 'Arranca el proceso de pruebas unitarias' 
            }
        }
        stage('Deploy') { 
            steps {
                echo 'Desplegando al área de desarrollo' 
            }
        }
    }
}
#####

cuando damos ejecutar a la tarea con este metodo obtendremos un 
reporte con cada agente y un detalle de la ejecución de cada uno

####### multiples pasos en un stage ########


#####
pipeline {
    agent any 
    stages {
        stage('Build') { 
            steps {
                sh ' echo "Construyendo la Aplicación"'
                sh '''
                	echo 'pasos multiples en shell tambien funcionan'
                	pwd
                '''
            }
        }
        stage('Test') { 
            steps {
                echo 'Arranca el proceso de pruebas unitarias' 
            }
        }
        stage('Deploy') { 
            steps {
                echo 'Desplegando al área de desarrollo' 
            }
        }
    }
}
#####

DOCU PIPELINE => https://www.jenkins.io/doc/book/pipeline/syntax/

###########################################
##### Etapas paralelas vs secuenciales #####

modificar la tarea y colocar el siguiente comando que aplica el nuevo formato paralelo y secuencial
###
pipeline {
    agent any
    stages {
        stage('Secuencial') {
            stages {
                stage('Secuencial 1') {
                    steps {
                        echo "Secuencial: Parte 1"
                    }
                }
                stage('Secuencial 2') {
                    steps {
                        echo "Secuencial: Parte 2"
                    }
                }
                stage('Paralelo dentro de secuencial') {
                    parallel {
                        stage('Paralelo 1') {
                            steps {
                                echo "Paralelo en secuencial: Parte 1"
                            }
                        }
                        stage('Paralelo 2') {
                            steps {
                                echo "Paralelo en secuencial: Parte 2"
                            }
                        }
                    }
                }
            }
        }
    }
}
###

como el formato de la secuencia cambia el reporte se limpia y arranca de 0 con el nuevo formato
contando las diferencias a partir del formato actual


########## rety / timeout / sleep #######
pipeline {
    agent any
    stages {
        stage('Deploy') {
            steps {
                retry(3) {
                    sh 'echo "Arranca el deploy"'
                }

                timeout(time: 10, unit: 'SECONDS') {
                    sh 'sleep 15'
                }
            }
        }
    }
}
como le colocamos un sleep de de 15 segundos y decidimos darle un limite de ejecución
de 10 este para automaticamente dejando la alerta gris
(en vez de las rojas y verdes en el listado de ejecuciones)

/////////// CONSOLA //////////////////////////
Started by user admin
[Pipeline] Start of Pipeline
[Pipeline] node
Running on Jenkins in /var/jenkins_home/workspace/Primer pipeline
[Pipeline] {
[Pipeline] stage
[Pipeline] { (Deploy)
[Pipeline] retry
[Pipeline] {
[Pipeline] sh
+ echo Arranca el deploy
Arranca el deploy
[Pipeline] }
[Pipeline] // retry
[Pipeline] timeout
Timeout set to expire in 10 sec
[Pipeline] {
[Pipeline] sh
+ sleep 15
Cancelling nested steps due to timeout
Sending interrupt signal to process
Terminated
script returned exit code 143
[Pipeline] }
[Pipeline] // timeout
[Pipeline] }
[Pipeline] // stage
[Pipeline] }
[Pipeline] // node
[Pipeline] End of Pipeline
Timeout has been exceeded
org.jenkinsci.plugins.workflow.actions.ErrorAction$ErrorId: 747ea912-ddab-47c0-ac0d-9ddc22a73e54
Finished: ABORTED
///////////////////////////////////////////////

##################### retry #############

pipeline {
    agent any
    stages {
        stage('Deploy') {
            steps {
                timeout(time: 5, unit: 'SECONDS') {
                    retry(3) {
                        sh 'hola'
                    }
                }
            }
        }
    }
}
##############

cuando falla este se ejecuta 3 veces

###########
pipeline {
    agent any
    stages {
        stage('Deploy') {
            steps {
                timeout(time: 5, unit: 'SECONDS') {
                    retry(3) {
                        sh 'sleep 6'
                    }
                }
            }
        }
    }
}
###########

en este caso no fallo pero se cancelo ya que tenia una espera de 6 segundos y el timeout 
limite era de 5 para el proceso, entonces se corto automaticamente

###### OPTIONS ###########

pipeline {
  agent any
  stages {
    stage('Secuencial') {
      options {
        timeout(time: 15, unit: 'SECONDS')
			}
			stages {
				stage('Secuencial 1') {
					steps {
						echo "Secuencial: Parte 1"
					}
				}
				stage('Secuencial 2') {
					steps {
						sh 'sleep 16'
					}
				}
			}
		}
	}
}

############ 
en este ejemplo utilizamos options para delimitar el timeout general del secuencial como limitador total
-- en este caso fallo ya que el secualcial 2 supera el limite general

######### environment variables / credentials ########
variables de entorno y credenciales

DOCU: https://www.jenkins.io/doc/book/pipeline/syntax/#environment

se puede utilizar tanto en el pilepine general como en un stage solamente

#### CREAR CREDENCIALES ####

configuración => credentials => global (aparece un desplegable para agregar)

(metodo usuario y contraseña)
usuario = qwerty
contraseña = 1234
ID = USUARIO1
## CREATE ##

(metodo secret text)
secret = 123456789
ID = USUARIO50
## CREATE ##

#####
pipeline {
    agent any
    stages {
        stage('Ejemplo Username/Password') {
            environment {
                CRED_USUARIO = credentials('USUARIO1')
            }
            steps {
                sh 'echo "El usuario es $CRED_USUARIO_USR"'
                sh 'echo "La contraseña es $CRED_USUARIO_PSW"'
            }
        }
    }
}
#####

#####
pipeline {
    agent any
    environment { 
        SECRET_TEXT = credentials('USUARIO50')
    }
    stages {
        stage('Ejemplo para Secret Text') {
            steps {
                sh 'echo $SECRET_TEXT'
            }
        }
    }
}
#####

de esta manera podremos utilizar las credenciales alojadas en los pipelines

######### PARAMETERS/INPUT ##########

DOCU: https://www.jenkins.io/doc/book/pipeline/syntax/

####
pipeline {
    agent any
    parameters {
        string(name: 'PERSONA', defaultValue: 'Julian', description: 'A quien debo saludar?')

        booleanParam(name: 'FLAG', defaultValue: true, description: 'FLAG Verdadera?')

        choice(name: 'Eleccion', choices: ['A', 'B', 'C'], description: 'Elegir una opción')
    }
    stages {
        stage('Clase de Parametros') {
            steps {
                echo "Hola, como estas ${params.PERSONA}"

                echo "FLAG: ${params.FLAG}"

                echo "Eleccion: ${params.Eleccion}"
            }
        }
    }
}

####

cuando lo ejecutemos por segunda vez nos permitira elegir los parametros (Build with Parameters)
(en la primera ejecución utilizara los default)


### IMPORTANTE , cuando coloquemos este parametro tenemos que quitar lo parametrizado
 ya que queda del codigo anterior seteado en la tarea
####
pipeline {
  agent any
  stages {
    stage('Etapa 1') { 
      steps {
        echo "Arranca la Etapa 1" 
 
        sh 'sleep 10'
      }
    }
    stage('Etapa 2') {
      input {
        message "Continuar el proyecto?"
        ok "Si, continuar por favor."
        parameters {
            string(name: 'PERSONA', defaultValue: 'Julian', description: 'A quien debo saludar?')
        }
      }
      steps {
        echo "Hola, ${PERSONA}, un placer conocerte."
      }
    }
  }
}
####

ahora nos tendremos que posicionar en el reporte para setear los valores y no
permitira avanzar la ejecución hasta que lo realicemos
OPCIONAL (podriamos ponerle un timeout por si nadie ingresa valores y poner un default)

######### PIPELINE CRON ##########

######
pipeline {
    agent any
    triggers {
        cron('H/2 * * * *')
    }
    stages {
        stage('Example') {
            steps {
                echo 'Hello World'
            }
        }
    }
}
######
Esto se ejecutara solo cada 2 minutos despues de realizar la primera construcción
1ro = #1513 jun. 2023 2:33 ART
2do = #1613 jun. 2023 2:34 ART
3ro = #1713 jun. 2023 2:36 ART
4to = #1813 jun. 2023 2:38 ART

##### POST ACTIONS ######

son procesos que se ejecutan despues de terminar la tarea basica principal
se podria utilizar para comunicar el estado de la tarea enviando un mail por ejemplo

#####
pipeline {
	agent any
	stages {
		stage('Example') {
			steps {
				sh 'hola'
			}
		}
	}
	post { 
		failure { 
			echo 'Esta ejecución ha fallado'
		}
	}
}
#####

cuando falla la ejecución imprime 'Esta ejecución ha fallado'



######################################################################
######################################################################
######################################################################
## NodeJS docker pepeline #######

esto nos permite crear entornos aislados instalar las dependencias y realizar acciones 
con dependencias especificas sobre los diferentes stages

###### instalar docker pipeline en plugins


crear nueva tarea "NodeJS pipeline" (de tipo pipeline)

esta vez vamos a utilizar en Definition de "Pipeline script" a "Pipeline script from SCM"

SCM => git => https://github.com/macloujulian/nodejspipeline.git

Branch Specifier (blank for 'any') => */main

Script Path => "jenkinsfile" (nombre del archivo del repositorio)

## GUARDAR ##

//////// SALIDA /////////
ERROR: Could not find credentials matching docker-hub
Finished: FAILURE
////////////// en nuestro caso nos da error por que no tenemos bien colocadas las
credenciales del docker-hub (si otro ejersicio lo presisa retrocederemos para que funcione
o buscaremos documentación externa al curso)

####### Segundo ejercicio ##########

creamos una nueva tarea de pipeline llamada "Nodejs Pipeline 2" de tipo SCM
GIT => https://github.com/macloujulian/nodejspipeline.git
=> main
"jenkinsfile2" (es el mismo repo y otro archivo)

// SALIDA //
WARNING: Support for the legacy ~/.dockercfg configuration file and file-format has been removed and the configuration file will be ignored
permission denied while trying to connect to the Docker daemon socket 
at unix:///var/run/docker.sock: 
Post "http://%2Fvar%2Frun%2Fdocker.sock/v1.24/images/create?fromImage=node&tag=4.6": 
dial unix /var/run/docker.sock: connect: permission denied

// solucionado con "sudo chmod 666 /var/run/docker.sock"

tome la referencia de este sitio pero tenia los codigos aca
https://stackoverflow.com/questions/48957195/how-to-fix-docker-got-permission-denied-issue

al ejecutarlo nuevamente me da el error de 

ERROR: Could not find credentials matching docker-hub
Finished: FAILURE

########### FIXEO #######
verificar instalación del plugin "CloudBees Docker Build and Publish plugin"

en el capitulo 09 video 50 (retroceder)

######## pasamos al proyecto de Aplicaciónn nodeJS
nos aparecia un error de la versión como warning en la consola entonces 
cambiamos a la version mas proxima de 14.8.0 mencionada en el log y compilo,
pero sigue sin publicar el commit un hub docker

al colocar docker login ingresa automaticamente y no solicita credenciales actualmente en consola

########## Pipeline app Java maven ##############

crear nueva tarea => pipeline mave de tipo pipeline , desc => Java maven app
pipiline script from SCM => git => https://github.com/macloujulian/simple-java-maven-app.git
=> master, script => Jenkinsfile1
el pipeline que vendra del repo sera:

##################
pipeline {
    agent any
    
    tools {
        maven 'mavenjenkins'
    }
    
    stages {
        stage('Build') {
            steps {
                sh 'mvn -B -DskipTests clean package'
            }
        }
        stage('Test') {
            steps {
                sh 'mvn test'
            }
            post {
                always {
                    junit 'target/surefire-reports/*.xml'
                }
            }
        }
        stage('Deploy') {
            steps {
                sh './jenkins/scripts/deliver.sh'
            }
        }
    }
}
##################

Ejemplo fuera del contenedor

cambiar la ruta de la tarea anterior a "jenkins/Jenkinsfile"
la cual contiene el siguiente pipeline

########
pipeline {
    agent {
        docker {
            image 'maven:3-alpine'
            args '-v /root/.m2:/root/.m2'
        }
    }
    stages {
        stage('Build') {
            steps {
                sh 'mvn -B -DskipTests clean package'
            }
        }
        stage('Test') {
            steps {
                sh 'mvn test'
            }
            post {
                always {
                    junit 'target/surefire-reports/*.xml'
                }
            }
        }
        stage('Deliver') {
            steps {
                sh './jenkins/scripts/deliver.sh'
            }
        }
    }
}
########

al final de esto el contenedor se para y se elimina

########## maven app al docker hub #####

cambiar el directorio por "Jenkinsfile3"

pipeline {
    agent any
    environment {
        gitcommit = "${gitcommit}"
    }
    tools {
        maven 'mavenjenkins'
    }

    stages {

        stage('Verificación SCM') {
          steps {
            script {
              checkout scm
              sh "git rev-parse --short HEAD > .git/commit-id"  
              gitcommit = readFile('.git/commit-id').trim()
            }
          }  
        }
        stage('Build') {
            steps {
                sh 'mvn -B -DskipTests clean package'
            }
        }
        stage('Test') {
            steps {
                sh 'mvn test'
            }
            post {
                always {
                    junit 'target/surefire-reports/*.xml'
                }
            }
        }
        stage('Docker Build & Push') {
          steps {
            script {  
              docker.withRegistry('https://registry.hub.docker.com', 'docker-hub') {
                def appmavenjenkins = docker.build("macloujulian/mavenjenkins:${gitcommit}", ".")
                appmavenjenkins.push()
              }
            }  
          }  
        }
    }
}

vemos que en este metodo no se utiliza node sino que pepeline que es mas estricto 
y tendremos que declarar las variables en environment

DOCU DOCKER : https://docs.docker.com/engine/deprecated/#support-for-legacy-dockercfg-configuration-files

##### pipeline maven coin artifact y notificaciones en slak ##

cambiar el directorio por "Jenkinsfile4"

pipeline {
    agent any
    environment {
        gitcommit = "${gitcommit}"
    }
    tools {
        maven 'mavenjenkins'
    }

    stages {

        stage('Verificación SCM') {
          steps {
            script {
              checkout scm
              sh "git rev-parse --short HEAD > .git/commit-id"  
              gitcommit = readFile('.git/commit-id').trim()
            }
          }  
        }
        stage('Build') {
            steps {
                sh 'mvn -B -DskipTests clean package'
            }
            post {
                success {
                    archiveArtifacts artifacts: 'target/*.jar', fingerprint: true
                }
            }    
        }
        stage('Test') {
            steps {
                sh 'mvn test'
            }
            post {
                always {
                    junit 'target/surefire-reports/*.xml'
                }
            }
        }
        stage('Docker Build & Push') {
          steps {
            script {  
              docker.withRegistry('https://registry.hub.docker.com', 'docker-hub') {
                def appmavenjenkins = docker.build("macloujulian/mavenjenkins:${gitcommit}", ".")
                appmavenjenkins.push()
              }
            }  
          }  
        }
        stage('Deploy') {
            steps {
                sh './jenkins/scripts/deliver.sh'
            }
        }
    }
    post {
        success { 
            slackSend message: "Build Started - ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>)"
        }
        failure {
            slackSend message: "Build Started - ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>)"
        }
        always {
            slackSend message: "Build Started - ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>)"
        }    
    }
}

##################### verificador de ramas creadas en github #####################################
##################### verificador de ramas creadas en github #####################################

verificar si tenemos instalado el plugin "GitHub Branch Source"

nueva tarea "Mi repositorio de github"
de tipo organization folder
repository resources => 
Owner => Braian-92

Credentials =>
github.com => settings => developer settings => personal access tocken => generate new
note => jenkins
solo colocar el acceso a repo [x]
generate tocken =>  XXXXXXXXXXXXXXX

crear un fork del siguiente repo => https://github.com/spring-guides/gs-gradle

instalar dependencias

cd jenkins/jenkins_home/
mkdir gradle
cd $HOME
chown 1000:1000 /jenkins/jenkins_home/gradle

instalar plugin "docker pipeline" 

(dice que hay que asignar los permisos para acceder a jenkins dentro del contenedor como dijo antes)

Create multiBranch proyects = https://www.jenkins.io/doc/book/pipeline/multibranch/



##################### verificador de ramas creadas en bitbucket #####################################

instalar "bitbucket branch plugin" (por las dudas instalamos el generico de bitbucket)

neuva tarea "Mi repositorio de bitbucket"
sources => bitbucket, owner (equipo) workspaces en profile debitbucket.profile.com (no es pagina sino ref) => settings 

credenciales "App pasword" => create passwords, label => jenkins, permisos (todos)
dice que bitbucket tambien permite webhooks

Def "artifact" resultado binario de una compilación (docker, jar, zip, )


################ Jfrog Artifactory #####################
################ Jfrog Artifactory #####################

permite guardar los compilados y generar un historico para poder volver en el tiempo
 registrarse en https://jfrog.com/ 
 instalar plugin "Artifactory"

en usuario => setup => Gradle (para crear un repo) dejar en default y crear y cerrar

panel => artifacts

filtrar por gradle

configuración => identity and access => groups => new group , name deploy => save
menu => users => new user, name deploy
ir a un listado de grupos de abajo y mover deploy de available a included y sacar el de readers

subir un poco y setear la contraseña y poner un mail y save

menu => permisos => new , "deploy" => en la parte inferior seleccionar los repos, seleccionar los de gradle y pasarlos a la derecha
y mover en groups tambien despues de finalizar los permisos
en groups activar deploy and cache => create
poner editar en enm permisos dejar any
con esto se activa el buid y seleccionar todo y lo mismo enm groups

en jenkins ir a configuración => settings => jfrog => use credentials plugin [x], agregar credenciales, 
user deploy y contraseña generar desde la web una password api

id y desc como "artifactory" => add

instance id => link de ifrag usuario propio despues del .com/

y en el segundo incluir el link con el hpps => test conection => guardar

Info: dice que java y gravel no usa docker

en configuración => poner un nombre en gradle como hicimos con node (solo poner un nombre) => save (poner v6.7)

nueva tarea => "testeo de gradle" => pipeline => origen SCM =>
git => https://github.com/macloujulian/cursojenkins.git => main => jfrog-artifactory/Jenkinsfile => save


############

node {
  def server = Artifactory.server('cursojenkinsmac.jfrog.io')
  def rtGradle = Artifactory.newGradleBuild()
  def buildInfo = Artifactory.newBuildInfo()
  
  stage 'Complicacion/Build'
      git branch: 'main', url: 'https://github.com/macloujulian/gs-gradle.git'

  stage 'Configuracion Artifactory'
      rtGradle.tool = 'gradle' // Como le asignamos al nombre de la herramienta en Jenkins en configuración
      rtGradle.deployer repo:'default-gradle-dev-local',  server: server
      rtGradle.resolver repo:'default-gradle-dev', server: server

      stage('Configuracion buildInfo') {
          buildInfo.env.capture = true
          buildInfo.env.filter.addInclude("*")
      }

      stage('Configuraciones extra de gradle') {
          rtGradle.usesPlugin = true // El plugin ya está definifo en el build script
      }
      stage('Ejecutar Gradle') {
          rtGradle.run rootDir: "artifactory/", tasks: 'clean artifactoryPublish', buildInfo: buildInfo
      }
      stage('Publicar buildInfo') {
          server.publishBuildInfo buildInfo
      }
}

#########

ejecutar tarea

############## LLAMAR A API POR HTTP BITBUCKET ###########

instalar plugin 'HTTP Request'

entrar al workspace de bitbucket => settings => OAuth consumers => add consumer, nombre "jenkins", 
callback url =>  pagina y puerto del jenkins "http://192.168.1.38:8080/" = URL
checkear pull request y repo (repo es auto) => save => copiar la clave

en jenkins en manage credentials => agregar una global (poner usuario hash y clave secreta de bitbucket)
id = bitbucket-oauth igual que en descripcion (esto nos da un link con la docu)


###########

hostname -i (mostrar ip del servidor)


########### BLUE OCEAN ######

buscar e instalar el plugin blue ocean
entran en la nueva sección de 
"Open blue ocean que aparece en la solapa de configuración tareas etc"
al crear un nuevo pipeline te da como preferencia el origen de los archivo como repositorios

GIT => https://github.com/macloujulian/gs-gradle.git
create pipeline

- Detectara que la rama contiene un jenkinsfile y actuara sobre el mismo

############ SSH AGENT ################

utilización de credenciales con el  plugin a instalar ssh agent

node {
  stage('Etapa con Git') {  
    sshagent (credentials: ['github-key']) {
      // Con el siguiente comando el ultimo commit id del repositorio que se especifica
      sh 'git ls-remote -h --refs git@github.com:macloujulian/cursojenkins.git master |awk "{print $1}"'
    }
  }
}



########### PIPELINE MULTIBRANCH ###############

crear una tarea "multibranch sample app"
de tipo Multibranch pipeline
brach sources >  GIT =>
proyect repository = "https://github.com/CursoJenkins0/multibranch-sample-app"

si no tenemos enlazado por un webhook podriamos realizar 
un sistema de cron que revise el estado periodicamente
###  opcional ##
configuración => Periodically if not otherwise run => 1 hour
###  opcional ##

si lo ejecutamos va a verificar si contiene un jenkinsfile y va a realizar
 la tarea segun la tenga o no

 ## FILTROS ##

 branch sources => filter => "filter by name (with willcards)"
 en este sitio tenemos 2 caracteristicas (include y exclude)

 en include aplicaremos todo "*"
 y en esclude quitaremos todo lo que contenga "dev-*"

 ############ segundo caso ##########

 solo en include colocar "main fix-*"

 como segundo paso agregaremos nuevamente add (pero en vez de filter) colocamos 
 check out to matching local branch
 luego agregar => clean after checkout
 luego agregar => clean before checkout

 IMPORTANTE : siempre que termine de de realizarse cambios en la tarea de tipo
 multibranch realizara un escaneo automatico