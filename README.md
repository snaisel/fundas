# Usage Documentation

**Inventory System for Phone Cases and Accessories**

## 1. Installation

- Run the `install.php` file.
- Define the following parameters:
  - Database
  - Users table
  - Password
  - Port
- Create the administrator user (ID = 1).

## 2. General Description

This program manages an inventory of phone cases and accessories.

## 3. Data Structure

- **Brand:** Name and 2-digit reference. Allows filtering by brand.  
   _Recommendation:_ Use "Universal" for generic accessories and "Accessories" for other items.
- **Year:** Categorizes models by year/generation. Useful for searching recent products.
- **Model:** Classification by brand and year. The reference can be repeated in different years.  
   _Example:_ "Moto 5" from 2024 (ref 15) and "Moto 15" from 2025 (ref 15) are different due to the year.
- **Type:** Defines the type of case/accessory and its price. If a recent model requires a higher price, create a different type.
- **Color:** Color, texture, or pattern of the product.
- **Related:** A case can fit several models (e.g., iPhone 6 and 6S).
- **References:** The reference for each case consists of an 8-digit code composed of brand-year-model-type-color, for example 1124161009.
<ul>
<li>11 - Brand iPhone</li>
<li>24 - Year 2024 </li>
<li>16 - Model 16</li>
<li>10 - Type Book Case</li>
<li>09 - Light Blue</li>
</ul>

## 4. Features

- **Home:** Review, edit, and delete created options.
- **Options:** Create Brands, Models, Years, Types, Colors, and Relations.
- **Cases:** Create cases, manage stock, export CSV for other applications (e.g., labels).
- **Models:** View summary by model.
- **Add:** Add one unit to the stock of a specific ID.
- **Subtract:** Subtract one unit from the stock of a specific ID or empty the stock for future inventories.
- **Administer:** Change the database and users.
- **Help:** Usage help.
  - The user with ID 1 (administrator) can view and manage all users.
  - Other users can only change their password.
  - Future versions will include operation logs by user.

## 5. Recommendations

- Keep the administrator user with ID 1.
- Use consistent references and classifications to facilitate searches and management.
- Regularly export the inventory for backup and external use.

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
- **Referencias:** La referencia de cada funda consiste en un código de 8 dígitos compuesto de marca-año-modelo-tipo-color por ejemplo 1124161009.
<ul>
<li>11 - Marca iPhone</li>
<li>24 - Año 2024 </li>
<li>16 - modelo 16</li>
<li>10 - Tipo Funda de Libro</li>
<li>09 - Azul Claro</li>
</ul>

## 4. Funcionalidades

- **Inicio:** Revisar, editar y eliminar opciones creadas.
- **Opciones:** Crear Marcas, Modelos, Años, Tipos, Colores y Relaciones.
- **Fundas:** Crear fundas, gestionar stock, exportar CSV para otras aplicaciones (ej: etiquetas).
- **Modelos:** Ver resumen por modelo.
- **Sumar:** Añade una unidad al stock de un ID concreto.
- **Restar:** Resta una unidad al stock de un ID concreto o vacía el stock para inventarios futuros.
- **Administrar:** Cambia la base de datos y usuarios.
- **Ayuda:** Ayuda para el uso.
  - El usuario con ID 1 (administrador) puede ver y gestionar todos los usuarios.
  - Otros usuarios solo pueden cambiar su contraseña.
  - Futuras versiones incluirán logs de operaciones por usuario.

## 5. Recomendaciones

- Mantenga el usuario administrador con ID 1.
- Use referencias y clasificaciones coherentes para facilitar búsquedas y gestión.
- Exporte regularmente el inventario para respaldo y uso externo.
