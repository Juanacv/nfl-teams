<?php
/**
 *  Class that registers shortcodes and actions to show NFL Teams
 */
class NFL_REGISTER {
    
    /**
     * Register a shortcode
     * @param string $tag Shortcode tag
     * @param object $component Object containing the method
     * @param string $callback string Method called
     */
    public static function addShortcode($tag, $component, $callback) {
        add_shortcode( $tag, array( $component, $callback ) );
    }
    /**
     * Register an action
     * @param string $action Action name
     * @param object $component Object containing the method
     * @param string $callback string Method called
     */    
    public static function addAction($action, $component, $callback) {
        add_action($action,array($component,$callback));
    }
}
?>