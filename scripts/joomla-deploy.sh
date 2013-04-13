#!/bin/bash

# Copyright © 2012 Techjoomla
# Licensed under GNU General Public License v2.0

#Example usage
EXAMPLE_USAGE="./joomla-deploy.sh <deployment version> <old version> <environment>"
EXAMPLE_VALUES="./joomla-deploy.sh 0.8.3 0.8.2 alpha"

# CHeck if we have all info
# if no arg passed $# will be 0
if [ $# -eq 0 ]
then
	echo "Usage: $EXAMPLE_USAGE"
	echo "Example Syntax: $EXAMPLE_VALUES"
	exit;
fi

# Check if deployment versions are provided
if [ ! $1 ]; then
	echo "Please provide deployment version."
	echo "Example Usage $EXAMPLE_USAGE"
	exit;
fi

if [ ! $2 ]; then
	echo "Please provide the deployment version to move the image files & zoo config files from"
	echo "Example Usage $EXAMPLE_USAGE"
	exit;
fi

if [ ! $3 ]; then
	echo "Please provide environment"
	echo "Example Usage $EXAMPLE_USAGE"
	exit;
fi

# Var defs
# When updating script, make sure to use absolute paths so that the script is location agnostic
GIT_PATH=/var/www/deploy-git
DEPLOYMENT_BASE=/var/www/alpha
DEPLOYMENT_NEW=$DEPLOYMENT_BASE"-"$1
DEPLOYMENT_OLD=$DEPLOYMENT_BASE"-"$2
CONFIG_LOCATION=/var/www/site-assets/configuration.php
CONFIG_NEW=$DEPLOYMENT_NEW"/configuration.php"
IMAGES_NEW=$DEPLOYMENT_NEW"/images"
IMAGES_OLD=$DEPLOYMENT_OLD"/images/*"
CACHE_NEW=$DEPLOYMENT_NEW"/cache"
ZOO_NEW=$DEPLOYMENT_NEW"/media/zoo/applications/blog/types"
ZOO_OLD=$DEPLOYMENT_OLD"/media/zoo/applications/blog/types/*"

#Root assets location -- htaccess files etc
ROOT_ASSETS=/var/vhosts/example.com/root-assets

# Don't modify anything beyond this point unless you know what you're doing

if [ ! -d "$DEPLOYMENT_OLD" ]; then
    echo "ERROR: Old deployment version ($DEPLOYMENT_OLD) does not exist. Aborting."
    exit
fi

#Step 1
cd $GIT_PATH && git pull origin master && git pull --tags

#Step 2  @TODO -- Supress git messages
git checkout $1
success=$?
if [[ $success -eq 0 ]]; then
	echo "GOOD: Tag checked out. Proceeding with deployment"
else
	echo "ERROR: Tag not found. Aborting"
	exit;
fi


#Step 3
mkdir $DEPLOYMENT_NEW
success=$?
if [[ $success -eq 0 ]]; then
	echo "GOOD: Deployment directory created ($DEPLOYMENT_NEW)"
else
	echo "ERROR: Deployment directory already exists or no permissions. Aborting"
	exit;
fi

#Step 4
cp -r /var/www/deploy-git/www/* $DEPLOYMENT_NEW

#Step 5 & 6 -- Move/copy images
mkdir $IMAGES_NEW
mv $IMAGES_OLD $IMAGES_NEW

#Step 7 -- Create Cache folder
mkdir $CACHE_NEW

#Step 8 -- Copy zoo config files
cp $ZOO_OLD $ZOO_NEW

#Step 9 -- Symlink configuration.php
ln -s $CONFIG_LOCATION $CONFIG_NEW

#Step 10 -- Create symlink for httpdocs/alpha
rm $DEPLOYMENT_BASE
ln -s $DEPLOYMENT_NEW $DEPLOYMENT_BASE

# chmod the files if on alpha. Not needed on beta & live
if [ "$3" == "alpha" ]; then
	chmod -R 777 $DEPLOYMENT_NEW
fi

if [ -d "$ROOT_ASSETS" ]; then
    cp -r $ROOT_ASSETS/* $DEPLOYMENT_NEW
fi

echo "$1" > $DEPLOYMENT_NEW/.htversion
