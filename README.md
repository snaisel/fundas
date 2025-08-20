# Documentación de Uso  
**Sistema de Inventario para Fundas de Teléfono y Accesorios**

## 1. Instalación
- Ejecute el archivo `install.php`.
- Defina los siguientes parámetros:
    - Base de datos
    - Tabla de usuarios
    - Contraseña
    - Puerto
- Cree el usuario administrador (ID = 1).

## 2. Descripción General
Este programa administra un inventario de fundas de teléfono y accesorios.

## 3. Estructura de Datos
- **Marca:** Nombre y referencia de 2 dígitos. Permite filtrar por marca.  
    _Recomendación:_ Use "Universal" para accesorios genéricos y "Accesorios" para otros elementos.
- **Año:** Categoriza modelos por año/generación. Útil para buscar productos recientes.
- **Modelo:** Clasificación por marca y año. La referencia puede repetirse en años diferentes.  
    _Ejemplo:_ "Moto 5" de 2024 (ref 15) y "Moto 15" de 2025 (ref 15) son distintos por el año.
- **Tipo:** Define el tipo de funda/accesorio y su precio. Si un modelo reciente requiere precio mayor, cree un tipo diferente.
- **Color:** Color, textura o motivo del producto.
- **Relacionados:** Una funda puede servir para varios modelos (ej: iPhone 6 y 6S).

## 4. Funcionalidades
- **Fundas:** Crear fundas, gestionar stock, exportar CSV para otras aplicaciones (ej: etiquetas).
- **Sumar:** Añade una unidad al stock de un ID concreto.
- **Restar:** Resta una unidad al stock de un ID concreto o vacía el stock para inventarios futuros.
- **Administrar:** Cambia la base de datos y usuarios.
    - El usuario con ID 1 (administrador) puede ver y gestionar todos los usuarios.
    - Otros usuarios solo pueden cambiar su contraseña.
    - Futuras versiones incluirán logs de operaciones por usuario.

## 5. Recomendaciones
- Mantenga el usuario administrador con ID 1.
- Use referencias y clasificaciones coherentes para facilitar búsquedas y gestión.
- Exporte regularmente el inventario para respaldo y uso externo.   