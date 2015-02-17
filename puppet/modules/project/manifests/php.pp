class project::php {

	package { "php5-cli":
		require => Class["project::apt"],
		ensure => installed,
	}

	package { "php5-fpm":
		require => Class["project::apt"],
		ensure => installed,
	}

	service { "php5-fpm":
		require => Package["php5-fpm"],
		ensure => running,
	}

}
