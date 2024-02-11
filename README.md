# JEDI HoloNet

The **JEDI HoloNet** is the main web site of the [JEDI](https://www.jediholo.net) role-playing clan, based on [Wordpress](https://wordpress.org).

## Development

To make local development easier, you can use [Docker](https://docs.docker.com/engine/install/) and [Docker Compose](https://docs.docker.com/compose/install/):

```
# Pull and start all containers
docker compose up -d

# Install and configure Wordpress
docker compose run --rm cli wp-setup.sh
```

Then, point your browser to http://www.dev.jediholo.net and you should see the JEDI HoloNet site.

To stop all containers, run `docker compose stop`. \
To remove containers, run `docker compose down`. \
To remove containers and data, run `docker compose down -v`.


## Credits

- **Lead developer:** Fabien Crespel (a.k.a. Soh Raun)
- **Original design:** Jesse Smith (a.k.a. Ctathos Ederoi)
