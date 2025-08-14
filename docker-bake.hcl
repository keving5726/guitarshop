group "default" {
  targets = ["guitarshop-app"]
}

target "guitarshop-build" {
  dockerfile = "docker/guitarshop-build.Dockerfile"
  context = "."
}

target "guitarshop-app" {
  dockerfile = "docker/guitarshop-app.Dockerfile"
  tags = ["keving5726/guitarshop:latest"]
  contexts = {
    build = "target:guitarshop-build"
  }
  context = "."
}
