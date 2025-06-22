# Proyecto_Ciberseguridad

# Configuración Completa de Servidor Apache HTTP en Kali Linux

## 🔧 Requisitos Previos
- Kali Linux actualizado
- Acceso de superusuario (`sudo su` o comandos con `sudo`)
- Conexión a internet para instalar paquetes

---

##  Paso 1: Actualizar Sistema e Instalar Apache
```bash
sudo apt update
sudo apt upgrade -y
sudo apt install apache2 -y
```
##  Paso 2: Iniciar y Habilitar Apache
```bash
sudo systemctl start apache2
sudo systemctl status apache2  # Verificar que esté activo (running)
```
##  Paso 3: Crear directorio para tu sitio web
```bash
sudo mkdir -p /var/www/mi_sitio/public_html
sudo chown -R $USER:$USER /var/www/mi_sitio/public_html
sudo chmod -R 755 /var/www/mi_sitio/public_html
```

##  Paso 4: Crear página web
```bash
sudo nano /var/www/mi-sitio/index.html
```

## Paso 5: Configurar Virtual Host
```bash
sudo nano /etc/apache2/sites-available/mi-sitio.conf
```
```
<VirtualHost *:80>      
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/my_sites/public_html
        <Directory "/var/www/my_sites/public_html">
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

## Paso 6: Montar servidor
```bash
sudo a2dissite *
sudo a2ensite mi-sitio.conf
sudo systemctl restart apache2
```

## Paso 7: Probar servidor
-  En la url del navegador coloca la tu ip local y ve si se muestra tu página
