#!/bin/bash

set -e

# install deep-diff-yml
npm i -g git://github.com/shadiakiki1986/deep-diff-yml-cli.git

# config diff for yml in git to use deep-diff-yml
git config --global diff.yml_diff.command "deep-diff-yml"
mkdir -p ~/.config/git
# grep -v yml_diff ~/.config/git/attributes
echo -e '*.yml diff=yml_diff' >> ~/.config/git/attributes

# prepare git-rest-api to use one workdir
# https://github.com/shadiakiki1986/docker-node-git-rest-api/blob/master/Dockerfile
mkdir -p /tmp/git/1161017-23323-pfc5zt
npm i git://github.com/shadiakiki1986/docker-node-git-rest-api.git
