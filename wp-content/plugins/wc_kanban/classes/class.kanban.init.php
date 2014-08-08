<?php
/**
 * User: Sergey
 * Date: 22.06.14
 */

class KanbanInit
{
    #region Fields
    #endregion

    #region Properties
    #endregion

    #region Public Methods

    public function add_kanban_css()
    {
        $cssfiles = '';
        $cssfiles .= "<link rel='stylesheet' href='".plugins_url('wc_kanban/inc/styles/640a0960.main.css')."'/>";
        $cssfiles .= "<link id='themeStylesheet' rel='stylesheet' href='".plugins_url('wc_kanban/inc/styles/themes/default-bright.css')." '/>";

        echo $cssfiles;
    }

    public function add_kanban_js()
    {
        //Wordpress use self-versioned jquery. In case it doesn't applied following line should be uncommented.
        //$jsfiles  = "<script src='".plugins_url('wc_kanban/inc/bower_components/jquery/jquery.min.js') . "'></script>";
        $jsfiles = "<script src='".plugins_url('wc_kanban/inc/bower_components/angular/angular.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/angular-sanitize/angular-sanitize.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/angular-ui-bootstrap-bower/ui-bootstrap.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/jquery-ui/ui/minified/jquery-ui.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/angular-ui-utils/ui-utils.min.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/bower_components/spinjs/spin.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/scripts/5ebce75f.themes.js') . "'></script>";
        $jsfiles .= "<script src='".plugins_url('wc_kanban/inc/scripts/bd231e43.scripts.js') . "'></script>";

        echo $jsfiles;
    }

    public function add_body_attributes()
    {
        $keyupAttr = '{"ctrl-shift-79":"openKanbanShortcut($event)", "ctrl-shift-72":"openHelpShortcut($event)"}';
        $script =
            "jQuery(document).ready(function(){
                jQuery('body').attr('ng-app','mpk');
                jQuery('body').attr('ng-controller','ApplicationController');
                jQuery('body').attr('ui-keyup','$keyupAttr' );
            });
        ";
        $generatedScript  = "<script>$script</script>";
        echo $generatedScript;
    }

    public function add_kanabn_ie9_js()
    {
        $jsfiles = "<!--[if lt IE 9]><script src='".plugins_url('wc_kanban/inc/bower_components/es5-shim/es5-shim.js')."'></script><script src='".plugins_url('wc_kanban/inc/bower_components/json3/lib/json3.min.js')."'></script><![endif]-->";
        echo $jsfiles;
    }


    #endregion

    #region Private Methods
    #endregion

    #region Constructors

    public function __construct()
    {
        add_action('wp_head',array($this,'add_body_attributes'));
        add_action('wp_head',array($this,'add_kanban_css'));
        add_action('wp_footer',array($this, 'add_kanban_js'));
    }


    #endregion
} 