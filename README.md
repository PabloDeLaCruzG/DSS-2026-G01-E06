# 🎮 GameLink Marketplace

![Laravel](https://img.shields.io/badge/Laravel-11.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Entrega-01%20En%20Progreso-yellow?style=for-the-badge)

> **Diseño de Sistemas Software (DSS) - Curso 2025/2026**  
> Universidad de Alicante  
> Grupo: **G01** | Equipo: **E06**

---

## 📖 Descripción del Proyecto

**GameLink** es una plataforma web híbrida de comercio electrónico (Marketplace) verticalizada en el sector de los videojuegos. El sistema centraliza la oferta del mercado actuando como intermediario de confianza y facilitando transacciones bajo dos modelos simultáneos:

1.  **B2C (Business-to-Consumer):** Empresas verificadas venden productos nuevos o reacondicionados.
2.  **C2C (Consumer-to-Consumer):** Usuarios particulares compran, venden artículos de segunda mano entre sí.

El objetivo es fomentar la economía circular en el *gaming*, ofreciendo funcionalidades avanzadas como **carrito multi-vendedor**, **sistema de subastas**, **intercambio inteligente (trueque)** y **verificación de vendedores profesionales**.

---

## 👥 Equipo de Desarrollo (G01-E06)

| Miembro | GitHub User |
| :--- | :--- |
| **Pablo De La Cruz Gomez** | [@PabloDeLaCruzG](https://github.com/PabloDeLaCruzG) |
| **Silvia Carrasco Gavilá** | [@SilviaaCG](https://github.com/SilviaaCG) |
| **Marius Antonio Nica** | [@man31-ua](https://github.com/man31-ua) |
| **Ilyas Chourafi** | [@ilyaschourafi](https://github.com/ilyaschourafi) |
| **Ismael Adrián G. Verdugo** | [@iagr1-ua](https://github.com/iagr1-ua) |

---

## 🛠️ Stack Tecnológico

El proyecto sigue una **Arquitectura en Capas** (Presentación, Servicios, Persistencia) implementada sobre el patrón MVC.

*   **Lenguaje:** PHP 8.3
*   **Framework:** Laravel 11.x
*   **Base de Datos:** MySQL 8.0
*   **Gestor de Dependencias:** Composer
*   **Testing:** PHPUnit
*   **Motor de Plantillas:** Blade

---

## 🚀 Instalación y Despliegue

Sigue estos pasos para levantar el entorno de desarrollo en local.

### 1. Requisitos Previos
Asegúrate de tener instalado:
*   PHP >= 8.3 (con extensiones `bcmath`, `curl`, `mbstring`, `mysql`, `xml`)
*   Composer
*   MySQL Server


### 2. Clonar el Repositorio
```bash
git clone https://github.com/PabloDeLaCruzG/DSS-2026-G01-E06.git
cd DSS-2026-G01-E06
```

### 3. Instalar Dependencias
```bash
composer install
```

### 4. Configuración del Entorno (.env)
Copia el archivo de ejemplo y genera la clave de aplicación.
```bash
cp .env.example .env
php artisan key:generate
```

Abre el archivo `.env` y asegúrate de configurar la base de datos `dss`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dss
DB_USERNAME=dss
DB_PASSWORD=dss

SESSION_DRIVER=file
CACHE_STORE=file
```

### 5. Base de Datos y Seeders (Datos de prueba)
Ejecuta las migraciones para crear las tablas y los seeders para poblar la base de datos con el catálogo de juegos, usuarios, perfiles y pedidos de ejemplo.
```bash
php artisan migrate:fresh --seed
```

### 6. Ejecutar el Servidor
```bash
php artisan serve
```
La aplicación estará disponible en: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Ejecución de Tests

El proyecto incluye pruebas automatizadas para verificar la integridad del Modelo de Dominio y las relaciones entre entidades. Para ejecutarlas:

```bash
php artisan test
```
---
