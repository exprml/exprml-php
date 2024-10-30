.PHONY: init
init: protobuf testdata ## make init
	php composer.phar update
	php composer.phar dump-autoload

.PHONY: protobuf
protobuf: ## make protobuf
	rm -rf protobuf
	git clone https://github.com/exprml/exprml-api.git protobuf
	cp buf.gen.yaml protobuf/buf.gen.yaml
	rm -rf protobuf/.git
	cd protobuf && buf generate

.PHONY: testdata
testdata: ## make testdata
	rm -rf testdata
	rm -rf exprml-testsuite
	git clone https://github.com/exprml/exprml-testsuite.git
	mv exprml-testsuite/testdata testdata
	rm -rf exprml-testsuite

.PHONY: test
test: ## make test
	vendor/phpunit/phpunit/phpunit tests

.PHONY: docs
docs: ## make docs
	docker run --rm -v "$$(pwd):/data" "phpdoc/phpdoc:3" run \
		--directory=src \
		--directory=build/gen/pb/Exprml/PB \
		--visibility=public \
		--defaultpackagename=Exprml \
		--target=docs/api