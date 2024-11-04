#!/usr/bin/env bash

set -e

if [ -z "${ECR_PREFIX}" ] ; then
  echo "ENV: ECR_PREFIX is missing!"
  exit 1
fi

if [ -z "${APP_NAME}" ] ; then
  echo "ENV: APP_NAME is missing!"
  exit 1
fi

if [ -z "${DISCORD_WEBHOOK_URL}" ] ; then
  echo "ENV: DISCORD_WEBHOOK_URL is missing!"
  exit 1
fi

if [ -z "${DEPLOY_LOGIN}" ] ; then
  echo "ENV: DEPLOY_LOGIN is missing!"
  exit 1
fi

if [ -z "${DEPLOY_HOST}" ] ; then
  echo "ENV: DEPLOY_HOST is missing!"
  exit 1
fi

rm -f scripts/build.sh
rm -f scripts/push.sh
rm -f scripts/deploy.sh
rm -f docker-compose.yml

cp docker-compose.yml.dist docker-compose.yml
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" docker-compose.yml

cp scripts/build.sh.dist scripts/build.sh
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/build.sh

cp scripts/deploy.sh.dist scripts/deploy.sh
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/deploy.sh
sed -i "s|app@1.1.1.1|${DEPLOY_LOGIN}|" scripts/deploy.sh
sed -i "s|stuff.prod.google.com|${DEPLOY_HOST}|" scripts/deploy.sh

cp scripts/push.sh.dist scripts/push.sh
sed -i "s|xxx.dkr.ecr.eu-central-1.amazonaws.com|${ECR_PREFIX}|" scripts/push.sh
