#!/bin/bash

GL='\033[1;32m'
NC='\033[0m' # No Color

if [ ! -z "$1" ]; then
    if [ "$1" == '--update' ]; then
        echo -e "${GL}Don't know how to update yet :(";
        exit
    fi

    name=$1
    rx="^([a-zA-Z]{3,})$"
    if ! [[ "$name" =~ $rx ]] ; then
        echo -e "${GL}Bad name, use letters in the range [A-Za-z]!"
        exit
    fi

    rm -Rf ./.git/ &&
    rm -f ./.gitignore &&
    echo -e "${NC}> Git files has been removed"

    cat ./config.example.php | sed "s/cometwpp_plugin_fw_prefix/${name}_prefix/g" | sed "s/cometwpp_plugin_fw/$name/g" > ./config.php &&
    rm -f ./config.example.php &&
    echo -e "${GL}* Config done"

    find ./ -type f -name "*.php" -print0 | xargs -0 sed -i "s/Cometwpp/$name/g"
    echo -e "${GL}* Namespace done"

    cat ./pl_plugin_name.php | sed "s/PluginName/$name/g" > ./"$name".php  &&
    rm -f ./pl_plugin_name.php &&
    echo -e "${GL}* Plugin Name done"

    cd .. &&
    mv -T ./Cometwpp ./"$name" &&
    cd `pwd -P` &&
    echo -e "${NC}> Plugin directory has been renamed" &&
    echo -e "${GL}* Install complete"
else echo -e "${GL}Take the plugin name as argument in cli"
fi
echo -e "${NC}\n";