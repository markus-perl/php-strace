
class project::puppet {

	package { 'tofrodos':
		ensure => installed,
		require => Class['project::apt']
	}

	package { "unzip":
		ensure => installed,
		require => Class["apt"]
	}

	file { "/usr/bin/puppet-apply":
	    owner => root,
	    group => root,
	    mode => 555,
	    source => "puppet:///modules/project/puppet-apply.sh"
	}

	exec { "/usr/bin/fromdos /usr/bin/puppet-apply":
		require => [File['/usr/bin/puppet-apply'], Package['tofrodos']],
	}

}
