
class project::nginx {

  package { "nginx":
    ensure => installed,
    require => Class["project::apt"]
  }

  file { "/etc/nginx/sites-enabled/default":
    require => Package["nginx"],
    owner => root,
    group => root,
    mode => 644,
    source => "puppet:///modules/project/nginx/sites-enabled/default",
    notify => Service["nginx"],
  }

  service { "nginx":
    require => Package["nginx"],
    ensure => running,
  }

}
