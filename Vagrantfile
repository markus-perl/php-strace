Vagrant::Config.run do |config|

    # Every Vagrant virtual environment requires a box to build off of.
    config.vm.box = "mex_v2"
    config.vm.box_url = "http://dl.dropbox.com/u/32252351/mex_v2.box"

    config.ssh.max_tries = 300

    config.vm.customize ["modifyvm", :id, "--memory", "512"]
    config.vm.customize ["modifyvm", :id, "--nestedpaging", "off"]

    # Boot with a GUI so you can see the screen. (Default is headless)
    # config.vm.boot_mode = :gui

    # nfs Switch Mac OS-X and Linux
    if RUBY_PLATFORM.downcase.include?("darwin") or RUBY_PLATFORM.downcase.include?("linux")
  	    config.vm.network :hostonly, "33.33.33.10"
  	    config.vm.share_folder("v-root", "/vagrant", ".")
    end

	#Forward a port from the guest to the host, which allows for outside computers to access the VM, whereas host only networking does not.
	config.vm.forward_port 80, 8080 #php5-cgi
	config.vm.forward_port 81, 8081 #php-fpm

    # Puppet provision
    config.vm.provision :puppet, :module_path => "puppet/modules" do |puppet|
        puppet.manifests_path = "puppet/manifests"
        puppet.manifest_file  = "vm.box.pp"
    end

end
