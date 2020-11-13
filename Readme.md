### How to install the project
* `bash setup_env.sh dev` - to setup .env.local docker/.env
* `make dc_up` - docker-compose up 
* `make setup_dev` - composer install, migrations and so on
* `make seed` - seeding sb
* `make create_user` - create a user
* `http://127.0.0.1:888/api/doc` `https://127.0.0.1:444/api/doc` - api doc

### Container
`make dev` - jump into workplace container

### CI
```
make dev
//in container execute
make analyze
```
