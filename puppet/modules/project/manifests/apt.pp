
class project::apt {

    exec { "add php5 repo":
        command => "/usr/bin/apt-add-repository ppa:brianmercer/php5"
    }

	exec { "/usr/bin/apt-get update":
	    require => Exec["add php5 repo"]
	}



}
