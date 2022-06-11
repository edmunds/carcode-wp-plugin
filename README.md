# wordpress-base-plugin
A bare-minimum WordPress plugin using Docker-Compose

## Development
1. Clone this repository
2. Edit `docker-compose.yml` change the volume path from `/base-plugin` to the name of your plugin e.g. `/booking-plugin`
3. Rename `base-plugin.php` to your plugin name, it should match the folder name e.g. `booking-plugin.php`
2. Run docker `docker-compose up -d`
3. Navigate to `localhost:8000`

## Publishing 
1. Shutdown docker with `docker-compose down`
2. Delete the `docker-compose.yml` file
5. Update this README for your plugin

Helpful links:
- https://developer.wordpress.org/plugins/
