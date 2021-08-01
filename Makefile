IMAGE_PHPTEST = fastwf/php-test

test-prepare: docker/Dockerfile.test
	docker build -t "$(IMAGE_PHPTEST)" - < docker/Dockerfile.test

test:
	docker run -v $(PWD):/app -w /app --rm "$(IMAGE_PHPTEST):latest" ./vendor/bin/phpunit tests \
		--coverage-html build/cov-html --stderr

documentation:
	docker run --rm -v $(PWD):/data phpdoc/phpdoc:3 run --directory=/data/
