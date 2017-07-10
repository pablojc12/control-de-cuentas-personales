# control-de-cuentas-personales
Aplicacion web para gestionar el control de distintas cuentas, esta escrito en AngularJS con PHP

# Para configurar la base de datos:

	a) Ejecutar phpMyAdmin y correr el script "db.slq" que se encuentta al mismo nivel que este archivo.

	b) Configura el archivo "public_html/server/configConection.php":
		("server", "TU_SERVIDOR") => "localhost" si lo estas coriendo en un servidor local, "IP or hosting name" 
					      si lo corres desde un dominio propio
		("userDB", "TU_USUARIO_DB") => Depende de como este configurado tu phpMyAdmin
		("passDB", "TU_PASS_DB") => Depende de como este configurado tu phpMyAdmin
		("db", "mydb") => Este es el nombre actual de la base de datos

# Agregar cuentas a la DB
	
	Esto se hace desde phpMyAdmin dentro de la tabla "acount"
