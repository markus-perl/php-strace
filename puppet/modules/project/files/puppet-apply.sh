#!/bin/bash

cd /tmp/vagrant-puppet/manifests/ && sudo puppet  apply vm.box.pp --modulepath=../modules-0/