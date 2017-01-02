#Cometwpp 
is the Wordpress plugin mini framework
### Required
PHP **5.6+**
<br>
Wordpress 4+
###Install
You can use unix terminal
```bash
git clone https://github.com/shov/Cometwpp.git
cd ./Cometwpp
/bin/bash ./install.sh NewPluginName
```

Also u can do it manually. TODO:

* [Download](https://github.com/shov/Cometwpp/archive/master.zip) or clone
* Unpack
* Replace all entries of the several strings to your new plugin name:
   * in config.example.php 'cometwpp_fw' and 'cometwpp_fw_prefix'
   * in pl_plugin_name 'PluginName' (it's class name)
   * in all .php files 'Cometwpp' (it's namespace and package name)
* Rename few files:
   * config.example.php to config.php
   * pl_plugin_name.php to your new plugin name
   * this folder (Cometwpp) to your new plugin name

:+1: