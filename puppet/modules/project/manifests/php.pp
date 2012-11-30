
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

	file { "/usr/bin/php5-spawn":
		require => Package['spawn-fcgi'],
	    owner => root,
	    group => root,
	    mode => 555,
	    source => "/tmp/vagrant-puppet/modules-0/project/templates/php5-spawn.sh"
	}

	exec { "/usr/bin/fromdos /usr/bin/php5-spawn":
		require => [File['/usr/bin/php5-spawn'], Package['tofrodos']],
	}

	exec { "/usr/bin/php5-spawn":
		require => File["/usr/bin/php5-spawn"]
	}

}
