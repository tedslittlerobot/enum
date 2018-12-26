
test:
	./vendor/bin/phpunit

coverage:
	./vendor/bin/phpunit --coverage-html ./report

report: coverage
	open ./report/index.html

lint:
	./vendor/bin/phpcs

lint-fix:
	./vendor/bin/phpcbf

clean:
	rm -rf report
