all: phpunit

phpunit: vendor/.sentinel
	./vendor/bin/phpunit

vendor/.sentinel:
	composer install
	touch vendor/.sentinel
