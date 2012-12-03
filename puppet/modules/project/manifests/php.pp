
class project::php {

	package { 'php5-cgi':
		require => Class['project::apt'],
		ensure => installed,
	}

	package { 'php5-cli':
		require => Class['project::apt'],
		ensure => installed,
	}

	package { 'spawn-fcgi':
		require => Package['php5-cgi'],
		ensure => installed,
	}

	package { 'php5-fpm':
    		require => Package['php5-cgi'],
    		ensure => installed,
    }

	file { "/usr/bin/php5-spawn":
		require => Package['spawn-fcgi'],
	    owner => root,
	    group => root,
	    mode => 555,
	    source => "/tmp/vagrant-puppet/modules-0/project/files/php5-spawn.sh"
	}


	file { "/etc/php5/fpm/pool.d/www.conf":
		require => Package['php5-fpm'],
	    owner => root,
	    group => root,
	    mode => 555,
	    source => "/tmp/vagrant-puppet/modules-0/project/files/php/fpm/www.conf"
	}


	exec { "/usr/bin/fromdos /usr/bin/php5-spawn":
		require => [File['/usr/bin/php5-spawn'], Package['tofrodos']],
	}

	exec { "/usr/bin/php5-spawn":
		require => File["/usr/bin/php5-spawn"]
	}

	service { "php5-fpm":
	    ensure     => running,
        enable     => true,
        subscribe  => File["/etc/php5/fpm/pool.d/www.conf"],
	}

}
