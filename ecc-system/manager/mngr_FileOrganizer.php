<?
class FileOrganizer {
	
	private $db = false;
	private $eccident = false;
	
	public function __construct($db=false, $eccident=false) {
		$this->db = $db;
		$this->eccident = $eccident;
	}
	
	public function process() {
		
		$cat_cnt = $this->get_cat_counts();
		
		$out = array();
		foreach ($cat_cnt as $cat_id => $value) {
			$out[$value['cat_name']] = $this->get_files_by_category($cat_id);
		}
		
		print "<pre>";
		print_r($out);
		print "</pre>n";
	}
	
	public function set_categories($categories) {
		if (!$categories) return false;
		$this->categories = $categories;
	}
	
	public function get_files_by_category($category_id) {
		$snipp = array();
		$snipp[] = ($category_id) ? "m.category = ".$category_id."" : "m.category is null";
		$snipp[] = ($this->eccident) ? "fd.eccident = '".$this->eccident."'" : "1";
		#$snipp[] = "fd.duplicate is null";
		
		$sql_snipp = implode(" AND ", $snipp);
		
		$out = array();
		$q = "
			select
			*
			from
			fdata as f
			left join mdata as m on fd.eccident=m.eccident and fd.crc32=m.crc32
			where
			".$sql_snipp."
		";
		#print $q;
		$hdl = $this->db->query($q);
		while ($res = $hdl->fetch(1)) {
			
			
			if ($this->is_single_file($res['fd.path'])) {
				$out[$res['fd.id']]['crc32'] = $res['fd.crc32'];
				$out[$res['fd.id']]['path'] = $res['fd.path'];
			}
		}
		return $out;
	}
	
	public function is_single_file($path) {
		$q = "select count(*) as cnt from fdata where path='".sqlite_escape_string($path)."' group by path having cnt > 1";
		#print $q."\n";
		$hdl = $this->db->query($q);
		
		if ($res = $hdl->fetchSingle()) {
			print $q."\n";
		}
		return false;
	}
	
	public function get_cat_counts() {
		
		$snipp = array();
		$snipp[] = ($this->eccident) ? "fd.eccident = '".$this->eccident."'" : "1";
		#$snipp[] = "fd.duplicate is null";
		
		$sql_snipp = implode(" AND ", $snipp);
		
		$out = array();
		$q = "
			select
			count(*) as cnt, m.category as cat_id, sum(fd.size) as size
			from
			fdata as f
			left join mdata as m on fd.eccident=m.eccident and fd.crc32=m.crc32
			where
			".$sql_snipp."
			group by m.category
			order by
			cnt desc
		";
		#print $q;
		$hdl = $this->db->query($q);
		while ($res = $hdl->fetch(1)) {
			$out[$res['cat_id']] = $res;
			if ($res['cat_id'] != '') {
				$idx = (int) $res['cat_id'] +1;
				$out[$res['cat_id']]['cat_name'] = $this->categories[$idx];
			}
			else {
				$out[$res['cat_id']]['cat_name'] = 'Unknown';
			}
			
		}
		return $out;
	}
}

$db = new SQLiteDatabase('../database/eccdb_sqlite2', 0666, $sqliteerror);;

$test = new FileOrganizer($db, 'snes');

$cat = array(
	"",
	"Action",
	"Adventure",
	"Arcade",
	"Beat'em Up",
	"Board",
	"Card Game",
	"Casino",
	"Compilation",
	"Demo",
	"Dictionary",
	"Educational",
	"Fighting",
	"Fishing",
	"Flight Sim",
	"FPS",
	"Hardware",
	"Hunting",
	"Intro",
	"Logical",
	"Mahjong",
	"Mini-Games",
	"Music",
	"Party",
	"Pinball",
	"Jump'n Run",
	"Puzzle",
	"Racing",
	"RPG",
	"Shoot'em Up",
	"Shooting",
	"Simulation",
	"Slot Machine",
	"Sports",
	"Strategy",
	"Tool",
	"Video",
	"XXX",
);

$test->set_categories($cat);
$test->process();

?>
