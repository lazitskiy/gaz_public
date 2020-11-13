#!/usr/bin/env bash

if [ -z "$1" ]
then
  echo 'Enter the environment by first argument [dev, prod, test]'
  exit 1
fi

get_os(){
  unameOut="$(uname -s)"
  case "${unameOut}" in
      Linux*)     machine=Linux;;
      Darwin*)    machine=Mac;;
      CYGWIN*)    machine=Cygwin;;
      MINGW*)     machine=MinGw;;
      *)          machine="UNKNOWN:${unameOut}"
  esac
  echo ${machine}
}

#docker
PUID=$(id -u)
PGID=$(id -g)
DB_ROOT_PASSWORD=$(openssl rand -hex 8)
DB_PASSWORD=$(openssl rand -hex 8)

if [ "$(get_os)" == "Mac" ] ; then
  SED="gsed";
else
  SED="sed";
fi

#app
APP_ENV=dev
APP_SECRET=$(openssl rand -hex 16)
DATABASE_URL="mysql:\/\/book:${DB_PASSWORD}@db:3306\/book?serverVersion=8.0"

#docker
cp ./docker/.env.dist ./docker/.env && \
#$SED -i "s/^PUID=.*/PUID=${PUID}/g" ./docker/.env && \
#$SED -i "s/^PGID=.*/PGID=${PGID}/g" ./docker/.env && \
$SED -i "s/^DB_ROOT_PASSWORD=.*/DB_ROOT_PASSWORD=${DB_ROOT_PASSWORD}/g" ./docker/.env && \
$SED -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/g" ./docker/.env

#application
cp .env .env.local && \
$SED -i "s/^APP_ENV=.*/APP_ENV=${APP_ENV}/g" .env.local && \
$SED -i "s/^APP_SECRET=.*/APP_SECRET=${APP_SECRET}/g" .env.local && \
$SED -i "s/^DATABASE_URL=.*/DATABASE_URL=${DATABASE_URL}/g" .env.local

#jwt keys
mkdir -p config/jwt && \
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 && \
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

echo -n "Enter PEM password to save in .env.local: "
read -s JWT_PASSPHRASE
$SED -i "s/^JWT_PASSPHRASE=.*/JWT_PASSPHRASE=${JWT_PASSPHRASE}/g" .env.local
