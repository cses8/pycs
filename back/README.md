# Deployment Configuration Notes

This document outlines specific configuration steps required for deploying applications in different environments, particularly focusing on Hostinger shared hosting for Laravel and proxy setups for static sites.

## 1. Deploying Laravel on Hostinger Shared Hosting

Hostinger's shared hosting environment requires a couple of manual adjustments for standard Laravel deployments.

### 1.1. Fixing the Storage Link

**Problem:** The standard `php artisan storage:link` command often fails on Hostinger shared hosting due to permission restrictions or how symbolic links are handled. This prevents publicly accessible files stored in `storage/app/public` from being served correctly.

**Solution:** Manually create the symbolic link via SSH access.

1.  Connect to your Hostinger account via SSH.
2.  Navigate to your Laravel project's root directory (e.g., `cd public_html` if you deployed there).
3.  Run the following command:

    ```bash
    ln -s $(pwd)/storage/app/public $(pwd)/public/storage
    ```

    * `$(pwd)` ensures the command uses the full, absolute paths based on your current directory.
    * This creates a symbolic link named `storage` inside your `public` directory, pointing correctly to the `storage/app/public` directory.

### 1.2. Fixing Root Directory 404 Errors

**Problem:** By default, Hostinger's web server points to the `public_html` directory as the document root. However, Laravel serves its application from its `/public` subdirectory. If you deploy your Laravel project directly into `public_html`, accessing your site will result in 404 errors because the server is looking for an index file in `public_html` instead of `public_html/public`.

**Solution:** Add an `.htaccess` file to your Laravel project's root directory (`public_html` in this example) to rewrite all requests to the `/public` subdirectory.

1.  Create or edit the `.htaccess` file in your Laravel project's root directory (e.g., `/home/user/public_html/.htaccess`).
2.  Add the following content:

    ```htaccess
    <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteRule ^(.*)$ public/$1 [L]
    </IfModule>
    ```

    * This rule takes any request (`^(.*)$`) and internally rewrites it to be handled by the corresponding path inside the `public` directory (`public/$1`), effectively making the `public` directory the functional document root.
    * `[L]` ensures this is the last rule processed for the request in this file.