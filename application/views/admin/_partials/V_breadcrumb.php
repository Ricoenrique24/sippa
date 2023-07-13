<section class="content-header">
	<h1>
		DASHBOARD
	</h1>
	<ol class="breadcrumb">
		<?php foreach ($this->uri->segments as $segment) : ?>
			<?php
			$url = substr($this->uri->uri_string, 0, strpos($this->uri->uri_string, $segment)) . $segment;
			$is_active =  $url == $this->uri->uri_string;
			?>
			<li class="<?php echo $is_active ? 'active' : '' ?>">
				<?php if ($is_active) : ?>
					<?php echo strtoupper(ucwords(str_replace("-", " ", $segment))) ?>
				<?php else : ?>
					<a href="<?php echo site_url($url) ?>"><?php echo strtoupper(ucwords(str_replace("-", " ", $segment))) ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</section>
