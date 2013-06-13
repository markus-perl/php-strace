
class project::nginx {

	package { 'nginx':
		ensure => installed,
		require => Class['project::apt']
	}

	file { "/etc/nginx/fastcgi.conf":
		require => Package['spawn-fcgi'],
	    owner => root,
	    group => root,
	    mode => 644,
	    source => "puppet:///modules/project/nginx/fastcgi.conf"
	}

	file { "/etc/nginx/nginx.conf":
		require => Package['spawn-fcgi'],
	    owner => root,
	    group => root,
	    mode => 644,
	    source => "puppet:///modules/project/nginx/nginx.conf"
	}

	file { "/etc/nginx/sites-enabled/default":
		require => File['/etc/nginx/fastcgi.conf'],
	    owner => root,
	    group => root,
	    mode => 644,
	    source => "puppet:///modules/project/nginx/sites-enabled/default",
	}

	exec { "/etc/init.d/nginx restart":
		require => [File['/etc/nginx/sites-enabled/default']],
	}


}
