<?
class TreeviewPager {
	public $_p = 0;		// current page
	public $_pt = 0;		// page total count
	public $_pp = 0;		// results per page
	public $_res_total = 0;	// total count of results
	public $_res_offset = 0;	// total count of results
	public $_plast = false;
	public $_pfirst = false;
	
	public function __construct()
	{
	}
	
	public function init($total, $p, $pp)
	{
		if ($total > 0) {
			$this->_p = $p+$pp/$pp;
			$this->_plast = false;
			$this->_pfirst = true;
		}
		else {
			$this->_p = 0;
		}
		$this->_pt = ceil($total/$pp);
		$this->_pp = $pp;
		$this->_res_total = $total;
		$this->_res_offset = $p*$pp;
		
		if ($this->_pt <= 1) {
			$this->_plast = true;
			$this->_pfirst = true;
		}
		
		return $this;
	}
	
	public function next($offset=false)
	{
		$this->_plast = false;
		$this->_pfirst = false;
		
		if ($this->_res_total > 0) {
			if ($this->_p < 0) {
				$this->_p = 0;
			}
			elseif ($this->_p >= $this->_pt) {
				$this->_p = $this->_pt;
				$this->_plast = true;
				$this->_pfirst = false;
			}
			else {
				$this->_p++;
				$this->_res_offset += $this->_pp;	
			}
			if ($this->_p+1 > $this->_pt) {
				$this->_plast = true;
				$this->_pfirst = false;
			}	
		}
		return $this;
	}
	
	public function prev($offset=false)
	{
		$this->_plast = false;
		$this->_pfirst = false;
		
		
		
		if ($this->_res_total > 0) {
			$this->_p--;
			if ($this->_p <= 1 ) {
				$this->_p = 1;
				$this->_plast = false;
				$this->_pfirst = true;
			}
			$this->_res_offset -= $this->_pp;
			if ($this->_res_offset < 0 ) {
				$this->_res_offset = 0;
			}
		}
		
		return $this;
	}
	
	public function first()
	{
		$this->_plast = false;
		$this->_pfirst = true;
		
		$this->_p = 1;
		$this->_res_offset = 0;
		
		return $this;
	}
	
	public function last()
	{
		$this->_plast = true;
		$this->_pfirst = false;
		
		$this->_p = $this->_pt;
		$this->_res_offset = ($this->_pp*$this->_p)-$this->_pp;
		
		return $this;
	}
	
	public function reload()
	{
		return $this;
	}
}
?>
