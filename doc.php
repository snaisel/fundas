<?php
/**
 * Documentación de Uso - Sistema de Inventario para Fundas de Teléfono y Accesorios
 *
 * 1. Instalación
 *    - Ejecute el archivo `install.php`.
 *    - Defina los siguientes parámetros:
 *        - Base de datos
 *        - Tabla de usuarios
 *        - Contraseña
 *        - Puerto
 *    - Cree el usuario administrador (ID = 1).
 *
 * 2. Descripción General
 *    Este programa administra un inventario de fundas de teléfono y accesorios.
 *
 * 3. Estructura de Datos
 *    - Marca: Nombre y referencia de 2 dígitos. Permite filtrar por marca.
 *      - Recomendaciones: Use "Universal" para accesorios genéricos y "Accesorios" para otros elementos.
 *    - Año: Categoriza modelos por año/generación. Útil para buscar productos recientes.
 *    - Modelo: Clasificación por marca y año. La referencia puede repetirse en años diferentes.
 *      - Ejemplo: "Moto 5" de 2024 (ref 15) y "Moto 15" de 2025 (ref 15) son distintos por el año.
 *    - Tipo: Define el tipo de funda/accesorio y su precio. Si un modelo reciente requiere precio mayor, cree un tipo diferente.
 *    - Color: Color, textura o motivo del producto.
 *    - Relacionados: Una funda puede servir para varios modelos (ej: iPhone 6 y 6S).
 *
 * 4. Funcionalidades
 *    - Fundas: Crear fundas, gestionar stock, exportar CSV para otras aplicaciones (ej: etiquetas).
 *    - Sumar: Añade una unidad al stock de un ID concreto.
 *    - Restar: Resta una unidad al stock de un ID concreto o vacía el stock para inventarios futuros.
 *    - Administrar: Cambia la base de datos y usuarios.
 *      - El usuario con ID 1 (administrador) puede ver y gestionar todos los usuarios.
 *      - Otros usuarios solo pueden cambiar su contraseña.
 *      - Futuras versiones incluirán logs de operaciones por usuario.
 *
 * 5. Recomendaciones
 *    - Mantenga el usuario administrador con ID 1.
 *    - Use referencias y clasificaciones coherentes para facilitar búsquedas y gestión.
 *    - Exporte regularmente el inventario para respaldo y uso externo.
 */
