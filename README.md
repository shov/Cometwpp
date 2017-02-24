#Cometwpp 
is the Wordpress plugin mini framework
###Requirements
PHP **7+**
<br>
Wordpress 4+
###Install
You can use unix terminal
```bash
wget https://raw.githubusercontent.com/shov/Cometwpp/master/cometwpp.sh
/bin/bash ./cometwpp.sh NewPluginName
```

Also u can do it manually. TODO:

* [Download](https://github.com/shov/Cometwpp/archive/master.zip) or clone
* Unpack
* Replace all entries of the several strings to your new plugin name:
   * in *config.example.php* **'cometwpp_fw'** and **'cometwpp_fw_prefix'**
   * in *PluginName.php* **'PluginName'** (it's class name)
   * in all *.php* files **'Cometwpp'** (it's namespace and package name)
* Rename few files:
   * *config.example.php* to *config.php*
   * *PluginName.php* to your new plugin name
   * this folder (Cometwpp) to your new plugin name

###Update & Rename
Using unix terminal:
```bash
cd public_html/wp-content/plugins/MyPlugin
/bin/bash ./cometwpp.sh -u
/bin/bash ./cometwpp.sh -r MyReNamedPlugin
/bin/bash ./cometwpp.sh -ur MyReNamedAndUpdatedPlugin
```
:+1: