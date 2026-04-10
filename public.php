<?php
/*
Template name: Preference - Public Files
URI: https://www.projectsend.org/templates/preference
Author: ProjectSend
Author URI: https://www.projectsend.org/
Author e-mail: contact@projectsend.org
Description: Custom theme for Preference Porto.
*/
$ld = 'preference_template'; // specify the language domain for this template

$count = $files['pagination']['total'];

if ($count == 0) {
    if (isset($_GET['search'])) {
        $no_results_message = __('Your search keywords returned no results.', 'preference_template');
    } else {
        $no_results_message = __('There are no files available.', 'preference_template');
    }
}

$groups = get_groups([
    'public' => true,
]);

// Get template and branding information
$this_template = get_option('selected_clients_template');
$this_template_url = BASE_URI.'templates/'.$this_template.'/';
$logo_file_info = generate_logo_url();

$window_title = __('Available files','preference_template');
$site_title = get_option('this_install_title');

define('TEMPLATE_THUMBNAILS_WIDTH', '120');
define('TEMPLATE_THUMBNAILS_HEIGHT', '120');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo html_output( $window_title . ' &raquo; ' . $site_title ); ?></title>
		<?php meta_favicon(); ?>
		<link href='https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Sora:wght@500;600;700&display=swap' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $this_template_url; ?>/font-awesome-4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" media="all" type="text/css" href="<?php echo $this_template_url; ?>main.min.css" />

        <script>
            window.base_url = '<?php echo BASE_URI; ?>';
        </script>
        <script src="<?php echo $this_template_url; ?>/js/jquery.1.11.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
		<script src="<?php echo $this_template_url; ?>/js/jquery.masonry.min.js"></script>
        <script src="<?php echo $this_template_url; ?>/js/imagesloaded.pkgd.min.js"></script>
        <script src="<?php echo $this_template_url; ?>/js/template.js"></script>

        <?php render_custom_assets('head'); ?>
	</head>

	<body class="preference_theme public_page">
        <?php render_custom_assets('body_top'); ?>

		<div id="header">
			<div class="header_inner">
				<div id="branding" class="branding_lockup">
					<?php if ($logo_file_info && $logo_file_info['exists']) { ?>
						<img class="brand_logo" src="<?php echo $logo_file_info['url']; ?>" alt="<?php echo html_output($site_title); ?>">
					<?php } ?>
					<div class="brand_text">
						<span class="brand_title"><?php echo html_output($site_title); ?></span>
					</div>
				</div>
			</div>
		</div>

		<div id="menu">
			<div class="menu_inner">
				<p class="welcome">
					<?php _e('Public file access','preference_template'); ?>
					<span><?php echo html_output($site_title); ?></span>
				</p>
				<ul>
				<li id="search_box">
					<form action="" name="files_search" method="get">
						<input type="text" name="search" id="search_text" value="<?php echo (isset($_GET['search']) && !empty($_GET['search'])) ? html_output($_GET['search']) : ''; ?>" placeholder="<?php _e('Search...','preference_template'); ?>">
						<button type="submit" id="search_go"><i class="fa fa-search" aria-hidden="true"></i></button>
					</form>
				</li>
				<?php
					if ( !empty( $get_categories['categories'] ) ) {
						$url_client_id	= ( !empty($_GET['client'] ) && !current_role_in(['Client'])) ? $_GET['client'] : null;
						$link_template	= CLIENT_VIEW_FILE_LIST_URL;
				?>
						<li class="categories_trigger">
							<a href="#" target="_self"><i class="fa fa-filter" aria-hidden="true"></i> <?php _e('Categories', 'preference_template'); ?></a>
							<ul class="categories">
								<li class="filter_all_files"><a href="<?php echo CLIENT_VIEW_FILE_LIST_URL; if ( !empty( $url_client_id ) ) { echo '?client=' . $url_client_id; }; ?>"><?php  _e('All files', 'preference_template'); ?></a></li>
								<?php
									foreach ( $get_categories['categories'] as $category ) {
										$link_data	= array(
                                            'client' => $url_client_id,
                                            'category' => $category['id'],
                                        );
										$link_query	= http_build_query($link_data);
								?>
										<li><a href="<?php echo $link_template . '?' . $link_query; ?>"><?php echo $category['name']; ?></a></li>
								<?php
									}
								?>
							</ul>
						</li>
				<?php
					}
				?>
				<li>
                    <a href="<?php echo BASE_URI; ?>index.php" target="_self">
                        <i class="fa fa-sign-in" aria-hidden="true"></i> <?php _e('Login', 'preference_template'); ?>
                    </a>
				</li>
				</ul>
			</div>
		</div>

		<div id="content">
			<div class="content_cover"></div>
			<div class="wrapper">
				<div class="page_intro">
					<div class="page_intro_heading">
						<p class="page_intro_eyebrow"><?php echo html_output($window_title); ?></p>
					</div>
					<p class="page_intro_meta"><?php echo (int) $count; ?> files published for direct access.</p>
				</div>

            <?php if (!$count) { ?>
				<div class="no_files">
					<?php echo $no_results_message; ?>
				</div>
            <?php } else { ?>
				<div class="photo_list">
                <?php
					foreach ($files['files_ids'] as $file_id) {
                        $file = new \ProjectSend\Classes\Files($file_id);
                        ?>
						<div class="photo <?php if ($file->expired == true) { echo 'expired'; } ?>">
							<div class="photo_int">
                                <?php
                                    if ($file->isImage()) {
								?>
										<div class="img_prev">
											<?php
												if ($file->expired == false) {
											?>
														<div class="img_prev_media">
		                                                        <?php $thumbnail = make_thumbnail($file->full_path, null, TEMPLATE_THUMBNAILS_WIDTH, TEMPLATE_THUMBNAILS_HEIGHT); ?>
														<img src="<?php echo $thumbnail['thumbnail']['url']; ?>" alt="<?php echo html_output($file->title); ?>" />
														</div>
											<?php
												}
											?>
										</div>
								<?php
									} else {
										if ($file->expired == false) {
								?>
											<div class="ext_prev">
											<div class="ext_prev_media">
													<h6><?php echo strtoupper($file->extension); ?></h6>
											</div>
											</div>
								<?php
										}
									}
								?>
							</div>
							<div class="img_data">
								<h2><?php echo html_output($file->title); ?></h2>
								<div class="photo_info">
									<?php if (!empty($file->description)) { ?>
										<div class="file_description_excerpt"><?php echo format_description($file->description); ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="file_meta_column">
								<div class="file_summary">
									<span class="file_summary_chip"><?php echo $file->size_formatted; ?></span>
									<span class="file_summary_chip"><?php echo !empty($file->uploaded_date) ? format_date($file->uploaded_date) : __('Unknown', 'preference_template'); ?></span>

                                    <?php
                                        if ($file->isImage()) {
                                            $dimensions = $file->getDimensions();
                                            if (!empty($dimensions['width'])) {
                                    ?>
										<span class="file_summary_chip file_summary_chip_subtle"><?php echo $dimensions['width']; ?> x <?php echo $dimensions['height']; ?> px</span>
                                    <?php
                                            }
                                        }

                                        if ($file->expires == '1') {
                                            $exp_date = date(get_option('timeformat'), strtotime($file->expiry_date));
                                    ?>
										<span class="file_summary_chip file_summary_chip_subtle"><?php _e('Expires', 'preference_template'); ?>: <?php echo $exp_date; ?></span>
                                    <?php } ?>
								</div>
							</div>
							<div class="file_actions file_actions_public">
									<?php
										if ($file->expired == false) {
                                    ?>
											<a href="<?php echo $file->download_link; ?>" target="_blank" class="button button_gray">
												<?php _e('Download','preference_template'); ?>
                                            </a>
                                            <a href="<?php echo BASE_URI; ?>download.php?id=<?php echo $file->id; ?>&token=<?php echo $file->public_token; ?>" target="_blank" class="button button_gray">
												<?php _e('View','preference_template'); ?>
                                            </a>
									<?php
										}
										else {
									?>
											<div class="file_actions_notice"><?php _e('File expired','preference_template'); ?></div>
									<?php
										}
									?>
								</div>
						</div>
					<?php
						}
					?>
				</div>
            <?php } ?>

			</div>

			<?php render_footer_text(); ?>

        </div>

        <div class="downloading">
            <img src="<?php echo $this_template_url; ?>/img/loading.svg">
        </div>

        <?php render_custom_assets('body_bottom'); ?>

	</body>
</html>