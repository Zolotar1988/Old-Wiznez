<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daystart</title>
    <script>var originalPageTitle = 'Daystart';</script>

    <meta name="robots" content="noindex, nofollow" />

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/<?php echo $this->framework->templateVersion; ?>/stylesheets/icons/style.css" />
    <link rel="stylesheet" href="/assets/<?php echo $this->framework->templateVersion; ?>/stylesheets/style.css" />
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/application/views/components/kanban/assets/kanban.css" />
    <link rel="stylesheet" href="/datepicker/css/datepicker.css" />


    <meta name="viewport" content="initial-scale=1.0,width=device-width,user-scalable=0" />

    <style>
        .task_files {

        }

        .task_files .title {
            color: #FFF;
            font-weight: lighter;
            font-size: 16px;
            margin: 10px 0 5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
            padding: 0 0 2px;
        }

        .task_files a {
            color: #FFF;
            font-size: 12px;
            display: block;
            line-height: 18px;

        }
    </style>

    <?php
    $scripts    = array(
        'http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js',
        '/assets/' . $this->framework->templateVersion . '/js/preLoad.js',
        '/assets/' . $this->framework->templateVersion . '/js/forms.js',
        '/assets/' . $this->framework->templateVersion . '/js/plugins/countryselect/jquery-ui-autocomplete.js',
        '/assets/' . $this->framework->templateVersion . '/js/plugins/countryselect/jquery.select-to-autocomplete.min.js',
    );
    foreach($scripts as $path) echo '<script src="' . $path . '"></script>';
    ?>
    <script src="/datepicker/js/bootstrap-datepicker.js"></script>
</head>



<body id="component-<?php echo $component_name; ?>" class="<?php echo ($this->user->id ? 'logged_in' : 'logged_out'); ?>">


<section id="component"><?php $this->load->view('components/' . $component_path, $component_data); ?></section>


<?php
//$this->load->view('layout/footer/footer');
$this->load->view('layout/footer/javascript');
?>

</body>
</html>