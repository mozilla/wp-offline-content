.PHONY: reinstall test

WP_CLI = tools/wp-cli.phar
PHPUNIT = tools/phpunit.phar

reinstall: $(WP_CLI)
	$(WP_CLI) plugin uninstall --deactivate wp-offline-content --path=$(WORDPRESS_PATH)
	rm -f wp-offline-content.zip
	zip wp-offline-content.zip -r wp-offline-content/
	$(WP_CLI) plugin install --activate wp-offline-content.zip --path=$(WORDPRESS_PATH)

test: $(PHPUNIT)
	$(PHPUNIT)

test-sw: node_modules
	$(NODE) node_modules/karma/bin/karma start karma.conf

node_modules:
	npm install

tools/wp-cli.phar:
	mkdir -p tools
	wget -P tools -N https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
	chmod +x $(WP_CLI)

tools/phpunit.phar:
	mkdir -p tools
	wget -P tools -N https://phar.phpunit.de/phpunit-old.phar
	mv tools/phpunit-old.phar tools/phpunit.phar
	chmod +x $(PHPUNIT)
