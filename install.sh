#!/bin/bash
if [ ! -z "$1" ]; then
    if [ "$1" == '--update' ]; then
        echo "Don't know how to update yet :(";
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

    cat ./config.example.php | sed --quiet "s/cometwpp_plugin_fw_prefix/$name-prefix/g" | sed --quiet "s/cometwpp_plugin_fw/$name/g" > ./config.php &&
    rm -f ./config.example.php &&
    echo "* Config done"

    find ./ -type f -name "*.php" -print0 | xargs -0 sed -i --quiet "s/Cometwpp/$name/g"
    echo "* Namespace done"

    cat ./pl_plugin_name.php | sed --quiet "s/PluginName/$name/g" > ./"$name".php  &&
    rm -f ./pl_plugin_name.php &&
    echo "* Plugin Name done"

    cd .. &&
    mv -T ./Cometwpp ./"$name" &&
    cd `pwd -P` &&
    echo "* Plugin directory has been renamed" &&
    echo "* Install complete"
else echo "Take the plugin name as argument in cli"
fi