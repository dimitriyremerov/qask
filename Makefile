IMAGE_NAME := qask.ru
CONTAINER_NAME := qask.ru

default: update

update: build stop start

build:
	docker build -t $(IMAGE_NAME) .

start:
	docker run -d --name=$(CONTAINER_NAME) -p 127.47.7.1:9000:9000 $(IMAGE_NAME)

stop:
	docker stop $(CONTAINER_NAME) || true
	docker rm $(CONTAINER_NAME) || true

