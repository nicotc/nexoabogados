# Nexoabogados

## Información
- Autor: Nicolás Testagrossa
- Email: nicotestagrossa@gmail.com
- Desarrollado en: Laravel Framework 8.83.8
- Descripción: Prueba técnica  Nexoabogados
## Requerimientos 

- PHP > 7.4.19
- Mysql 
- Redis
- Git
- Composer


## Instalalación

 Clonar el repositorio
    
    git clone git@github.com:nicotc/nexoabogados.git nexoabogados

Ir al directorio

    cd ./nexoabogados

Composer 

    composer install

Copiar .env.example

    cp .env.example .env

Generar  key

    php artisan generate:key

Editar .env | Colocar los datos respectivos  

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nexoabogados
    DB_USERNAME=root
    DB_PASSWORD=

    QUEUE_CONNECTION=redis

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=nico@gmail.com
    MAIL_FROM_NAME="${APP_NAME}"


Ejecutar las migraciones

    php artisan migrate:refresh --seed

Generar key passport

     php artisan passport:client --personal


Ejecutar servicio de colas 

    php artisan queue:work


## Rutas

Interfaz Swagger 

    web /api/v1

Auth
    
    post /api/v1/login
    post /api/v1/register
    post /api/v1/logout

Abogados

    post /api/v1/abogados/create_subscription
    get /api/v1/abogados/cactual_subscription
    put /api/v1/abogados/update_subscription"
    delete /api/v1/abogados/delete_subscription"

Panel

    get /api/v1/panel/list_active_subscription
    get /api/v1/panel/list_inactive_subscription
    get /api/v1/panel/show_subscription/{id}
    post /api/v1/panel/cancel_subscription
    get /api/v1/panel/attempt_subscription/{id_subscription}/{directa?}

## Thunder  

    thunder-collection_nexoabogados.json


## Léeme

🏆 Las rutas de "/api/v1/panel" y de "/api/v1/abogados" se encuentran protegidas por autenticación  Bearer. 

🏆 Los primeros 5 usuarios de la tabla tienen rol "panel", el resto rol "abogado". Todos los usuarios generados por el seeder tienen como contraseña "password"

🏆 Los usuarios tienen roles, por lo que un usuario abogado no puede entrar en panel y uno panel no puede entrar en abogado

🏆 Los tipos de planes son ["premium", "basic"]

---

---


