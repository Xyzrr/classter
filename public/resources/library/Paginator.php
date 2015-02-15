<?php
class Paginator {
	private $offset;
	private $increment;
	private $length;
	private $maxPage;

	function __construct($offset, $increment, $max, $length = 5) {
		$this->offset = $offset;
		$this->increment = $increment;
		$this->maxPage = ceil($max/$increment);
		$this->length = $length;
	}

	public function getRender() {
		if($this->maxPage <= 1) {
			return "";
		} else {
			$currentPage = floor($this->offset/$this->increment) + 1;
			$str = "<div class='text-center'>";
			$str .= "<ul class='pagination'>";

			if($this->maxPage <= $this->length) { //Render basic paginator
				for($i = 1; $i <= $this->maxPage; $i++) {
					if($currentPage == $i) {
						$str .= "<li class='active'><a>${i}</a></li>";
					} else {
						$os = $this->increment*($i-1);

						$str .= "<li>";
						$str .= "<a class='paginator-button' data-os='${os}'>${i}</a>";
						$str .= "</li>";
					}
				}

			} else { //Render advanced paginator

				$middle = floor($this->length/2);
				if($currentPage <= $middle) {
					$startPage = 1;
				} elseif($currentPage > $this->maxPage - $this->length + $middle) {
					$startPage = $this->maxPage - $this->length + 1;
				} else {
					$startPage = $currentPage - $middle;
				}

				$os = $this->offset - $this->increment;
				if($currentPage == 1) {
					$str .= "<li class='disabled'><a>&laquo;</a></li>";
				} else {
					$str .= "<li>";
					$str .= "<a class='paginator-button' data-os='${os}'>&laquo;</a>";
					$str .= "</li>";
				}

				for($i = $startPage; $i < $startPage + $this->length; $i++) {
					if($currentPage == $i) {
						$str .= "<li class='active'><a>${i}</a></li>";
					} else {
						$os = $this->increment*($i-1);

						$str .= "<li>";
						$str .= "<a class='paginator-button' data-os='${os}'>${i}</a>";
						$str .= "</li>";
					}
				}

				$os = $this->offset + $this->increment;
				if($currentPage == $this->maxPage) {
					$str .= "<li class='disabled'><a>&raquo;</a></li>";
				} else {
					$str .= "<li>";
					$str .= "<a class='paginator-button' data-os='${os}'>&raquo;</a>";
					$str .= "</li>";
				}

			}

			$str .= "</ul>";
			$str .= "</div>";

			return $str;
		}
	}
}
?>