Vagrant.configure("2") do |o|
	o.vm.box = "ubuntu_14_04"
	o.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"
	o.vm.synced_folder ".", "/var/www", create:false
	o.vm.network :private_network, ip: "192.168.55.55"
	o.vm.network "forwarded_port", guest: 8000, host: 8000
	o.vm.provision :shell, :path=>"setup.sh"
end
