
class project::composer {

	$downloadUrl = "http://getcomposer.org/composer.phar"
	$targetDir = "/opt/composer"

	file { $targetDir:
	  ensure => directory,
	  owner => "root",
	  group => "root",
	  mode => 0755,
	}

	exec { "project::composer download":
		command => "/usr/bin/wget ${downloadUrl}",
		cwd => $targetDir,
		require => File[$targetDir],
		unless => "/usr/bin/test -f composer.phar"
	}

	file { "${targetDir}/composer.phar":
    	  ensure => "file",
    	  owner => "root",
    	  group => "root",
    	  mode => 0555,
    }

    file { "/usr/bin/composer":
       ensure => 'link',
       target => "${targetDir}/composer.phar",
    }

    exec { "project::composer install":
        command => "/usr/bin/composer install",
        cwd => '/vagrant',
        require => [File["/usr/bin/composer"], Class["project::php"]],
    }

}