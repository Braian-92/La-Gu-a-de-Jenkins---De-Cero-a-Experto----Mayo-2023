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