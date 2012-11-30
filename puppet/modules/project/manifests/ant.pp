class project::ant {

	package { "ant":
		ensure => installed,
		require => Class["project::apt"]
	}
}