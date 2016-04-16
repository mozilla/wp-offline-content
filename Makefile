.PHONY: reinstall test test-sw svn

PLUGIN_DIR = wp-offline-content

WP_CLI = tools/wp-cli.phar
PHPUNIT = tools/phpunit.phar
PLUGIN_ZIP = $(PLUGIN_DIR).zip

reinstall: $(WP_CLI)
	$(WP_CLI) plugin uninstall --deactivate $(PLUGIN_DIR) --path=$(WORDPRESS_PATH)
	rm -f $(PLUGIN_ZIP)
	zip $(PLUGIN_ZIP) -r $(PLUGIN_DIR)
	$(WP_CLI) plugin install --activate $(PLUGIN_ZIP) --path=$(WORDPRESS_PATH)

svn:
	@echo "Copying $(PLUGIN_DIR) contents to svn/trunk"
	@rsync -a --delete $(PLUGIN_DIR)/* svn/trunk
	@echo "Removing .git repositories from bundle"
	@find svn/trunk \( -name ".git" \) -prune -exec rm -rf {} \;

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
