#!/bin/bash
if [ ! -z "$1" ]; then
    if [ "$1" == '--update' ]; then
        echo "Dont't know how to update yet :(";
        exit
    fi

    name=$1
    rx="^([a-zA-Z]{3,})$"
    if ! [[ "$name" =~ $rx ]] ; then
        echo "Bad name, use letters in the range [A-Za-z]!"
        exit
    fi

    rm -Rf ./.git/ &&
    rm -f ./.gitignore &&
    echo "Git files has been removed"

    sed "s/cometwpp_plugin_fw/$name/g" ./config.example.php &&
    sed "s/cometwpp_plugin_fw_prefix/$name-prefix/g" ./config.example.php &&
    mv ./config.example.php ./config.php &&
    echo "* Config done"

    find ./ -type f -name "*.php" -print0 | xargs -0 sed -i "s/Cometwpp/$name/g"
    echo "* Namespace done"

    sed "s/PluginName/$name/g" ./pl_plugin_name.php &&
    mv ./pl_plugin_name.php ./"$name".php &&
    echo "* Plugin Name done"

    cd .. &&
    mv -T ./Cometwpp ./"$name" &&
    cd ./"$name" &&
    echo "* Plugin directory has been renamed" &&
    echo "* Install complete"
else echo "Take the plugin name as argument in cli"
fi