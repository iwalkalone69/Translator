#!/bin/bash

LOCALES=(en_GB en_US ca_ES es_ES)
FILES=(global global2)

for code in ${LOCALES[@]}; do
    echo "Processing $code... "
    for file in ${FILES[@]}; do
        if [ -f I18N/$code/LC_MESSAGES/$file.mo ]
        then
            rm I18N/$code/LC_MESSAGES/$file.mo
        fi
        if [ -f I18N/$code/LC_MESSAGES/$file.po ]
        then
            echo -n "    Compiling $file.po... "
            RES=`msgfmt -o I18N/$code/LC_MESSAGES/$file.mo I18N/$code/LC_MESSAGES/$file.po`
            echo -e "\e[32mOK\e[0m"
        fi
    done
done
