# Basic Puppet Apache manifest

class vm {

  group { "puppet":
     ensure => "present",
   }

   File { owner => 0, group => 0, mode => 0644 }

   file { '/etc/motd':
     content => "Welcome to your Vagrant-built virtual machine!
                 Managed by Puppet.\n"
   }

  class { 'project': }
  class { 'composer': }


}

include vm
