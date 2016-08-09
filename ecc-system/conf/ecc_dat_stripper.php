<?
/*
* This will used for datfile-import to strip data from names.
*/
$dat_strip_data = array (
	
	// strip years
	// --------------------------
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
	
	// 'J' => 'Japanese',
	// --------------------------
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
	
	// 'E' => 'English',
	// --------------------------
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
	
	// 'G' => 'German',
	// --------------------------
	'(G)' => array(
		'regex' => False,
		'save_type'	=> 'languages',
		'save_string' => 'G',
	),
	// 'G' => 'German',
	'ger' => array(
		'regex' => '\(.*?ger.*?\)',
		'save_type'	=> 'languages',
		'save_string' => 'G',
	),
	
	// 'F' => 'French',
	// --------------------------
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
	
	// 'I' => 'Italian',
	// --------------------------
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
	
	// 'S' => 'Spanish',
	// --------------------------
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
	
	// 'C' => 'Chinese',
	// --------------------------
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
	
	// 'P' => 'Portugese',
	// --------------------------
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
	
	// 'PL' => 'Polish',
	// --------------------------
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
	
	// 'KOR' => 'Korean',
	// --------------------------
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
	
	// Trainer
	// --------------------------
	// --------------------------
	// --------------------------
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
	
	
	// --------------------------
	// --------------------------
	// --------------------------
	'(PD)' => array(
		'regex' => false,
		'save_type'	=> 'freeware',
		'save_string' => 1,
	),			
	
	// --------------------------
	// --------------------------
	// --------------------------
	'(x-rated)' => array(
		'regex' => false,
		'save_type'	=> 'usk',
		'save_string' => 18,
	),	
	
	// --------------------------
	// --------------------------
	// --------------------------
	'[!]' => array(
		'regex' => false,
		'save_type'	=> 'running',
		'save_string' => 1,
	),				
	
	// --------------------------
	// --------------------------
	// --------------------------
	#'[T+' => '\[T\+.+?\]',
	'[T+' => array(
		'regex' => '\[T\+.+?\]',
		'save_type'	=> 'usermod',
		'save_string' => 1,
	),
	// '[T-' => '\[T-.+?\]',
	'[T-' => array(
		'regex' => '\[T-.+?\]',
		'save_type'	=> 'usermod',
		'save_string' => 1,
	),
	
	// Simple remove
	// --------------------------
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
	'[p1]' => False,
	'(JUE)' => False,
	'(JU)' => False,
	'(UE)' => False,
	'(EJ)' => False,
	'[M]' => False,
	'[C]' => False,
	'[A]' => False,
	'[X]' => False,
	'(JE)' => False,
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
	'(intro removed)' => False,
	'(lcp2000)' => False,
	'(no blood)' => False,
	'(yellow)' => False,
	'(blue)' => False,
	'(red)' => False,
	'(Password Patch)' => False,
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
?>
