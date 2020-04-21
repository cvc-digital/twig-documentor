.PHONY: test
test: test-phpunit test-psalm test-code-style test-composer

.PHONY: test-phpunit
test-phpunit: dependencies
test-phpunit:
	php vendor/bin/phpunit -c ./phpunit.xml ${MAKE_PHPUNIT_OPTIONS}

.PHONY: test-psalm
test-psalm: dependencies
test-psalm:
	php ./vendor/bin/psalm ${MAKE_PSALM_OPTIONS}

.PHONY: test-code-style
test-code-style: dependencies
test-code-style:
	php ./vendor/bin/php-cs-fixer fix --dry-run

.PHONY: test-composer
test-composer: dependencies
test-composer:
	composer normalize --dry-run

.PHONY: fix
fix: dependencies
fix:
	php ./vendor/bin/php-cs-fixer fix
	composer normalize

.PHONY: dependencies
dependencies:
	composer install
