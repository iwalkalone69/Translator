#!/bin/bash

LOCALES=(en_GB en_US ca_ES es_ES)
FILES=(global)

for code in ${LOCALES[@]}; do
    echo "Processing $code... "
    for file in ${FILES[@]}; do
        echo -n "    Compiling $file.po... "
        if [ -f I18N/$code/LC_MESSAGES/$file.mo ]
        then
            rm I18N/$code/LC_MESSAGES/$file.mo
        fi
        RES=`msgfmt -o I18N/$code/LC_MESSAGES/$file.mo I18N/$code/LC_MESSAGES/$file.po`
        echo -e "\e[32mOK\e[0m"
    done
done
