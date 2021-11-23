#!/bin/bash
SERVICE_NAME=$(basename "$PWD")

case "$1" in
## Build command
  build)
    if docker images -a | grep -q "$SERVICE_NAME".dev; then
      echo "Image already exists";exit 0;
    else
      echo "Build image $SERVICE_NAME"
    fi
    docker build --force-rm --target development -t "$SERVICE_NAME".dev .
    ;;
## Run command
  run)
    if docker ps -a | grep -q "$SERVICE_NAME".dev; then
      echo "Container already exists, it will be removed";
      docker rm -f -v "$SERVICE_NAME".dev
    else
      echo "Start container $SERVICE_NAME"
    fi
    if [[ "$PORT" -eq 0 ]]; then
      PORT=2000
      while lsof -i -P -n | grep -q :"$PORT"; do
        ((++PORT));
      done
    fi

    if [[ "$OSTYPE" == "darwin"* ]]; then
      docker volume create --name="$SERVICE_NAME-sync"
      docker-sync start
    fi

    sed -e "s/{{SERVER_NAME}}/${SERVICE_NAME}/g" \
        < .env \
        > .env.local

    VOLUME_MAIN="$(pwd):/var/www/file-service::rw"

    if [[ "$OSTYPE" == "darwin"* ]]; then
      VOLUME_MAIN="$SERVICE_NAME-sync:/var/www/file-service:nocopy"
    fi

    docker run -i -t -d --rm \
      --name "$SERVICE_NAME".dev \
      -v "$VOLUME_MAIN" \
      -p "$PORT":80 \
      --env-file .env.local \
      "$SERVICE_NAME".dev \
      sh -c "usermod -u $UID www-data && chown -R www-data:www-data /var/www && php -S 0.0.0.0:80 public/index.php"
    echo "0.0.0.0:$PORT"
    ;;
#  Stop command (remove container)
  stop)
    docker stop "$SERVICE_NAME".dev
    if [[ "$OSTYPE" == "darwin"* ]]; then
      docker-sync stop
    fi
    ;;
#  Remove container command
  remove)
    docker rm -f -v "$SERVICE_NAME".dev
    docker volume rm -f "$SERVICE_NAME".dev

    if [[ "$OSTYPE" == "darwin"* ]]; then
      docker-sync clean
    fi
    ;;
#  Remove container command
  remove-build)
    docker image rm -f "$SERVICE_NAME".dev
    ;;
#  Enter to container
  exec)
    docker exec -it "$SERVICE_NAME".dev su --shell=/bin/bash www-data
    ;;
  *) ;;
esac

