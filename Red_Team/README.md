# Configuraciones y herramientas del Red Team

## 1. Configurar el servidor malicioso:

### 🔧 Requisitos Previos
- Kali Linux actualizado
- Acceso de superusuario (`sudo su` o comandos con `sudo`)
- Conexión a internet para instalar paquetes

---

####  Paso 1: Actualizar Sistema e Instalar Apache
```bash
sudo apt update
sudo apt upgrade -y
sudo apt install apache2 -y
```
####  Paso 2: Iniciar y Habilitar Apache
```bash
sudo systemctl start apache2
sudo systemctl status apache2  # Verificar que esté activo (running)
```
####  Paso 3: Crear directorio para tu sitio web
```bash
sudo mkdir -p /var/www/mi_sitio/public_html
sudo chown -R $USER:$USER /var/www/mi_sitio/public_html
sudo chmod -R 755 /var/www/mi_sitio/public_html
```

####  Paso 4: Crear página web
* Crea la página dentro del directorio que creamos anteriormente

#### Paso 5: Configurar Virtual Host para que permita peticiones post y poder robar las credenciales
```bash
sudo nano /etc/apache2/sites-available/mi-sitio.conf
```
```
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

#### Paso 6: Montar servidor
```bash
sudo a2dissite *
sudo a2ensite mi-sitio.conf
sudo a2enmod headers
sudo a2enmod rewrite
sudo systemctl restart apache2
sudo systemctl restart apache2
```

#### Paso 7: Probar servidor
-  En la url del navegador coloca la tu ip local y ve si se muestra tu página

## 2. Instalar y Usar HTTrack

HTTrack es una herramienta poderosa que te permite descargar sitios web completos para verlos offline. Es especialmente útil en entornos de pentesting o análisis forense para examinar el código fuente de un sitio sin conexión a internet, o para crear copias locales de recursos web. Kali Linux, al estar basado en Debian, facilita su instalación.

#### Instalación de HTTrack en Kali Linux

1.  **Abre una Terminal:**
    Puedes abrir una terminal en Kali Linux de varias maneras:
    * Haz clic en el icono de la terminal en la barra de tareas.
    * Usa el atajo de teclado `Ctrl + Alt + T`.
    * Busca "Terminal" en el menú de aplicaciones.

2.  **Actualiza los paquetes del sistema:**
    Es una buena práctica asegurarse de que tu lista de paquetes esté actualizada antes de instalar cualquier software nuevo. Esto garantiza que obtendrás la última versión estable y resolverás posibles dependencias.
    ```bash
    sudo apt update
    ```
    Presiona `Enter` y si se te solicita, introduce tu contraseña de usuario.

3.  **Instala HTTrack:**
    Una vez que la lista de paquetes esté actualizada, puedes instalar HTTrack usando el siguiente comando:
    ```bash
    sudo apt install httrack
    ```
    Cuando te pregunte si deseas continuar (`Y/n`), escribe `Y` y presiona `Enter`.

4.  **Verifica la instalación (opcional):**
    Para asegurarte de que HTTrack se instaló correctamente, puedes verificar su versión:
    ```bash
    httrack --version
    ```
    Esto debería mostrar la versión de HTTrack instalada.

---

#### Uso de HTTrack en Kali Linux

HTTrack se usa principalmente a través de la línea de comandos en Kali Linux, lo que ofrece un control granular sobre el proceso de descarga.

### Sintaxis Básica de HTTrack

La sintaxis fundamental para descargar un sitio web es:

```bash
httrack <URL_DEL_SITIO> -O <CARPETA_DE_DESTINO> [OPCIONES]
