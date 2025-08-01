# Copilot Instructions for Asset Codebase

## Overview

This project is a web application built with the CodeIgniter PHP framework. It manages assets, risks, financials, and organizational data for UPK (Unit Pelaksana Kegiatan) and related divisions. The codebase is structured around CodeIgniter's MVC pattern.

## Architecture & Key Components

- **application/controllers/**: Contains controllers for each domain (e.g., `risiko/Profil_risiko.php`, `umum/`, `spi/`, etc.). Controllers load models and views based on user roles and request context.
- **application/models/**: Business logic and database access. Example: `Model_risiko.php` fetches UPK units from the `bagian_upk` table.
- **application/views/**: UI templates, organized by domain. Use PHP for dynamic rendering and session-based flash messages.
- **application/config/**: Configuration files for routing (`routes.php`), database (`database.php`), and autoloading.
- **assets/**: Static files (CSS, JS, images, plugins).
- **uploads/**: User-uploaded files, e.g., `arsip/`.

## Developer Workflows

- **Start local server**: Use XAMPP (Apache + MySQL). Place code in `htdocs` and access via `http://localhost/asset`.
- **Database setup**: Import SQL files (e.g., `asset.sql`) into MySQL. Default DB config: user `root`, no password, DB name `asset`.
- **Dependencies**: Managed via Composer (`composer.json`). Run `composer install` to set up PHP libraries (e.g., `dompdf/dompdf`).
- **Session & Auth**: User authentication is enforced in controllers (see `__construct` in `Profil_risiko.php`). Redirects to `auth` if not logged in.
- **Role-based views**: Sidebar and navigation templates are loaded based on `bagian` session variable (e.g., `sidebar_umum.php`, `sidebar_upk.php`).
- **Flash messages**: Use `$this->session->set_flashdata('info', ...)` and display in views with `$this->session->flashdata('info')`.

## Project-Specific Patterns

- **Routing**: Default controller is `auth`. URLs map to controllers/methods (see `routes.php`).
- **Data access**: Models use CodeIgniter's Query Builder (`$this->db`).
- **PDF generation**: Uses `dompdf/dompdf` for printable reports (see controllers with `cetak_*` methods).
- **Form validation**: Use `$this->form_validation` library in controllers for input checks.
- **View composition**: Views are assembled from header, navbar, sidebar, main content, and footer templates.
- **Table rendering**: Data tables in views are populated via PHP loops and can be enhanced with JS plugins (see `table-responsive` classes).

## Integration Points

- **External libraries**: Managed via Composer. Main dependency is `dompdf/dompdf`.
- **Database**: MySQL, configured in `application/config/database.php`.
- **Session**: PHP sessions via CodeIgniter's session library.

## Examples

- To add a new risk profile:
  - Controller: Add logic in `Profil_risiko.php`.
  - Model: Add DB logic in `Model_risiko.php`.
  - View: Update or create a template in `views/risiko/`.
- To change sidebar for a role, edit the corresponding file in `views/templates/`.

## Conventions

- Use English for code, Bahasa Indonesia for UI labels/messages.
- Organize files by domain and function.
- Use CodeIgniter's helpers and libraries for common tasks.

---

For more details, see `readme.rst` and config files in `application/config/`.
