<?php
/* Class containing all plugin activation actions */
class NFL_ACTIVATOR {
    /**
     *  On activation, creates a page showing NFL teams and remember it.
     *  The page id is storaged in the database.
     */
    public static function activate() {
        // NFL Teams Page Arguments
		$saved_page_args = array(
			'post_title'   => 'NFL TEAMS',
			'post_content' => '['.NFL_TAG.']',
			'post_status'  => 'publish',
			'post_type'    => 'page'
		);

		// Insert the page and get its id.
		$saved_page_id = wp_insert_post( $saved_page_args );

		// Save page id to the database.
		add_option( 'nfl_teams_saved_page_id', $saved_page_id );
    }
}
?>