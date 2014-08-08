<?php
foreach($results as $userData) $this->load->view('renderers/users/views/renderer_user_startup_item', array('userData' => $userData));