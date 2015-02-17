class project::strace {

	package { "strace":
		ensure => installed,
		require => Class["project::apt"]
	}

}