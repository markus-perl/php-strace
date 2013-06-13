
class project::apt {

    exec { "add php5 repo nginx":
        command => "/usr/bin/apt-add-repository ppa:brianmercer/nginx"
    }

    exec { "add php5 repo php":
        command => "/usr/bin/apt-add-repository ppa:brianmercer/php"
    }

	exec { "/usr/bin/apt-get update":
	    require => [
	            Exec["add php5 repo nginx"],
	            Exec["add php5 repo php"],
	        ]
	}



}
