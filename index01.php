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