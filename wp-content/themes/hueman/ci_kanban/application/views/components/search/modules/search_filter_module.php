<div id="search_filter_module" class="cf">
    <form id="search_parameters">
        <?php echo generateInputFields($inputFields, FALSE, TRUE); ?>
    </form>
</div>

<script type="application/javascript">
    $("#search_filter_module input").attr('onkeyup', "search.run('<?php echo $whichSesarch; ?>')");
    $("#search_filter_module select").attr('onchange', "search.run('<?php echo $whichSesarch; ?>')");
</script>