
class project {
	class { 'project::bash': }
	class { 'project::apt': }
	class { 'project::puppet': }
	class { 'project::php': }
	class { 'project::nginx': }
	class { 'project::composer': }
	class { 'project::ant': }
}
