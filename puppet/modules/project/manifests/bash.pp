
class project::bash {

	exec { "sb::php locale-gen":
		command => "/usr/sbin/locale-gen de_DE.utf8",
	}

	file { "/etc/profile.d/login.sh":
	    require => Exec["sb::php locale-gen"],
	    owner => root,
	    group => root,
	    mode => 644,
	    content => 'export LC_ALL=de_DE.UTF-8; export PS1=\'\[\e[1;32m\][\u@\h \W]\$\[\e[0m\] \'; cd /vagrant'
	}



}
