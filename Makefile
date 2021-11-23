build:
	@./docker-control.sh build
up: build
	@./docker-control.sh run
stop:
	@./docker-control.sh stop
rm: stop
	@./docker-control.sh remove
clear: rm
	@./docker-control.sh remove-build
exec:
	@./docker-control.sh exec
