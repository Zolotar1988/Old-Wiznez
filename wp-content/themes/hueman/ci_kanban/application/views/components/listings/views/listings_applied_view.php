<?php
foreach($results as $listingData) $this->load->view('renderers/listings/views/renderer_listing_item', array('listingData' => $listingData));