<?php
# Editable account fields
$config['account']['editableFields'] = array(
    'username'  => array('namespace' => 'account', 'title' => 'Username/Email', 'type' => 'input', 'required' => TRUE),
    'password'  => array('namespace' => 'account', 'title' => 'Password', 'type' => 'password'),
    'password_repeat'  => array('namespace' => 'account', 'title' => 'Password repeat', 'type' => 'password')
);