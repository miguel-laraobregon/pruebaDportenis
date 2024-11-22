
# Sistema de gestión de menús desarrollado en PHP aplicando MVC, POO y utilizando Bootstrap para estilos

## Características

- CRUD completo para la gestión de menús.
- Navegación dinámica con submenús desplegables.
- URLs amigables basadas en nombres de menús.
- Estilo responsivo con **Bootstrap**.
- Arquitectura limpia basada en MVC (Modelo, Vista, Controlador).
- Rutas definidas dinámicamente con un sistema de routing.
- **Docker** para contenerización y despliegue.

## Tecnologías utilizadas

- **PHP 8.2**
- **MySQL 8** para la base de datos.
- **Bootstrap 4.6.2** para estilos.
- **Docker** y **Docker Compose** para la configuración del entorno.
- **Composer** para la carga automática de dependencias.
- **Git** para el control de versiones.

## Estructura del proyecto

```plaintext
.
├── www/
│   └── app/
│   │   ├── Controllers/    # Controladores de la aplicación
│   │   ├── Models/         # Modelos de la aplicación
│   │   └── Views/          # Vistas de la aplicación
│   └── config/
│   │   └── Database.php    # Configuración para conexion de base de datos
│   └── public/             # Carpeta pública del proyecto
│   │   └── index.php       # Punto de entrada principal
│   │   └── .htaccess       # Configuracion para rutas
│   └── router.php          # Archivo de routing
│   └── vendor/             # Dependencias gestionadas por Composer
├── database.sql            # Archivo SQL con la estructura inicial de la base de datos
└── Dockerfile              # Definición de la imagen de la aplicación
└── docker-compose.yml      # Configuración de servicios

``` 
## Instalación y configuración

Sigue estos pasos para configurar y ejecutar el proyecto en tu máquina local:

1. Clona el repositorio
git clone git@github.com:miguel-laraobregon/pruebaDportenis.git
cd pruebaDportenis

2. Configura Docker
El proyecto incluye un archivo docker-compose.yml que define los servicios necesarios. Asegúrate de tener Docker y Docker Compose instalados.

Inicia los contenedores con:
docker-compose up -d

Esto levantará:
Un servidor PHP.
Una base de datos MySQL.
phpMyAdmin

3. Importa la base de datos
El archivo sql/Database.sql contiene la tabla necesaria para este proyecto. 
Para importarlo:
    Opcion 1: Accede a phpMyAdmin desde http://localhost:8081, importa la tabla en la db dportenis
    Opcion 2: Ejecuta -> docker exec -i mysql-db mysql -u root -ptest dportenis < database.sql


4. Accede al proyecto
Accede al proyecto en http://localhost:8000


## Uso
### Gestión de Menús
- Accede a la lista de menús en /menus.
- Crea un nuevo menú en /menus/create.
- Edita menús existentes o elimínalos usando las opciones correspondientes.
### Navegación por Nombres
- Los menús pueden ser accedidos directamente usando sus nombres como URLs. Por ejemplo: Un menú llamado "Catálogo" estará disponible en /catalogo.

### Submenús Desplegables
Al hacer clic en un menú principal, se desplegarán los submenús configurados.

## Archivos importantes
### Dockerfile
Define la imagen del contenedor para ejecutar el servidor PHP.

### docker-compose.yml
Orquesta servicios como el servidor PHP y MySQL.

### database.sql
Estructura inicial de la base de datos:

```sql
CREATE TABLE menus (
    CREATE TABLE menus (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nombre del menu',
    description TEXT COMMENT 'Descripción del menu',
    navLink VARCHAR(255) NOT NULL COMMENT 'String utilizado para navegacion',
    parent_id BIGINT DEFAULT NULL COMMENT 'ID del menu padre'
) ENGINE=InnoDB CHARSET=utf8mb4 COMMENT='Almacena el catalogo de menus disponibles en la aplicación';
```
Esta tabla permite gestionar menús y submenús.

