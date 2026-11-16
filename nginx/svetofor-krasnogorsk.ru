
server {
    listen 80;
    listen [::]:80;

    server_name ~^svetofor.*\.ru$;

    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;

    server_name ~^svetofor.*\.ru$;

    root /var/www/html/sites/public;
    index index.php index.html;

    charset utf-8;

    ssl_certificate /etc/letsencrypt/live/svetofor-krasnogorsk.ru/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/svetofor-krasnogorsk.ru/privkey.pem;

    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
