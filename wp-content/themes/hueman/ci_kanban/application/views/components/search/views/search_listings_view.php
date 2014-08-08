<?php $this->load->view('components/search/modules/search_filter_module', array('inputFields' => $inputFields, 'whichSesarch' => 'search_listings')); ?>

<div id="search-response"></div>

<script>$("#listings_country").val('<?php echo $this->user->currentUserData->country; ?>');</script>
<script>$("#listings_intern_type").val('<?php echo $this->user->currentUserData->profession; ?>');</script>
<script>$(function() { search.run('search_listings'); });</script>