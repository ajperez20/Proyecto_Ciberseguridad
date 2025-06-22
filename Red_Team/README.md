# Cómo Instalar y Usar HTTrack en Kali Linux

HTTrack es una herramienta poderosa que te permite descargar sitios web completos para verlos offline. Es especialmente útil en entornos de pentesting o análisis forense para examinar el código fuente de un sitio sin conexión a internet, o para crear copias locales de recursos web. Kali Linux, al estar basado en Debian, facilita su instalación.

## 1. Instalación de HTTrack en Kali Linux

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

## 2. Uso de HTTrack en Kali Linux

HTTrack se usa principalmente a través de la línea de comandos en Kali Linux, lo que ofrece un control granular sobre el proceso de descarga.

### Sintaxis Básica de HTTrack

La sintaxis fundamental para descargar un sitio web es:

```bash
httrack <URL_DEL_SITIO> -O <CARPETA_DE_DESTINO> [OPCIONES]