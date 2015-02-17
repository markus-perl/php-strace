Vagrant.configure("2") do |config|

    # Every Vagrant virtual environment requires a box to build off of.
    config.vm.box = "mex_v5"
    config.vm.box_url = "http://dl.dropbox.com/u/32252351/mex_v5.box"

    config.vm.provider :virtualbox do |vb|
        vb.customize ["modifyvm", :id, "--memory", "512"]
        vb.customize ["modifyvm", :id, "--nestedpaging", "off"]
        # vb.gui = true
    end

    config.vm.synced_folder ".", "/vagrant"

	#Forward a port from the guest to the host, which allows for outside computers to access the VM, whereas host only networking does not.
    config.vm.network :forwarded_port, guest: 80, host: 8080        #php5-fpm

    # Puppet provision
    config.vm.provision :puppet, :module_path => "puppet/modules" do |puppet|
        puppet.manifests_path = "puppet/manifests"
        puppet.manifest_file  = "vm.box.pp"
    end

end
