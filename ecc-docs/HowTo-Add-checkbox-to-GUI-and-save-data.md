## HowTo Add checkbox to GUI and save data
***
Edit the file ecc-system\gui\guiPopupConfig.glade, Add these elements in the GUI:

checkbox: startConfJoyEmulator

image: startConfJoyEmulatorImage

label: startConfJoyEmulatorLabel

Add an image in the theme folder, PNG icon 24x24:

    ecc-themes\default\icon\ecc_config_joystick.png

### EDIT: ecc-system\translations\[LANGUAGE]\i18n_popupConfig.php

Add translation for Label checkbox (example: Dutch)

	/* 1.21 */		
	'startConfJoyEmulatorLabel' =>	
	"Start Joystick emulator bij het opstarten",

### EDIT: ecc-system\manager\cGuiPopConfig.php

Configure image, around line 280, add:

    $this->startConfJoyEmulatorImage->set_from_file(FACTORY::get('manager/GuiTheme')->getThemeFolder('icon/ecc_config_joystick.png'));

Set checkbox label language, around line 1565, add:

    $this->startConfJoyEmulatorLabel->set_label(I18N::get('popupConfig', 'startConfJoyEmulatorLabel'));

Save checkbox settings, around line 1600, add:

    $this->globalIni['ECC_STARTUP']['startup_joyemulator'] = (int)$this->startConfJoyEmulator->get_active();

Load checkbox settings, line 1590, add:

    $optStartJoyEmulator = $iniManager->getKey('ECC_STARTUP', 'startup_joyemulator');
    $optStartJoyEmulator = ($optStartJoyEmulator === false || !$sectionExists) ? true : $optStartJoyEmulator;
    $this->startConfJoyEmulator->set_active($optStartJoyEmulator);

There is now a 0 or 1 written in the ECC config:

    ecc-user-configs\config\ecc_general.ini