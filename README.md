## MY TASKS SERVER

Proyecto backend creado con laravel 8 en el que se trabajó para satisfacer los requerimientos de la prueba técnica.

## REQUERIMIENTOS TÉCNICOS

`1. PHP >= 7.4`

`2. MariaDB 10.3.27`

`3. Composer`

## PUESTA EN MARCHA

`1. https://github.com/Adrian-Vergara/my-tasks-server.git`

`2. cd my-tasks-server`

`3. cp .env.example .env`

`4. composer install`

`5. crear la base de datos y agregar la cadena de conexión en el fichero .env`

`6. debe agregar las credenciales de algún servidor de correo. En mi caso utilicé el smtp de SendGrid que tiene mis datos personales, pero no lo dejé en el .env-example por temas de seguridad y privacidad.`

`7. Ejecutar los siguientes comandos:`

`php artisan key:generate`

`php artisan migrate --step`

`php artisan passport:install`

`php artisan config:clear`

`php artisan cache:clear`

`php artisan view:clear`

`php artisan route:clear`

`chmod -R 775 storage/`

`composer dump-autoload`

`php artisan db:seed`

`**NOTA:** Al ejecutar los sedeers se crearán los roles Administrador y Operador. Adicionalmente se registrará un usuario de tipo Administrador con mis datos. Lo recomendable es que edite el seeder database/seeders/UserSeeder.php y modifique los demás datos`

`8.  Las apis se documentaron con Postman. Así que solo debe importar el fichero que se encuentra ubicado en la raíz del proyecto MyTasks.postman_collection.json`

`9.  Para levantar el servidor en desarrollo ejecute el comando php artisan serve`
