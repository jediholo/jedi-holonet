# JEDI HoloNet

The **JEDI HoloNet** is the main web site of the [JEDI](https://www.jediholo.net) role-playing clan, based on [Wordpress](https://wordpress.org).

## Development

To make local development easier, you can use [Docker](https://docs.docker.com/engine/install/) and [Docker Compose](https://docs.docker.com/compose/install/):

```
# Pull and start all containers
docker-compose up -d

# Fix permissions
docker-compose exec wordpress bash -c "chown -R www-data:www-data /var/www/html"

# Install Wordpress
docker-compose run --rm wp-cli core install --url=www.dev.jediholo.net --title="JEDI HoloNet" --admin_user=admin --admin_password=admin --admin_email=admin@jediholo.net --skip-email

# Configure Wordpress
docker-compose run --rm wp-cli bash -s < wp-setup.sh
```

Then, point your browser to http://www.dev.jediholo.net and you should see the JEDI HoloNet site.

To stop all containers, run `docker-compose stop`. \
To remove containers, run `docker-compose down`. \
To remove data, delete the `.docker` directory.


## Credits

- **Lead developer:** Fabien Crespel (a.k.a. Soh Raun)
- **Original design:** Jesse Smith (a.k.a. Ctathos Ederoi)
