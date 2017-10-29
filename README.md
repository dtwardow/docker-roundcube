# Roundcube WebMail #

A **Roundcube WebMail** client based on Apache Web-Server with PHP 5.
The intention is to provide an auto-configured WebMail-Client to connect to other containers. Of course, it's also supported to connect to external servers.

NOTE: On purpose, there is no secured channel (TLS/SSL), because this service should never be exposed to the internet directly.
Link it to a reverse-proxy (linke "NGINX") to provide access from external.


## Requirements ##

- Docker (>= 1.9.0)

## Provided Resouces ##

The service provides the following network ports and filesystems.

### Exposed Ports ###

- `80` : Web Service

### Exposed Filesystems ###

None


## Usage ##

The created container is configured automatically by the `entrypoint`-script during the **each** run.
Only the database is setup during the first run, only!

The following environment variables **must** be provided:

### IMAP Server (**required**) ###

IMAP host which is used for user's login.

  - `IMAP_PROTOCOL` (default: empty)
    - *empty* : insecure connection
    - `tls` : Secure TLS communication
    - `ssl` : Secure SSL communication
  - `IMAP_HOST` (**required** | default: mail)
    - Doesn't need to be changed, if linked to a container with either hostname or alias named `mail`.
  - `IMAP_PORT` (**required** | default: 143)

#### Linking a Mail-Server ####

When a mail-server contiaer is linked with the alias `mail`, the HOST and PORT parameters can be omitted.

### SMTP Server (**required**) ###

SMTP host used for sending mails.

  - `SMTP_PROTOCOL` (default: empty)
    - *empty* : insecure connection
    - `tls` : Secure TLS communication
    - `ssl` : Secure SSL communication
  - `SMTP_HOST` (**required** | default: mail)
    - Doesn't need to be changed, if linked to a container with either hostname or alias named `mail`.
  - `SMTP_PORT` (default: 25)

#### Linking a Mail-Server ####

When a mail-server contiaer is linked with the alias `mail`, the HOST and PORT parameters can be omitted.

### Sieve Plugin (**optional**) ###

If provided, the **managesieve** plugin is activated to allow creation of folder-specific rules.

  - `SIEVE_PROTOCOL` (default: empty)
    - *empty* : insecure connection
    - `tls` : Secure TLS communication
    - `ssl` : Secure SSL communication
  - `SIEVE_HOST` (default: mail)
    - Doesn't need to be changed, if linked to a container with either hostname or alias named `mail`.
  - `SIEVE_PORT` (default: 4190)

### Database Connection (**optional**) ###

Dataase used for Roundcubes Meta-Data.

  - `DB_TYPE` (default: sqlite)
    - Supported Database Backends: `mysql`, `postgresql`, `sqlite`
    - If not provided or left at the default value, `sqlite` is used database backend.
  - `DB_NAME` (default: empty)
    - Required for `DB_TYPE`s `mysql` or `postgresql`
  - `DB_USER` (default: empty)
    - Required for `DB_TYPE`s `mysql` or `postgresql`
  - `DB_PASS` (default: empty)
    - Required for `DB_TYPE`s `mysql` or `postgresql`

#### Linking a Database Container ####

This image supports the official MySQL und PostgreSQL images. They have to be linked using the appropriate link-alias.

- MySQL 
  - Alias: `mysql`
  - Supported Images:
    - [mysql (official)](https://hub.docker.com/_/mysql/´)
    - [sameersbn/mysql](https://hub.docker.com/r/sameersbn/mysql/)
- PostgreSQL
  - Alias: `postgresql`
  - Supported Images:
    - [postgres (official)](https://hub.docker.com/_/postgres/´)
    - [sameersbn/postgresql](https://hub.docker.com/r/sameersbn/postgresql/)

#### Usage of database backend `sqlite` ####

The `sqlite` database is stored in the local filesystem.
Therefore, the data directory `/data` should be linked to the local filesystem.

This is done with the option `-v /local/data/dir:/data` when starting the container..

### Misc. Parameters ###

  - `ENC_KEY` (default: a 24 digit secret)
  - `UI_LANG` (default: de_DE)
  - `DEBUG_LEVEL` (default: 1)
  - `TIMEZONE` (default: "UTC")

## Startup ##

The container can be started directly (in background) by the following command-line:

```bash
 docker run --name roundcube -d \
            -p 8080:80 \
            -e SMTP_HOST=mail \
            -e SMTP_PORT=25 \
            -e SMTP_PROTOCOL=tls \
            -e IMAP_HOST=mail \
            -e IMAP_PORT=143 \
            -e IMAP_PROTOCOL=tls \
            -e SIEVE_HOST=mail \
            -e SIEVE_PORT=4190 \
            -e SIEVE_PROTOCOL=tls \
            -e DB_TYPE=postgresql \
            -e DB_HOST=database \
            -e DB_NAME=mailserver \
            -e DB_USER=user \
            -e DB_PASS=password \
            dtwardow/roundcube
```

This solution assumes a running mail-server and database backend.

Alternatively it can be started in a `docker-compose` context with the following configuration parameters (Roundcube is located in the `webmail` container):

```yaml
version: '2'

services:
  service:
    image: bboehmke/isp-mail:latest
    volumes:
      - ./mail:/data:rw
      - /srv/data/certs:/data/ssl:ro
    ports:
      - "25:25"
      - "465:465"
      - "110:110"
      - "995:995"
      - "143:143"
      - "993:993"
      - "4190:4190"
    environment:
      - MAIL_SERVER_HOSTNAME=mail.example.com
      - SSL_CERT=domain.crt
      - SSL_KEY=domain.key
    links:
      - database:postgresql
    network_mode: bridge

  database:
    image: postgres:latest
    volumes:
      - ./database:/var/lib/postgresql/data:rw
    environment:
      - POSTGRES_DB=mailsrv
      - POSTGRES_USER=mail
      - POSTGRES_PASSWORD=ma1l%server
    network_mode: bridge

  webmail:
    image: dtwardow/roundcube
    links:
      - service:mail
    environment:
      - IMAP_PROTOCOL=tls
      - SMTP_PROTOCOL=tls
      - SIEVE_PROTOCOL=tls
    network_mode: bridge
```