require 'class.php';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Documentación - Sistema de Fundas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="js.js" type="text/javascript"></script>
    </head>
    <?php require 'login.php'; ?>
    <body>
        <div
            class="container">
            <?php include 'header.php'; ?>
            <h1 class="mb-4">Documentación de Uso</h1>
            <div class="accordion" id="docAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingInstalacion">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInstalacion" aria-expanded="true">
                            1. Instalación
                        </button>
                    </h2>
                    <div id="collapseInstalacion" class="accordion-collapse collapse show" data-bs-parent="#docAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li>Ejecute el archivo
                                    <code>install.php</code>.</li>
                                <li>Defina los siguientes parámetros:
                                    <ul>
                                        <li>Base de datos</li>
                                        <li>Usuario Base de datos</li>
                                        <li>Contraseña</li>
                                        <li>Puerto</li>
                                    </ul>
                                </li>
                                <li>Cree el usuario administrador (ID = 1).</li>
                                <li>Opcionalmente puede usar el archivo sample para un volcado de datos después de esta operación</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingDescripcion">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescripcion">
                            2. Descripción General
                        </button>
                    </h2>
                    <div id="collapseDescripcion" class="accordion-collapse collapse" data-bs-parent="#docAccordion">
                        <div class="accordion-body">
                            Este programa administra un inventario de fundas de teléfono y accesorios.
                            <ul>
                                <li>Gestión de productos</li>
                                <li>Control de stock</li>
                                <li>Registro de cambios usuarios<span class="badge bg-secondary">Por implementar</span>
                                </li>
                                <li>Registro de ventas<span class="badge bg-secondary">Por implementar</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEstructura">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstructura">
                            3. Estructura de Datos
                        </button>
                    </h2>
                    <div id="collapseEstructura" class="accordion-collapse collapse" data-bs-parent="#docAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li>
                                    <b>Marca:</b>
                                    Nombre y referencia de 2 dígitos. Permite filtrar por marca.<br>
                                    <i>Recomendación:</i>
                                    Use "Universal" para accesorios genéricos y "Accesorios" para otros elementos.
                                </li>
                                <li>
                                    <b>Año:</b>
                                    Categoriza modelos por año/generación. Útil para buscar productos recientes y comprobar cuanto lleva un modelo en el mercado.</li>
                                <li>
                                    <b>Modelo:</b>
                                    Clasificación por marca y año. La referencia puede repetirse en años diferentes.<br>
                                    <i>Ejemplo:</i>
                                    "Moto 5" de 2024 (ref 15) y "Moto 15" de 2025 (ref 15) su referencia total será XX2415 y XX2515 siendo XX la referencia del modelo.
                                </li>
                                <li>
                                    <b>Tipo:</b>
                                    Define el tipo de funda/accesorio y su precio. Si un modelo reciente requiere precio mayor, cree un tipo diferente.</li>
                                <li>
                                    <b>Color:</b>
                                    Color, textura o motivo del producto.</li>
                                <li>
                                    <b>Relacionados:</b>
                                    Sirve para poder relacionar dos modelos que coincidan en forma usando así la misma funda o accesorio.<br>
                                    Una funda puede servir para varios modelos (ej: iPhone 6 y 6S).</li>
                                <li>
                                    <b>Referencia:</b>
                                    Las referencias serán usadas para crear un código de 8 números personalizado. Luego aparecerá en la exportación y puede ser útil para clasificación de productos.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFuncionalidades">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuncionalidades">
                            4. Funcionalidades
                        </button>
                    </h2>
                    <div id="collapseFuncionalidades" class="accordion-collapse collapse" data-bs-parent="#docAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li>
                                    <b>Inicio:</b>
                                    Pantalla principal del programa, muestra un resumen del inventario y acceso a funciones de edición y eliminado.
                                </li>
                                <li>
                                    <b>Opciones:</b>
                                    Permite crear nuevas opciones para los productos.
                                </li>
                                <li>
                                    <b>Fundas:</b>
                                    Crear fundas, gestionar stock, exportar CSV para otras aplicaciones (ej: etiquetas).</li>
                                <li>
                                    <li>
                                        <b>Modelos</b>
                                        Podemos visualizar por modelo, de todas las fundas que disponemos.
                                    </li>
                                    <b>Sumar:</b>
                                    Añade una unidad al stock de un ID concreto.</li>
                                <li>
                                    <b>Restar:</b>
                                    Resta una unidad al stock de un ID concreto o vacía el stock para inventarios futuros.</li>
                                <li>
                                    <b>Administrar:</b>
                                    Cambia la base de datos y gestiona usuarios.
                                    <ul>
                                        <li>El usuario con ID 1 (administrador) puede ver y gestionar todos los usuarios.</li>
                                        <li>Otros usuarios solo pueden cambiar su contraseña.</li>
                                        <li>Futuras versiones incluirán logs de operaciones por usuario.</li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Ayuda:</b>
                                    Proporciona información sobre el uso del programa y sus funcionalidades.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingRecomendaciones">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecomendaciones">
                            5. Recomendaciones
                        </button>
                    </h2>
                    <div id="collapseRecomendaciones" class="accordion-collapse collapse" data-bs-parent="#docAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li>Mantenga el usuario administrador con ID 1.</li>
                                <li>Use referencias y clasificaciones coherentes para facilitar búsquedas y gestión. Las referencias tendrán 2 dígitos, por lo que se recomienda usar 10 como referencia inicial</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

