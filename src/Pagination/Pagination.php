<?php

namespace robinksp\querybuilder\Pagination;

class Pagination
{
	public $perpage;

	function __construct()
	{
		$this->perpage = 10;
	}

	function getAllPageLinks($count, $href, $limit, $page)
	{
		$output = '';
		$pages = 1;
		if (!isset($page))
			$page = 1;
		if ($limit != 0)
			$pages = ceil($count / $limit);
		if ($pages > 1) {
			if ($page == 1)
				$output = $output . '<li class="page-item disabled"><span class="page-link first ">&#8810;</span></li><li class="page-item disabled"><span class="page-link ">&#60;</span></li>';
			else
				$output = $output . '<li class="page-item"><a href="javascript:void();" class="page-link first" id="_pagination" data-id="' . $href . (1) . '" >&#8810;</a></li><li class="page-item"><a href="javascript:void();" class="page-link" id="_pagination" data-id="' . $href . ($page - 1) . '" >&#60;</a><li>';


			if (($page - 3) > 0) {
				if ($page == 1)
					$output = $output . '<li class="page-item active"><span id=1 class="page-link ">1</span></li>';
				else
					$output = $output . '<li class="page-item"><a href="javascript:void();" class="page-link" id="_pagination" data-id="' . $href . '1" >1</a></li>';
			}
			if (($page - 3) > 1) {
				$output = $output . '<li class="page-item"><span class="page-link dot">...</span></li>';
			}

			for ($i = ($page - 2); $i <= ($page + 2); $i++) {
				if ($i < 1)
					continue;
				if ($i > $pages)
					break;
				if ($page == $i)
					$output = $output . '<li class="page-item active"><span id=' . $i . ' class="page-link ">' . $i . '</span></li>';
				else
					$output = $output . '<li class="page-item"><a href="javascript:void();" class="page-link" id="_pagination" data-id="' . $href . $i . '" >' . $i . '</a></li>';
			}

			if (($pages - ($page + 2)) > 1) {
				$output = $output . '<li class="page-item"><span class="page-link dot">...</span></li>';
			}
			if (($pages - ($page + 2)) > 0) {
				if ($page == $pages)
					$output = $output . '<li class="page-item active"><span id=' . ($pages) . ' class="page-link ">' . ($pages) . '</span></li>';
				else
					$output = $output . '<li class="page-item"><a href="javascript:void();" class="page-link" id="_pagination" data-id="' . $href . ($pages) . '" >' . ($pages) . '</a></li>';
			}

			if ($page < $pages)
				$output = $output . '<li class="page-item"><a href="javascript:void();"  class="page-link" id="_pagination" data-id="' . $href . ($page + 1) . '" >></a></li><li class="page-item"><a href="javascript:void();"  class="page-link" id="_pagination" data-id="' . $href . ($pages) . '" >&#8811;</a></li>';
			else
				$output = $output . '<li class="page-item disabled"><span class="page-link ">></span></li><li class="page-item disabled"><span class="page-link ">&#8811;</span></li>';


		}
		return $output;
	}
	function getPrevNext($count, $href, $limit)
	{
		$output = '';
		if (!isset($page))
			$page = 1;
		if ($limit != 0)
			$pages = ceil($count / $limit);
		if ($pages > 1) {
			if ($page == 1)
				$output = $output . '<li class="page-item disabled"><span class="page-link  first">Prev</span></li>';
			else
				$output = $output . '<li class="page-item"><a href="javascript:void();" class="page-link first" id="_pagination" data-id="' . $href . ($page - 1) . '" >Prev</a></li>';

			if ($page < $pages)
				$output = $output . '<li class="page-item"><a href="javascript:void();"  class="page-link" id="_pagination" data-id="' . $href . ($page + 1) . '" >Next</a></li>';
			else
				$output = $output . '<li class="page-item disabled"><span class="page-link ">Next</span></li>';
		}
		return $output;
	}
}