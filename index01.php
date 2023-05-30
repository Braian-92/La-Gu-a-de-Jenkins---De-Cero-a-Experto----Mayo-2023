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
(reboot reiniciar la pc desde comando)
