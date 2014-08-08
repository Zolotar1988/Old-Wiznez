<?php
include('./config.php');
?>


Here are some other files to include<br><br>
&ltscript src="/assets/<?php echo $config->version; ?>/foundation/js/foundation.min.js"&gt;&lt/script&gt;<br>

<?php
// Compile together the paths for JavaScript
foreach($config->desiredComponents as $component)
{
    ?>
&ltscript src="/application/views/components/<?php echo $component; ?>/assets/<?php echo $component; ?>.js"&gt;&lt/script&gt;
<br>
<?php
}
?>
<br><br><br><br>









Here are some general includes<br><br>
@import "./_settings.scss";<br>
@import "./includes/mixins/shadow";<br>
@import "./includes/mixins/transition";<br>
@import "./includes/mixins/gradients";<br>
@import "./includes/mixins/rounded";<br>
@import "./includes/mixins/calc";<br>
@import "./includes/mixins/reset";<br>
@import "./includes/mixins/float";<br>
@import "./includes/mixins/typography";<br>
@import "./includes/mixins/buttons";<br>
@import "./includes/_reset.scss";<br>
@import "./includes/_tables.scss";<br>
@import "./includes/_keyVal-row.scss";<br>
@import "./includes/_users.scss";<br>
@import "./includes/_forms.scss";<br>
@import "./includes/_layout.scss";<br>

<?php
// Compile together the paths for SASS
foreach($config->desiredComponents as $component)
{
    ?>
    #component-<?php echo $component; ?> { @import "../../../application/views/components/<?php echo $component; ?>/assets/_<?php echo $component; ?>.scss"; }
    <br>
    <?php
}
?>

<?php
// Compile together the paths for SASS
foreach($config->desiredModules as $module)
{
    ?>
    #module-<?php echo $module; ?> { @import "../../../application/views/modules/<?php echo $module; ?>/assets/_<?php echo $module; ?>.scss"; }
    <br>
    <?php
}
?>
<br><br><br><br>










Just a note, here we have the start of the routes just to make it easier to copy/paste<br><br>
&lt?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');<br>
$route['default_controller'] = 'frontpage/frontpage_interface_controller/index';<br>
$route['error_404']= 'showerror/error_404';<br>
    $route['email_verification/(:any)/(:any)']  =   'libraryinterfaces/email_lib_interface_controller/verifiyEmail';<br>

<?php
// Compile routes
foreach($config->desiredComponents as $component)
{
    if(empty($config->componentViews[$component])) continue;

    foreach($config->componentViews[$component] as $match => $view)
    {
        ?>
    $route['<?php echo $component . ($view == 'index' ? '' : '/' . $view) . ($match ? '/' . $match : ''); ?>'] = '<?php echo $component; ?>/<?php echo $component; ?>_interface_controller/<?php echo $view; ?>';
    <br>
    <?php
    }
}
?>