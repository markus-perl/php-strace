class project {

	class { 'project::bash': }
	class { 'project::apt': }
	class { 'project::ant': }
	class { 'project::php': }
	class { 'project::strace': }
	class { 'project::nginx': }

}
