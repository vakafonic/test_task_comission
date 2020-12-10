SHELL := /bin/bash

help:
	@echo -e "Targets\n"\
	"- test-unit: Run unit tests.\n"\
	"- test-functional: Run functional tests.\n"

test-unit:
	@docker-compose up -d && docker-compose run --rm comission ./vendor/bin/phpunit --testsuite Unit

test-functional:
	@docker-compose up -d && docker-compose run --rm comission ./vendor/bin/phpunit --testsuite Functional