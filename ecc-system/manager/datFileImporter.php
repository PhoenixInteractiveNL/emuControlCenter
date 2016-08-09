<?

/*
$string = "dies (2005) ist ein test";

$reg = '\((\d\d\d\d)\)';
preg_match('/'.$reg.'/i', $string, $matches);

print "<pre>";
print_r($matches);
print "</pre>\n";

#die;
*/

class DatParser {
	public function parse() {
		
		$stripInfos = array (
		
		
			'(19' => array(
				'regex' => '\((\d\d\d\d)\)',
				'save_type'	=> 'year',
				'save_string' => true,
				'save_match' => 1,	// match position is 1, not 0
			),
			'(20' => array(
				'regex' => '\((\d\d\d\d)\)',
				'save_type'	=> 'year',
				'save_string' => true,
				'save_match' => 1,
			),
		
			# 'J' => 'Japanese',
			# --------------------------
			'(J)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'J',
			),
			'(JPN)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'J',
			),
			
			# 'E' => 'English',
			# --------------------------
			'(E)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'E',
			),
			'eng' => array(
				'regex' => '\(.*?eng.*?\)',
				'save_type'	=> 'languages',
				'save_string' => 'E',
			),
			'(U)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'E',
			),
			'(EUR)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'E',
			),
			'(USA)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'E',
			),			
			
			# 'G' => 'German',
			# --------------------------
			'(G)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'G',
			),
			# 'G' => 'German',
			'ger' => array(
				'regex' => '\(.*?ger.*?\)',
				'save_type'	=> 'languages',
				'save_string' => 'G',
			),
			
			# 'F' => 'French',
			# --------------------------
			'(F)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'F',
			),
			'fre' => array(
				'regex' => '\(.*?fre.*?\)',
				'save_type'	=> 'languages',
				'save_string' => 'F',
			),
			
			# 'I' => 'Italian',
			# --------------------------
			'(I)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'I',
			),
			'ita' => array(
				'regex' => '\(.*?ita.*?\)',
				'save_type'	=> 'languages',
				'save_string' => 'I',
			),
			
			# 'S' => 'Spanish',
			# --------------------------
			'(S)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'S',
			),
			'span' => array(
				'regex' => '\(.*?eng.*?\)',
				'save_type'	=> 'languages',
				'save_string' => 'S',
			),			
			
			# 'C' => 'Chinese',
			# --------------------------
			'(C)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'C',
			),
			'(Chinese)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'C',
			),
			
			# 'P' => 'Portugese',
			# --------------------------
			'(P)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'P',
			),
			'(Brazil)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'P',
			),			
			
			# 'PL' => 'Polish',
			# --------------------------
			'(PL)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'PL',
			),
			'(Polish)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'PL',
			),
			
			# 'KOR' => 'Korean',
			# --------------------------
			'(Korean)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'KOR',
			),
			'(K)' => array(
				'regex' => False,
				'save_type'	=> 'languages',
				'save_string' => 'KOR',
			),
			
			# Trainer
			# --------------------------
			# --------------------------
			# --------------------------
			'(No Trainer)' => array(
				'regex' => False,
				'save_type'	=> 'trainer',
				'save_string' => '0',
			),
			'[t]' => array(
				'regex' => false,
				'save_type'	=> 'trainer',
				'save_string' => 1,
			),
			
			
			# --------------------------
			# --------------------------
			# --------------------------
			'(PD)' => array(
				'regex' => false,
				'save_type'	=> 'freeware',
				'save_string' => 1,
			),			
			
			# --------------------------
			# --------------------------
			# --------------------------
			'(x-rated)' => array(
				'regex' => false,
				'save_type'	=> 'usk',
				'save_string' => 18,
			),	
			
			# --------------------------
			# --------------------------
			# --------------------------
			'[!]' => array(
				'regex' => false,
				'save_type'	=> 'running',
				'save_string' => 1,
			),				
			
			# --------------------------
			# --------------------------
			# --------------------------
			#'[T+' => '\[T\+.+?\]',
			'[T+' => array(
				'regex' => '\[T\+.+?\]',
				'save_type'	=> 'usermod',
				'save_string' => 1,
			),
			# '[T-' => '\[T-.+?\]',
			'[T-' => array(
				'regex' => '\[T-.+?\]',
				'save_type'	=> 'usermod',
				'save_string' => 1,
			),
			
			/*
			'NL' => 'Dutch',
			'NO' => 'Norway',
			'SW' => 'Swedish',
			'FN' => 'Finnish',
			'KOR' => 'Korean',
			*/
			
			#'(C)' => False,
			#'(J)' => False,
			#'(E)' => False,
			#'(G)' => False,
			#'(F)' => False,
			#'(U)' => False,
			#'(S)' => False,
			#'(I)' => False,
			#'(Polish)' => False,
			#'(Chinese)' => False,
			#'(Korean)' => False,
			#'ger' => '\(.*?ger.*?\)',
			#'fre' => '\(.*?fre.*?\)',
			#'eng' => '\(.*?eng.*?\)',
			#'span' => '\(.*?eng.*?\)',
			#'ita' => '\(.*?eng.*?\)',
			#'(Brazil)' => False,
			'[b]' => False,
			'(B)' => False,
			'(D)' => False,
			'(H)' => False,
			'(R)' => False,
			'(UK)' => False,
			'(A)' => False,
			'(W)' => False,
			'(CH)' => False,
			'(Beta)' => False,
			#'(PD)' => False,
			'[p1]' => False,
			#'[!]' => False,
			'(JUE)' => False,
			'(JU)' => False,
			'(UE)' => False,
			'(EJ)' => False,
			'[M]' => False,
			'[C]' => False,
			'[A]' => False,
			'[X]' => False,
			'(JE)' => False,
			#'(K)' => False,
			'[SS]' => False,
			'[S]' => False,
			'(GB)' => False,
			'[U]' => False,
			'[O]' => False,
			'[h-SGB]' => False,
			'(UAE)' => False,
			'(UEA)' => False,
			'(UA)' => False,
			'(AE)' => False,
			'(Unl)' => False,
			'[hI]' => False,
			'[hIRff]' => False,
			'(EEPROM)' => False,
			'(SRAM)' => False,
			'(Multirom)' => False,
			'(Save)' => False,
			'(Password)' => False,
			'(sgx)' => False,
			'(no sgx)' => False,
			'(prototype)' => False,
			'(Proto)' => False,
			'(J-Cart)' => False,
			'(Joystick)' => False,
			'(NP)' => False,
			'(PAL)' => False,
			'(NTSC)' => False,
			'(Country Check)' => False,
			'(32Mbit)' => False,
			'(Boot)' => False,
			'(FG)' => False,
			'(FGD)' => False,
			'(DS-1)' => False,
			'(GBA)' => False,
			'(unfinished)' => False,
			'(Alpha)' => False,
			'(padded)' => False,
			'(update)' => False,
			'(quang2000)' => False,
			'(lik-sang)' => False,
			'(y2kode)' => False,
			'(crc-2)' => False,
			'(jua)' => False,
			'(16mbit)' => False,
			'(4mbit)' => False,
			'(16k)' => False,
			'(32k)' => False,
			#'(x-rated)' => False,
			'(intro removed)' => False,
			'(lcp2000)' => False,
			'(no blood)' => False,
			'(yellow)' => False,
			'(blue)' => False,
			'(red)' => False,
			'(Password Patch)' => False,
			#'(No Trainer)' => False,
			'(bug fixes)' => False,
			'(Joypad)' => False,
			'(NSS)' => False,
			'(UpperCase)' => False,
			'(4Man)' => False,
			'(NES-SNES Adapter)' => False,
			'(Alt Music)' => False,
			'(V.ROM)' => False,
			'(Z64)' => False,
			'[v]' => False,
			#'[T-' => '\[T-.+?\]',
			#'[T+' => '\[T\+.+?\]',
			'(' => '\([0-9+-]+?\)',
			'{' => '\{[0-9+-]+?\}',
			'(V' => '\(V[0-9a-zA-z+-]+?\.{0,1}[0-9]+?\)',
			'(S' => '\(S[0-9a-zA-z+-]+?\)',
			'(M' => '\(M[0-9a-zA-z+-]+?\)',
			'[a' => '\[a[0-9a-zA-z+-]+?\]',
			'[b' => '\[b[0-9a-zA-z+-]+?\]',
			'[r' => '\[r[0-9a-zA-z+-]+?\]',
			'[f' => '\[f[0-9a-zA-z+-]+?\]',
			'[h' => '\[h[0-9a-zA-z+-]+?\]',
			'[o' => '\[o[0-9a-zA-z+-]+?\]',
			'[p' => '\[p[0-9a-zA-z+-]+?\]',
			'[t' => '\[t[0-9a-zA-z+-]+?\]',
			'(t' => '\(t[0-9a-zA-z+-]+?\)',
			'[BF' => '\[BF[0-9a-zA-z+]*?\]',
			'-M' => '\([A-Za-z-]+?\-M[0-9]*?\)',
			' by ' => '(by .*?)$',
			'hack' => '\(.*?hack.*?\)',
			'version' => '\(.*?version.*?\)',
			'rev' => '\(.*?rev.*?\)',
			'beta' => '\(.*?beta.*?\)',
			'dump' => '\[.*?dump.*?\]',
			'dump' => '\(.*?dump.*?\)',
			'bios' => '\(.*?bios.*?\)',
			'release' => '\(.*?release.*?\)',
			'PAL' => '\(.*?PAL.*?\)',
			'NTSC' => '\(.*?NTSC.*?\)',
			'updated' => '\(.*?updated.*?\)',
			'final' => '\(.*?final.*?\)',
			'header' => '\(.*?header.*?\)',
			'build' => '\(.*?build.*?\)',
			'bung' => '\(.*?bung.*?\)',
			'bung' => '\(.*?bung.*?\)',
		);
		
		$ret = array();
		
		#############################################################################
		###### // gameboy classic + color + advanced + virtual
		#############################################################################
		
		$base_path = "../../../emuControlCenter-user-datfiles/";
		
		
		$data = array (
			// GBA

			array(
				'ecc_ident' => 'gba',
				'file_ext_default' => '.gba',
				'name_from_field' => 1,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/gba_lister/GBARL_Roms[RC_2436].dat",
				'strip_info_direct_after' => false,
				'images' => false,
				/*
				'images' => array(
					'image_folder' => "../../user//gba_lister/",
					'image_id_position_start' => 0,
					'image_id_position_end' => 7,
					'remove_string' => '-',
				),
				*/
				
			),
			array(
				'ecc_ident' => 'gba',
				'file_ext_default' => '.gba',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => true,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodGBA 2.01.dat",
			),
			array(
				'ecc_ident' => 'gba',
				'file_ext_default' => '.gba',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/Advance-Power_GBA_RC(alphabetically).dat",
				'strip_info_direct_after' => "(",
			),
			array(
				'ecc_ident' => 'gba',
				'file_ext_default' => '.gba',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodGBA  - 0.999.7.dat",
			),
			array(
				'ecc_ident' => 'gba',
				'file_ext_default' => '.gba',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GBAdat Classic (RomCenter Alphabetical).dat",
			),
			// GB
			array(
				'ecc_ident' => 'gb',
				'file_ext_default' => '.gb',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Nintendo Game Boy (20051113_RC).dat",
			),
			// GBC
			array(
				'ecc_ident' => 'gbc',
				'file_ext_default' => '.gbc',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Nintendo Game Boy Color (20051113_RC).dat",
			),
			
			// GBX - GBC and GB
			array(
				'ecc_ident' => '',
				'file_ext_default' => '',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => true,
				'ini_file' => $base_path."/GoodGBx 2.02.dat",
				'strip_info_direct_after' => false,
			),
			array(
				'ecc_ident' => '',
				'file_ext_default' => '',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => true,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodGBX  - 1.020.0.dat",
			),
			// Visual gameboy
			array(
				'ecc_ident' => 'gbv',
				'file_ext_default' => '.vb',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/GoodVBoy 2.01.dat",
				'strip_info_direct_after' => false,
			),
			array(
				'ecc_ident' => 'gbv',
				'file_ext_default' => '.vb',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Nintendo Virtual Boy (20040525_RC).dat",
			),
			
			// N64
			array(
				'ecc_ident' => 'n64',
				'file_ext_default' => '.v64',
				'extension_from_field' => false,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/GoodN64 2.02a.dat",
				'strip_info_direct_after' => false,
			),
			array(
				'ecc_ident' => 'n64',
				'file_ext_default' => '.v64',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodN64  - 0.999.9a.dat",
			),
			array(
				'ecc_ident' => 'n64',
				'file_ext_default' => '.v64',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Nintendo 64 (20051029_RC).dat",
			),
			// NES
			array(
				'ecc_ident' => 'nes',
				'file_ext_default' => '.nes',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Nintendo Entertainment System - Famicom (20051126_RC).dat",
			),
			// SNES
			array(
				'ecc_ident' => 'snes',
				'file_ext_default' => '.smc',
				'extension_from_field' => false,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodSNES 2.04.dat",
			),
			array(
				'ecc_ident' => 'snes',
				'file_ext_default' => '.smc',
				'extension_from_field' => false,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodSNES - 0.999.5 for RC 2.5.dat",
			),
			array(
				'ecc_ident' => 'snes',
				'file_ext_default' => '.smc',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Super Nintendo Entertainment System - Super Famicom (20051113_RC).dat",
			),
			// GENESIS
			array(
				'ecc_ident' => 'gen',
				'file_ext_default' => '.smd',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodGen 2.05.dat",
			),
			array(
				'ecc_ident' => 'gen',
				'file_ext_default' => '.smd',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Sega Genesis - Mega Drive - 32X (20051110_RC).dat",
			),
			// MASTER-SYSTEM
			array(
				'ecc_ident' => 'sms',
				'file_ext_default' => '.sms',
				'extension_from_field' => false,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodSMS 2.01.dat",
			),
			array(
				'ecc_ident' => 'sms',
				'file_ext_default' => '.sms',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Sega Master System (20051109_RC).dat",
			),
			// GAME-GEAR
			array(
				'ecc_ident' => 'gg',
				'file_ext_default' => '.gg',
				'extension_from_field' => false,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodGG 2.01.dat",
			),
			array(
				'ecc_ident' => 'gg',
				'file_ext_default' => '.gg',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Sega GameGear (20050904_RC).dat",
			),
			// ngp snk neo geo pocket
			array(
				'ecc_ident' => 'ngp',
				'file_ext_default' => '.ngp',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/GoodNGPx 2.01.dat",
				'strip_info_direct_after' => false,
			),
			array(
				'ecc_ident' => 'ngp',
				'file_ext_default' => '.ngp',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/SNK NeoGeo Pocket (20050825_RC).dat",
			),
			array(
				'ecc_ident' => 'ngp',
				'file_ext_default' => '.ngc',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/SNK NeoGeo Pocket Color (20040523_RC).dat",
			),
			array(
				'ecc_ident' => 'ngp',
				'file_ext_default' => '.ngp',
				'extension_from_field' => 4,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodNGPX - 1.000.0.dat",
			),
			// lynx atari lynx
			array(
				'ecc_ident' => 'lynx',
				'file_ext_default' => '.lnx',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodLynx 2.01.dat",
			),
			array(
				'ecc_ident' => 'lynx',
				'file_ext_default' => '.lnx',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Atari Lynx (20040625_RC).dat",
			),
			
			// pce pc-engine / turbo graphics
			array(
				'ecc_ident' => 'pce',
				'file_ext_default' => '.pce',
				'name_from_field' => 5,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodPCE 1.09a.dat",
			),
			array(
				'ecc_ident' => 'pce',
				'file_ext_default' => '.pce',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/NEC PC Engine - TurboGrafx 16 (20050626_RC).dat",
			),
			
			// wswan wonderswan
			array(
				'ecc_ident' => 'wswan',
				'file_ext_default' => '.ws',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/GoodWSx 2.01.dat",
			),
			array(
				'ecc_ident' => 'wswan',
				'file_ext_default' => '.ws',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Bandai WonderSwan (20040224_RC).dat",
			),
			array(
				'ecc_ident' => 'wswan',
				'file_ext_default' => '.wsc',
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Bandai WonderSwan Color (20051113_RC).dat",
			),
			// atari consoles
			array(
				'ecc_ident' => 'a2600',
				'file_ext_default' => '.a26',
				'name_from_field' => 5,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Good2600 1.00.dat",
			),
			array(
				'ecc_ident' => 'a5200',
				'file_ext_default' => '.a52',
				'name_from_field' => 5,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Atari 5200 (20050104_RC).dat",
			),
			array(
				'ecc_ident' => 'a5200',
				'file_ext_default' => '.a52',
				'name_from_field' => 5,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Good5200 2.01.dat",
			),
			array(
				'ecc_ident' => 'a7800',
				'file_ext_default' => '.a78',
				'name_from_field' => 5,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'strip_info_direct_after' => false,
				'ini_file' => $base_path."/Good7800 2.04.dat",
			),			
		);
		
		/*
		* parse images from gba_lister
		* hack in loop - search for // only for gba_listener
		*/
		$data = array (
			array(
				'ecc_ident' => 'nds',
				'file_ext_default' => '.nds',
				'name_from_field' => 1,
				'extension_from_field' => 5,
				'use_extesion_as_eccident' => false,
				'ini_file' => $base_path."/NDSRL_Roms[RC_507].dat",
				'strip_info_direct_after' => false,
				'strip_info_pos_start' => 0,
				'strip_info_pos_end' => 7,
				
				'images' => false,
				/*
				'images' => array(
					'image_folder' => "../../user//gba_lister/",
					'image_id_position_start' => 0,
					'image_id_position_end' => 7,
					'remove_string' => '-',
				),
				*/
				
			),
		);
		
		foreach ($data as $key => $value) {
			
			$ecc_ident = $value['ecc_ident'];
			$file_ext_default = $value['file_ext_default'];
			$extension_from_field = $value['extension_from_field'];
			$use_extesion_as_eccident = $value['use_extesion_as_eccident'];
			$ini_file = $value['ini_file'];
			$strip_info_direct_after = $value['strip_info_direct_after'];
			
			$strip_info_pos_start = $value['strip_info_pos_start'];
			$strip_info_pos_end = $value['strip_info_pos_end'];
			
			
			
			$images = @$value['images'];
			
			
			$name_from_field = (isset($value['name_from_field']) && $value['name_from_field']!==false) ? $value['name_from_field'] : 1 ;
			
			
			$ini = $this->parse_ini_file_quotes_safe($ini_file);
			
			$ret = array();
			$ret['CREDITS'] = $ini['CREDITS'];
			$ret['DAT'] = $ini['DAT'];
			$ret['EMULATOR'] = $ini['EMULATOR'];
			
			print "\n## Start parsing $ecc_ident: $ini_file (DEFAULT: $file_ext_default)!\n";
			print str_repeat("#", 80)."\n";
			print "<pre>";
			print_r($value);
			print "</pre>\n";
			print str_repeat("-", 80)."\n";
			
			$count_valid = 0;
			$count_corrupt = 0;
			$count_total = 0;
			
			foreach ($ini['GAMES'] as $key => $value) {
				$split = explode('¬', $key);
				

				
				if (isset($split[1]) && isset($split[4]) && isset($split[5])) {
					// einige felder sind in lowercase
					// hier kann angegeben werden, welches feld
					// fï¿½r den file_namen genutzt werden soll
					if ($extension_from_field !== false) {

						if (false === strpos($split[$extension_from_field], ".")) {
							$file_name = trim($split[$name_from_field]);
							$file_ext = "";
						}
						else {
							$file_ext_array = explode(".", $split[$extension_from_field]);
							$file_ext = array_pop($file_ext_array);
							if (isset($name_from_field) && $name_from_field !== false) {
								$file_name = trim(str_replace($file_ext_default, "", $split[$name_from_field]));
							}
							else {
								$file_name = implode(".", $file_ext_array);
							}
						}
					}
					else {
						$file_ext = $file_ext_default;
						$file_name = trim(str_replace($file_ext_default, "", $split[$name_from_field]));
					}
					$eccident = ($use_extesion_as_eccident===true) ? trim(str_replace(".", "", $file_ext)) : $ecc_ident;
					$file_ext = trim(str_replace(".", "", $file_ext));
					
					
					$image_id = false;
					if (isset($images) && $images != false) {
						$image_id = substr($file_name, $images['image_id_position_start'], $images['image_id_position_end']);
						$file_name = str_replace($image_id, '', $file_name);
						$image_id =  (int)str_replace($images['remove_string'], '', $image_id);
					}
					
					

					
					
					$file_crc32 = $split[6];
					$file_id = $split[7];
					
					$stripped_infos = "";
					
					
					if ($strip_info_pos_start || $strip_info_pos_end) {
						$strip_info_pos_start = ($strip_info_pos_start) ? $strip_info_pos_start : 0;
						$strip_info_pos_end = ($strip_info_pos_end) ? $strip_info_pos_end : strlen($file_name);
						
						$stripped_infos .= substr($file_name, $strip_info_pos_start, $strip_info_pos_end);
						$file_name = str_replace($stripped_infos, '', $file_name);
						#print "$test $strip_info_pos_start || $strip_info_pos_end\n";
						#die;
					}
					
					if (isset($strip_info_direct_after) && $strip_info_direct_after !== false) {
						if ($pos = strpos($file_name, $strip_info_direct_after)) {
							$file_name_tmp = $file_name;
							$file_name = trim(substr($file_name_tmp, 0, $pos));
							$stripped_infos .= trim(substr($file_name_tmp, $pos));
						}
					}
					else {
						
						// remove crap from info-file-data
						foreach ($stripInfos as $search => $regex) {
							if (stripos($file_name, $search)!==false) {
								
								if ($regex===false) {
									$file_name_original_tmp = $file_name;
									$file_name = str_ireplace($search, "", $file_name);
									if ($file_name != $file_name_original_tmp) {
										$stripped_infos .= trim($search)."|";
									}
								}
								else {
									if (is_array($regex)) {
										
										$regex_tmp = $regex;
										$regex = $regex_tmp['regex'];
										$save_type = $regex_tmp['save_type'];
										$save_string = $regex_tmp['save_string'];
										$save_match = (isset($regex_tmp['save_match'])) ?  $regex_tmp['save_match'] : 0;
										
										
										if ($regex!==false) {
											preg_match('/'.$regex.'/i', $file_name, $matches);
											if (isset($matches[0])) {
												$stripped_infos .= trim($matches[0])."|";
												$file_name = preg_replace('/'.$regex.'/i', "", $file_name);
												
												// auto detect
												if ($save_match) {
													$save_string = $matches[$save_match];
												}
												else {
													$save_string = ($save_string!==false) ? $save_string : $matches[0];
												}
												$ret['GAMES'][$file_crc32]['ECC_INFOS'][$save_type][$save_string] = true;
												
												#if ($save_match) {
												#	print "['GAMES'][$file_crc32]['ECC_INFOS'][$save_type][$save_string]";
												#	die;
												#}
											}
										}
										else {
											$file_name_original_tmp = $file_name;
											$file_name = str_ireplace($search, "", $file_name);
											if ($file_name != $file_name_original_tmp) {
												$stripped_infos .= trim($search)."|";
												
												// auto detect
												$save_string = ($save_string!==false) ? $save_string : $search;
												$ret['GAMES'][$file_crc32]['ECC_INFOS'][$save_type][$save_string] = true;
											}
										}
										
										

									}
									else {
										preg_match('/'.$regex.'/i', $file_name, $matches);
										if (isset($matches[0])) {
											$stripped_infos .= trim($matches[0])."|";
											$file_name = preg_replace('/'.$regex.'/i', "", $file_name);
										}
									}
								}
							}
						}
					}
					
					$file_name = str_replace("  ", " ", $file_name);
					$ret['GAMES'][$file_crc32]['ECCIDENT'] = trim(strtolower($eccident));
					$ret['GAMES'][$file_crc32]['NAME'] = trim($file_name);
					$ret['GAMES'][$file_crc32]['CRC32'] = strtoupper($file_crc32);
					$ret['GAMES'][$file_crc32]['EXT'] = trim($file_ext);
					$ret['GAMES'][$file_crc32]['INFO'] = trim($stripped_infos);
					$ret['GAMES'][$file_crc32]['INFO_ID'] = trim($file_id);
					$ret['GAMES'][$file_crc32]['IMAGE_ID'] = trim($image_id);
					print str_repeat(" ", 180).chr(13);
					print "? ".$file_name.chr(13);
					
					

					/* 
					if (!isset($count)) $count=0;
					$count++;
					
					if ($count < 2) {
						print "<pre>";
						print_r($ret['GAMES']);
						print "</pre>n";
						#die;
					}
 */
					
					// only for gba_listener
					
					if (isset($images)) {
						$source_a = $images['image_folder'].DIRECTORY_SEPARATOR.$image_id."a.jpg";
						$dest_a = $images['image_folder'].DIRECTORY_SEPARATOR."ecc_".$eccident."_".$file_crc32."_ingame_start.jpg";
						if (realpath($source_a)) copy($source_a, $dest_a);
						
						$source_b = $images['image_folder'].DIRECTORY_SEPARATOR.$image_id."b.jpg";
						$dest_b = $images['image_folder'].DIRECTORY_SEPARATOR."ecc_".$eccident."_".$file_crc32."_ingame_play_01.jpg";
						if (realpath($source_b))copy($source_b, $dest_b);
					}
					
					
					$count_valid++;
				}
				else {
					print "\nERROR $key corrupt!\n";
					$count_corrupt++;
				}
				$count_total++;
			}
			
			print str_repeat(" ", 180).chr(13);
			print "Parsing DONE!\n";
			print "count_total $count_total\n";
			print "count_valid $count_valid\n";
			print "count_corrupt $count_corrupt\n";
			print str_repeat("-", 80)."\n";
			
			
			//$sqlitDb = new SQLiteDatabase('db/eccdb_sqlite2', 0666, $sqliteerror);
			$sqlitDb = new SQLiteDatabase('../database/eccdb_sqlite2', 0666, $sqliteerror);
			foreach ($ret['GAMES'] as $crc32 => $infos) {
				
				#print $infos['NAME']."\n";
				$file_ext = ($infos['EXT']!="") ? strtolower($infos['EXT']) : strtolower($file_ext_default);
				$file_ext = trim(str_replace(".", "", $file_ext));
				
				$q = "
					SELECT
					*
					FROM
					mdata
					WHERE
					eccident = '".sqlite_escape_string($infos['ECCIDENT'])."' AND
					crc32 = '".sqlite_escape_string($infos['CRC32'])."'
					LIMIT
					0,1
				";
				#print $q."\n";
				$hdl = $sqlitDb->query($q);
				
				if ($res = $hdl->fetch(SQLITE_ASSOC)) {
					
					$aaa = $res['info']."".$infos['INFO'];
					
					#print "OLD: $aaa\n";
					
					$bbb = explode("|", $aaa);
					$infos_merged = array_unique($bbb);
					$new_infos = implode("|", $infos_merged);
					
					#print "NEW: $new_infos\n\n";
					
					$q = "
						update
						mdata
						set
						info = '".sqlite_escape_string($new_infos)."'
						where
						id = ".$res['id']."
					";
					#print $q."\n";
					$hdl = $sqlitDb->query($q);
						
					$id = $res['id'];
					#print strtoupper($infos['CRC32'])." -> ".$infos['NAME']."\n";
					print str_repeat(" ", 180).chr(13);
					print "= ".$infos['NAME'].chr(13);
				}
				else {
					
					$running = (isset($infos['ECC_INFOS']['running'])) ? current(array_keys($infos['ECC_INFOS']['running'])) : "NULL";
					$usermod = (isset($infos['ECC_INFOS']['usermod'])) ? current(array_keys($infos['ECC_INFOS']['usermod'])) : "NULL";
					$freeware = (isset($infos['ECC_INFOS']['freeware'])) ? current(array_keys($infos['ECC_INFOS']['freeware'])) : "NULL";
					$year = (isset($infos['ECC_INFOS']['year'])) ? current(array_keys($infos['ECC_INFOS']['year'])) : "NULL";
					$usk = (isset($infos['ECC_INFOS']['usk'])) ? current(array_keys($infos['ECC_INFOS']['usk'])) : "NULL";
					$creator = (isset($infos['ECC_INFOS']['creator'])) ? current(array_keys($infos['ECC_INFOS']['creator'])) : "NULL";
					
					$q = "
						INSERT INTO
						mdata
						(
						id,
						eccident,
						name,
						extension,
						crc32,
						info,
						info_id,
						running,
						usermod,
						freeware,
						year,
						usk,
						creator
						)
						values
						(
						null,
						'".sqlite_escape_string($infos['ECCIDENT'])."',
						'".sqlite_escape_string($infos['NAME'])."',
						'".sqlite_escape_string($file_ext)."',
						'".sqlite_escape_string($infos['CRC32'])."',
						'".sqlite_escape_string($infos['INFO'])."',
						'".sqlite_escape_string($infos['INFO_ID'])."',
						".sqlite_escape_string($running).",
						".sqlite_escape_string($usermod).",
						".sqlite_escape_string($freeware).",
						".sqlite_escape_string($year).",
						".sqlite_escape_string($usk).",
						".sqlite_escape_string($creator)."
						)
					";
					#print $q."\n";
					
					#die;
					
					$hdl = $sqlitDb->query($q);
					$id = $sqlitDb->lastInsertRowid();
					
					print str_repeat(" ", 180).chr(13);
					print "+ ".$infos['NAME'].chr(13);
					#print "+".$infos['NAME']."\n";
				}
				
				// process languages
				if (isset($infos['ECC_INFOS']['languages']) && count($infos['ECC_INFOS']['languages'])) {
					$this->save_language($sqlitDb, $id, array_keys($infos['ECC_INFOS']['languages']));
				}
			}
			print str_repeat(" ", 180).chr(13);
			print "All Saved DONE!\n";
		}
	}

	/*
	*
	*/
	public function save_language($sqlitDb, $mdata_id, $languages) {
		foreach ($languages as $void => $lang_ident) {
			$q = "SELECT * FROM mdata_language WHERE mdata_id=".$mdata_id." AND lang_id='".$lang_ident."'";
			$hdl = $sqlitDb->query($q);
			if ($res = $hdl->fetch(SQLITE_ASSOC)) {
			}
			else {
				$q = "INSERT INTO mdata_language ( mdata_id, lang_id) VALUES ('".$mdata_id."', '".$lang_ident."')";
				$sqlitDb->query($q);
			}
		}
		return true;
	}
	
	public function parse_ini_file_quotes_safe($f)
	{
		$newline = "
		";
		$null = "";
		$r=$null;
		$first_char = "";
		$sec=$null;
		$comment_chars="/*<;#?>";
		$num_comments = "0";
		$header_section = "";
		//Read to end of file with the newlines still attached into $f
		$f=@file($f);
		// Process all lines from 0 to count($f) 
		for ($i=0;$i<@count($f);$i++)
		{
		$newsec=0;
		$w=@trim($f[$i]);
		$first_char = @substr($w,0,1);
		if ($w)
		{
		if ((!$r) or ($sec))
		{
		// Look for [] chars round section headings
		if ((@substr($w,0,1)=="[") and (@substr($w,-1,1))=="]") {$sec=@substr($w,1,@strlen($w)-2);$newsec=1;}
		// Look for comments and number into array
		if ((stristr($comment_chars, $first_char) === FALSE)) {} else {$sec=$w;$k="Comment".$num_comments;$num_comments = $num_comments +1;$v=$w;$newsec=1;$r[$k]=$v;echo "comment".$w.$newline;}
		//
		}
		if (!$newsec)
		{
		//
		// Look for the = char to allow us to split the section into key and value 
		$w=@explode("=",$w);$k=@trim($w[0]);unset($w[0]); $v=@trim(@implode("=",$w));
		// look for the new lines 
		if ((@substr($v,0,1)=="\"") and (@substr($v,-1,1)=="\"")) {$v=@substr($v,1,@strlen($v)-2);}
		if ($sec) {$r[$sec][$k]=$v;} else {$r[$k]=$v;}
		}
		}
		}
		return $r;
		}
	}

$dp_obj = new DatParser();
$dp_obj->parse();

?>
