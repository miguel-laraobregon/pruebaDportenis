CREATE TABLE menus (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nombre del menu',
    description TEXT COMMENT 'Descripción del menu',
    navLink VARCHAR(255) NOT NULL COMMENT 'String utilizado para navegacion',
    parent_id BIGINT DEFAULT NULL COMMENT 'ID del menu padre'
) ENGINE=InnoDB CHARSET=utf8mb4 COMMENT='Almacena el catalogo de menus disponibles en la aplicación';
