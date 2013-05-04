#!/bin/bash

# Copyright © 2012-13 Techjoomla
# Licensed under GNU General Public License v2.0

BASELANG="en-GB"

#Start by parsing the text file containing projects and respective folders
for line in `cat project-langs.txt`; do
	# If we find a line containing a square brakcet - indicates a project
	if $( echo $line | grep --quiet '\[' )
	then
		project=`echo $line | cut -d "[" -f2 | cut -d "]" -f1`
	else
	
		#Start adding all the language files from the folder into transifex config
		nolang="${PWD}/$line/"
		source="${PWD}/$line/$BASELANG/"

		for resource in `ls $source/*.ini`; do
		
			if $( echo $resource | grep --quiet 'admin' )
			then
				# Set resource path
				prefixed="admin_"`basename $resource`
				tmp=`basename $resource`
				tresourcepath="$nolang<lang>/"`echo $tmp | sed -e "s/$BASELANG/<lang>/g"`
			else
				prefixed=`basename $resource`
				tresourcepath="$nolang<lang>/"`echo $prefixed | sed -e "s/$BASELANG/<lang>/g"`
			fi
			
			
			# Set resource name for transifex by strippping the dots
			tresourcename="${prefixed,,}"
			tresourcename=`echo $tresourcename | sed -e 's/\.//g'`

			#Sanitize path
			tresourcepath=$(readlink -m $tresourcepath)
			resource=$(readlink -m $resource)
			
			#echo $tresourcepath
			tx set --auto-local -r $project.$tresourcename "$tresourcepath" --source-language=en_GB --source-file "$resource" --execute
			#echo $command > commands.txt
			#echo "$resource -> $tresource"
		done;
		
	fi
done;

