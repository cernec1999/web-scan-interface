#!/bin/bash

#black ink
BLACK=$(ink -p usb | tail -2 | head -1 | awk '{print $2}')
BLACK="${BLACK::-1}"

#color ink
COLOR=$(ink -p usb | tail -2 | tail -1 | awk '{print $2}')
COLOR="${COLOR::-1}"

if [ $COLOR -gt 20 ] && [ $BLACK -gt 20 ]
then
    exit 1
fi

EMAIL="Howdy,

Your printer ink is getting low. Please buy the appropriate ink.

Here are the current ink levels, with the associating amazon links.

Black: %s%% (http://a.co/1Ui8m1C)
Color: %s%% (http://a.co/hsJozNo)

Best regards,
Your Printer"

#printf "$EMAIL" "$BLACK" "$COLOR"

printf "$EMAIL" "$BLACK" "$COLOR" | mail -s "Printer Ink Low" -r printer@print.abc.com email@email.com
