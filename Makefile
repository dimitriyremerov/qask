IMAGE_NAME := qask.ru
CONTAINER_NAME := qask.ru

default: update

update: build stop start

build:
	docker build -t manager manager/
	docker build -t $(IMAGE_NAME) .

start:
	docker run -d --name=$(CONTAINER_NAME) --network manager-cluster $(IMAGE_NAME)
	docker run -it --name=manager --network manager-cluster -p 127.20.0.1:80:80 manager

stop:
	docker stop $(CONTAINER_NAME) || true
	docker rm $(CONTAINER_NAME) || true
	docker stop manager || true
	docker rm manager || true
