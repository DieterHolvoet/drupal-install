assets:=$(shell find resources -type f)
.PHONY: clean build

resources/dist: package.json yarn.lock $(assets)
	$(MAKE) node_modules
	npm run build
	touch resources/dist

node_modules: package.json yarn.lock
	npm install
	npm rebuild node-sass
	touch node_modules

clean:
	@- rm -rf node_modules
