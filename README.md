### FALTA ARRUMAR ESSE README HORRÍVEL, MAS A CLASSE PARA AUTENTICAÇÃO COM SENHA ÚNICA NO SYMFONY ESTÁ OK.

    composer create-project symfony/skeleton usp

        composer require orm-pack annotations validator template security-bundle make form
        composer require hwi/oauth-bundle php-http/guzzle6-adapter php-http/httplug-bundle

        composer require server maker-bundle --dev 

        composer require uspdev/senhaunica-hwi

        no .env: 
        DATABASE_URL=mysql://master:master@localhost:3306/senhaunica

        php bin/console make:controller IndexController


        php bin/console server:run


        {% if app.user %}
            Logado {{ app.user.username }}!<br/>
            {{ dump(app.user) }}
            <a href="{{ url('secured') }}">Protected route</a>
            <a href="{{ logout_url("secured_area") }}">
                <button>Logout</button>
            </a>
        {% else %}
            <h1>Symfony Auth0 Quickstart</h1>
            <a href="/connect/auth0"><button>Login</button></a>
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

    config/packages/hwi_oauth.yaml

        hwi_oauth:
          firewall_names: [secured_area]
          resource_owners:
            senhaunica:
              type: 'oauth1'
              class: 'Uspdev\SenhaUnicaResourceOwner'
              client_id: '%env(SENHAUNICA_ID)%'
              client_secret: '%env(SENHAUNICA_SECRET)%'
              redirect_uri: 'https://localhost:8000/callback'


    config/packages/security.yaml

        security:
            providers:
                hwi:
                    id: hwi_oauth.user.provider

        firewalls:
            secured_area:
                anonymous: ~
                oauth:
                    resource_owners:
                        senhaunica: "/senhaunica/callback"
                    login_path:        /login
                    use_forward:       false
                    failure_path:      /login

                    oauth_user_provider:
                        service: hwi_oauth.user.provider
                logout:
                    path:   /logout
                    target: /

        access_control:
            - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/secured, roles: ROLE_OAUTH_USER }
