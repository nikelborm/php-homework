# 1 task php homework

nginx + php + postgres + pg tools

to up execute:

```bash
DOCKER_BUILDKIT=1 COMPOSE_DOCKER_CLI_BUILD=1 docker compose --env-file ./env/dev.env -f ./.devcontainer/docker-compose.dev.yaml up --build --remove-orphans --force-recreate
```

or `Up dev with rebuilding` task in vs code

[Homepage](http://127.0.0.1:8080/)

![screenshot](./Screenshot%20from%202023-02-20%2023-57-56.png)
