dump-autoload-install:
	composer dump-autoload -o && composer require --dev orchestra/testbench

dump-autoload:
	composer dump-autoload -o


unit-test:
# 	./vendor/bin/phpunit
	./vendor/bin/phpunit --group live
