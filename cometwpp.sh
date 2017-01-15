#!/bin/bash
#
# This file is part of the Cometwpp package.
# Package URL: https://github.com/shov/Cometwpp
#
# Copyright Alexandr Shevchenko [comet.by] alexandr@comet.by
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.


##
# Header
##

#colors
colorRD='\e[0;31m'
colorYL= '\e[0;33m'
colorGL='\033[1;32m'
colorNC='\033[0m'


# $1 value, $2 msg
errorMessage() {
    if (( $# == 2 )); then
        echo -e "${colorRD}${2} : ${1}${colorNC}" 1>&2
        echo
        exit 1
    fi
}

testNErrMsg() {
    if (( $# == 2 )) && [[ -n "$1" ]]; then
        errorMessage "$1" "$2"
    fi
}

testZErrMsg() {
    if (( $# == 2 )) && [[ -z "$1" ]]; then
        errorMessage "$1" "$2"
    fi
}

# green msg
greenMessage() {
    if [[ -n $1 ]]; then
        echo -e "${colorGL}${1}${colorNC}"
        exit 1
    fi
}

#yes No Question
yNQuestion() {
    local a
    if [[ -n $1 ]]; then
        read -n 1 -p "$1 [y/N]: " a
    else
        read -n 1 a
    fi
    echo ''
    case "$a" in
        y|Y) echo 1
           ;;
        ?) echo ''
           ;;
    esac
    exit 1
}

# echo the app who not installed
require() {
    local commandRes

    while (( $# )); do
        commandRes=$( command -v $1);
        if [[ -z "$commandRes" ]]; then
            echo "$1"
            exit
        else
            shift
        fi
    done
}

# echo the first found (left to right walk) installed app
requireOneOf() {
    local commandRes

    while (( $# )); do
        commandRes=$( command -v $1);
        if [[ -n "$commandRes" ]]; then
            echo "$1"
            exit
        else
            shift
        fi
    done
}

getCurrentDir() {
    local backTo=$( pwd )
    local src="${BASH_src[0]}"
    local path
    while [ -h "$src" ]; do # resolve $src until the file is no longer a symlink
      path="$( cd -P "$( dirname "$src" )" && pwd )"
      src="$(readlink "$src")"
      [[ $src != /* ]] && src="$path/$src" # if $src was a relative symlink, we need to resolve it relative to the path where the symlink file was located
    done
    path="$( cd -P "$( dirname "$src" )" && pwd )"
    cd "$backTo"
    echo "$path"
}

showHelp() {
    cat <<'HELP'
Cometwpp script usage:
/bin/bash cometwpp.sh [options] [NewPluginName]

Warning!
    Place this script in folder where you wanna install
    or into already installed Cometwwp folder

-u
    try to (u)pdate from git repository,
    using git clone or wget+unzip

[-r]  NewPluginName
    try to (r)ename or make "first installation":
    detect current plugin name
    or detect if the plugin has default values and
    change all entries of it (string) in the whole plugin,
    rename the plugin file and the plugin folder

-h --info|help - this text

HELP
exit 0
}

##
# Arguments
##
set -e

bRename=
bUpdate=
newName=

(( $# == 0 )) && showHelp
arrHelpKey=( '--help' '--info' )
[[ " ${arrHelpKey[@]} " =~ " ${1} " ]] && showHelp

OPTIND=1
while getopts ":hur:" opt; do
    case "$opt" in
    h|\?)  showHelp
        ;;
    r)  ;; #make no sence, bRename depend $newName val
    u)  bUpdate=1
        ;;
    :)  newName="$OPTARG"
        ;;
    esac
done
shift $((OPTIND-1))
[ "$1" = "--" ] && shift

if [[ -n "$newName" ]]; then
    bRename=1
fi

##
# Requiring apps
##

methodToGet=$( requireOneOf 'git' 'unzip' )
testZErrMsg "git or unzip" "You should install at least one of applications"

commonRequire="sed mktemp"
requireResult=
if [[ "$methodToGet" == "unzip" ]]; then
    requireResult="${commonRequire} wget"
fi

testNErrMsg "$requireResult" "Not found required application"


##
#   Variables
##
bInstall=

gitRepo='https://github.com/shov/Cometwpp.git'
gitZip='https://github.com/shov/Cometwpp/archive/master.zip'

renameNameSpace='Cometwpp'
renamePluginName='PluginName'
renameConfPrefixRx="\(\$aConfig.*\n[^\]]*'prefix'\s*=>\s*'\)\(.*\)\(',\)"
renameConfNameRx="\(\$aConfig.*\n\s*'name'\s*=>\s*'\)\(.*\)\(',\)"

currDir=$( getCurrentDir )
currPlDirName=${currDir##*/}


if [ -w "${currDir}/${currPlDirName}.php" ]; then
    haveRenamed=currPlDirName
    renameNameSpace=currPlDirName
    renamePluginName=currPlDirName
else
    if ! ( [[ "$renameNameSpace" == "$currPlDirName" ]] && [ -w "${renamePluginName}.php" ] ) ; then
        bInstall=1
    fi
fi

##
#   Install
##
installCometwpp() {
    if [[ "git" == "$methodToGet" ]]; then
        git clone "$gitRepo" &&
        echo "> Git clone is done"

        cd ./Cometwpp

        rm -Rf ./.git/ &&
        rm -f ./.gitignore &&
        echo -e "> Git files has been removed"

        mv ./config.example.php ./config.php
        echo -e "> Config done"
    else
        wget "$gitZip"
        echo "> Download is done"

        unzip "./master.zip"
        echo "> Unzip is done"

        mv ./Cometwpp-master ./Cometwpp
        cd ./Cometwpp
        rm -f ./.gitignore &&
        echo -e "> Git files has been removed"
    fi
}

tmpDir=
if [[ -n "$bInstall" ]]; then
    echo 'Cometwwp not found!'
    echo 'Maybe this script are not in the folder of plugin?'
    if [[ -z $( yNQuestion "Make Install Cometwpp here?" ) ]]; then
        echo 'Ok, nothing to do, bye'
        exit 0
    fi

    if [ -d "./Cometwpp" ]; then
        echo "Can't make install!"
        echo "In the current folder the Cometwpp subfolder already exists!"
        exit 0
    fi

    greenMessage "Start installation!"

    tmpDir=$( mktemp -d )
    cd "$tmpDir"
    echo "> got Temp directory"

    installCometwpp

    cd $currDir
    mkdir ./Cometwpp
    mv "${tmpDir}/Cometwpp/*" ./Cometwpp
    cd ./Cometwpp
    currDir=$( getCurrentDir )
    currPlDirName=${currDir##*/}

    echo "> remove Temp directory"
    rm -Rf "$tmpDir"
    tmpDir=""

    greenMessage "Installation complete!"
fi

##
#   Rename
##
if [[ -n "$bRename" ]]; then

    greenMessage "Start renaming!"

    rx="^([a-zA-Z]{3,})$"
    if ! [[ "$newName" =~ $rx ]] ; then
        errorMessage "$newName" "Is bad name for plugin!"
        exit 0
    fi

    cd $currDir

    cat ./config.php | sed "s!${renameConfPrefixRx}!\1${$newName,,}_prefix\3!g" | sed "s!${renameConfNameRx}!\1${$newName,,}\3!g" > ./config.php
    echo "> Config was updated"

    find ./ -type f -name "*.php" -print0 | xargs -0 sed -i "s/${renameNameSpace}/$newName/g"
    echo "> Namespace was changed"

    cat "./${renamePluginName}.php" | sed "s/${renamePluginName}/${newName}/g" > ./"$newName".php  &&
    rm -f "./${renamePluginName}.php"
    echo "> Plugin Name was changed"

    cd .. &&
    mv -T "./${currPlDirName}" ./"$newName" &&
    cd $( pwd -P )
    echo "> Plugin directory has been renamed"

    currDir=$( getCurrentDir )
    currPlDirName=${currDir##*/}

    greenMessage "Renaming is done!"
fi

##
#   Update
##
if [[ -n "$bUpdate" ]]; then
    if [[ -n "$bInstall" ]]; then
        echo "No need to update! Plugin have been just installed!"
    else
        greenMessage "Start update"
        echo "Update will replace old files in /inc folder of your plugin!"
        if [[ -z $( yNQuestion "Do you wanna continue?" ) ]]; then
            echo 'Update skipped!'
        else
            tmpDir=$( mktemp -d )
            cd "$tmpDir"
            echo "> got Temp directory"

            installCometwpp

            cd "$tmpDir/Cometwpp/inc"
            find ./ -type f -name "*.php" -print0 | xargs -0 sed -i "s/Cometwpp/${currPlDirName}/g"
            echo "> Namespace was changed"
            /bin/cp -rf ./* "$currDir/inc"
            echo "> files have been updated"

            cd "$currDir"

            echo "> remove Temp directory"
            rm -Rf "$tmpDir"
            tmpDir=""

            greenMessage "Update was completed!"
        fi
    fi
fi

greenMessage "All operations successfully completed! Bye!"
exit 0