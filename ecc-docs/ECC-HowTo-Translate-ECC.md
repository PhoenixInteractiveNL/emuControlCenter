## ECC HowTo Translate ECC
***
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_languages_globe.jpg)

Here is a small tutorial about how to add languages to emuControlCenter:

1) [_Determine your country abbreviation_](https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki/ECC-wiki-country-abbreviation)

2) Create a folder named with your country abbreviation in `ecc-system\transations` (use lowercase letters)

3) Copy the contents from the `en` folder to your country folder.

4) To let your translations correctly shown in ECC there is a file `charset.ini` in your country folder, you can edit it with notepad, now you'll see 2 settings:

    encoding_source
    encoding_destination

The `encoding_source` setting is mostly for 99% of the translations set to "UTF-8" (you can leave this unchanged)

The `encoding_destination` setting needs your country charset, so you may want to change that, here's a [_Charset list_](https://github.com/PhoenixInteractiveNL/emuControlCenter/wiki/ECC-wiki-charset-list)

5) After that you can edit and translate all PHP & INI files in the language folder, please try out in what format you should save your translation, this can be UTF-8 or ANSI, some language need to be saved in UTF-8 and other in ANSI to work correctly:
![](https://raw.githubusercontent.com/wiki/PhoenixInteractiveNL/emuControlCenter/images/img_howto_translation_format.png)

6) After all is done you can select your language in the config from ECC (ecc will restart)

7) When errors are found, you can find these in the PHP LOG file `error.log` in your ECC root folder.

You can ZIP your language folder and upload it to the [**ECC Forum**](http://eccforum.phoenixinteractive.nl/viewforum.php?f=21) or

E-Mail @ `phoenixinteractive -at- hotmail -dot- com`

If you have any questions....feel free to ask on our forum!