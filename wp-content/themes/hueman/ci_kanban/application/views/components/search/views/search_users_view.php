<?php $this->load->view('components/search/modules/search_filter_module', array('inputFields' => $inputFields, 'whichSesarch' => 'search_users')); ?>

<div id="search-response"></div>
<script>$(function() { search.run('search_users'); });</script>