<?php
/* Class containing all plugin deactivation actions */
class NFL_DEACTIVATOR {
    /**
     *  On deactivation, removes the page showing NFL teams
     *  using the id storaged in the database.
     */
    public static function deactivate() {
        // Get Saved page id.
		$saved_page_id = get_option( 'nfl_teams_saved_page_id' );

		// Check if the saved page id exists.
		if ( $saved_page_id ) {

			// Delete saved page.
			wp_delete_post( $saved_page_id, true );

			// Delete saved page id record in the database.
			delete_option( 'nfl_teams_saved_page_id' );
        }

    }
}
?>