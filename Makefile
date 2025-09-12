install:
	composer require --dev orchestra/testbench && composer dump-autoload -o

dump-autoload:
	composer dump-autoload -o


unit-test:
# 	./vendor/bin/phpunit
#	./vendor/bin/phpunit --group live --display-deprecations
	./vendor/bin/phpunit --group live --display-deprecations --do-not-cache-result

