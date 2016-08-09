<?php

class PEAR_Frontend_Gtk2_Channels
{
    static function loadChannels($cmbChannel, $config, $strDefaultChannel = 'pear.php.net')
    {
        $cmbChannel->get_model()->clear();
        $arChannels = $config->getRegistry()->getChannels();
        $arChannelNames = array();
        foreach ($arChannels as $nId => $channel) {
            if ($channel->getName() != '__uri') {
                $arChannelNames[] = $channel->getName();
            }
        }
        natcasesort($arChannelNames);

        $nPos = 0;
        $nDefaultChannelId = 0;
        foreach ($arChannelNames as $strName) {
            $cmbChannel->append_text($strName);
            if ($strName == $strDefaultChannel) {
                $nDefaultChannelId = $nPos;
            }
            $nPos++;
        }
        $cmbChannel->set_active($nDefaultChannelId);
    }//static function loadChannels($cmbChannel, $config)
}//class PEAR_Frontend_Gtk2_Channels
?>