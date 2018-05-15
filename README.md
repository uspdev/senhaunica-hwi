    composer create-project symfony/skeleton usp


    composer require symfony/orm-pack
    composer require annotations validator template security-bundle make form

    composer require --dev maker-bundle
    composer require server --dev


    no .env: 
    DATABASE_URL=mysql://master:master@localhost:3306/senhaunica

    php bin/console make:controller IndexController


    php bin/console server:run


    {% if app.user %}
        logado
    {% elseif not app.user %}
       não logado
    {% endif %}


    config/routes.yaml

    hwi_oauth_redirect:
        resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
        prefix:   /connect

    hwi_oauth_login:
        resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
        prefix:   /login

    senhaunica_login:
        path:    /senhaunica/callback

    senhaunica_logout:
        path: /senhaunica/logout
