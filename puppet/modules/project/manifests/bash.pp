class project::bash {

	file { "/etc/profile.d/login.sh":
	    owner => root,
	    group => root,
	    mode => 644,
	    content => 'export PS1=\'\[\e[1;32m\][\u@\h \W]\$\[\e[0m\] \'; cd /vagrant'
	}

}
