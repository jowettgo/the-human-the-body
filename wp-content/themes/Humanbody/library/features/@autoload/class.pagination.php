<?php

/**
 *
 */
class pagination
{
    public $perpage = 16;

    public function paginate($wp_query,$post)
	{
		$total = $wp_query->found_posts;

		$perpage = $this->perpage;

		$totalPages = round($total/$perpage);
		$pagevar = get_query_var('paged') ? get_query_var('paged') : 1;
		$next = $pagevar != $totalPages ? $pagevar+1 : $pagevar;
		$prev = get_query_var('paged') > 1 ? $pagevar-1 : '';
		$base = get_the_permalink($post->ID);
		$max = 5;
		$break = false;
		if($totalPages > 1) {
			for ($i=1; $i <= $totalPages; $i++) {

				if($i < $pagevar+$max && $i > $pagevar-$max || $i > $totalPages-$max && $i < $totalPages+1) :
					$active = '';

					if($pagevar) :

						if($pagevar == $i) :

							$active = 'active';
                        else :
                            $active = '';
						endif;
					endif;

					$page = $i > 0 ? $i : '';
					$url = $base.'page/'.($page != 0 ? $page : '') ;
					$pagination .= '<li><a href="'.$url.'" class="'.$active.'">'.$i.'</a></li>';
				else :
					if(!$break) :
						$break = true;
						$pagination .= '<li><a href="javascript:void(0)" class="">...</a></li>';
					endif;

				endif;

			}

			if($pagevar*$perpage == $total) {
				$disabled = 'disabled';
			}
			$start = '<li>
							<a href="'.$base.'" class="first-item '.$disabledstart.'">First</a>
						</li>
						<li>
							<a href="'.$base.$prev.'" class="prev '.$disabledstart.'">
								<i class="fa fa-angle-left"></i>
							</a>
						</li>';
			$ending = ' <li>
							<a href="'.$base.$next.'" class="next '.$disabled.'">
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
						<li>
							<a href="'.$base.$totalPages.'" class="last-item '.$disabled.'">Last</a>
						</li>';

			return $start.$pagination.$ending;
		}
	}
}

 ?>
