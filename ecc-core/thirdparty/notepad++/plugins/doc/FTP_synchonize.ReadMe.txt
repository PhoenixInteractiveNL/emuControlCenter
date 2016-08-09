FTP_synchonize:
---------------
Small plugin for Notepad++ for very basic FTP support.
Currently allows one to connect to a server, browse its contents and upload/download files.

Author:
-------
Harry

Installation:
-------------
Installation is self explainatory, copy the dll into the plugins folder.

Usage:
------
At first start you have to enable the Folder window, you can do so by selecting the button on the main toolbar
that resembles a small folder (if the toolbar is configured to show plug-in icons), or by selecting the menu-item
under Plugins->FTP_synchronize called "Show FTP Folders". The window should now appear as a dockable dialog.

Before you can connect to any server for FTP operations, you have to create a new profile. Select the
"Settings" button from the FTP toolbar (to be found in the Folders window, it resembles a cog). Then, in the
settingswindow, click the "New" button to create a new profile. Change all fields and values to your preferences
and select OK, or Apply if you wish to change other settings.

Then you may connect, using the toolbarbutton (small blue plug). A menu will appear and you can select your
profile.
If everything goes right, the statusbar should say "Connected" and a single folder will display in the
folderdialog. Open it by clicking the + next to it and browse for your file.
If you want to open it, rightclick and press 'Download'. The file will now be downloaded to the local cache.
When finished successfully, the file will be opened in Notepad++ for editing. When done, you can reupload the file
using the treeview (rightclick on folder and choose "Upload") or the toolbarbutton (upward pointing arrow).
On success the file should be changed on the server.

If you need to refresh the contents of a directory, rightclick on it in the folder window and select 'Refresh'.
Using the appropriate button on the toolbar will function jsut as well.

I advise to clear the cache once in a while, the plugin will not do this for you. For every user on every server
once connected a new cache is created.
This cache can be found in the plugins configuration folder of Notepad++, its a folder with the name of the plugin
(normally FTP_synchronize) with a subdirectory called username@server, depending on the username and server when
connected. If you have specified a non-default directory browse there instead.

Currently, you can only do one thing at a time, for example, when downloading a file you cannot browse for folders
or upload something else, this may be changed in the future. A quick fix is to copy the dll, this allows you to
connect twice because a 2nd instance is created of the plugin (you will need to reconfigure this, each plugin has
its own configuration file with the same name as the DLL). However, the queue will allow you to queue certain
operation so the plugin will automatically do everything in order when the server is available again.

To disconnect from a server, simple click on the button that was previously used to connect.
The icon of the button should change from a connected pair of sockets into a disconnected one.

If you wish to display the communications with the FTP server (and some various messages), you can open the
messageswindow by clicking on the corresponding item on the FTP toolbar. This is most usefull for
debugging purposes.
To clear the text, rightclick and select 'Clear' from the popup menu.

When an FTP operation is happening, you can abort it by clicking the 'Abort' button on the toolbar.
The operation will fail and the server will be available again for other operations (note that the server can
handle only ONE operation at a time).

Some of the cache settings explained:
-------------------------------
Cache file on direct upload:
	If this is set, when you upload a file that has been opened in Notepad++ but is not in the FTPs cache,
	the plugin will copy over the file to the cache

Open the cached file automatically (only available if setting above set)
	If this is set, the copied file will be opened in Notepad++ for further editing, the original file will
	remain opened

If a file to be uploaded is not a cached file, upload to the last selected directory
	If set, when a file does not reside in the FTPs cache and you attempt to upload that file,
	the file will be uploaded to the last selected directory in the folderview.

Upload cached file on save
	If set, when a file opened in Notepad resides in the cache of the current server and that file is being
	saved, it is automatically uploaded to the corresponding FTP folder (aka Autosave)


Current bugs/limitations:
-------------------------
-One connection
-No resume supported