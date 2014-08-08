<?php

$config['techpear']['listings_fields'] = array(
    'title'  => array('namespace' => 'listings', 'title' => 'Job title', 'type' => 'input', 'required' => TRUE),
    'intern_type'  => array('namespace' => 'listings', 'title' => 'Intern type', 'type' => 'select', 'options' => array('designer' => 'Designer', 'programmer' => 'Programmer', 'marketer' => 'Marketer', 'finance' => 'Finance', 'pr' => 'PR', 'customer_support' => 'Customer support'), 'required' => TRUE),
    'intern_possibleJob'  => array('namespace' => 'listings', 'title' => 'On Location', 'type' => 'select', 'options' => array('1' => 'Yes', '0'=>'No', 'maybe' => 'Maybe'), 'required' => TRUE),
    'intern_onLocation'  => array('namespace' => 'listings', 'title' => 'On Location', 'type' => 'select', 'options' => array('1' => 'Yes', '0'=>'No', '2' => 'Either'), 'required' => TRUE),
    'description'  => array('namespace' => 'listings', 'title' => 'Description', 'type' => 'textarea'),
//    'positions_number'  => array('namespace' => 'listings', 'title' => 'Open positions', 'type' => 'select', 'options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9), 'required' => TRUE),
    'intern_questions'  => array('namespace' => 'listings', 'title' => 'Questions', 'type' => 'textarea', 'required' => FALSE),
    'time_workHours'  => array('namespace' => 'listings', 'title' => 'Estimated workload', 'type' => 'select', 'options' => array('full_time' => 'Full Time', 'part_time' => 'Part Time', 'as_needed' => 'As needed') , 'required' => TRUE),
    'date_from'  => array('namespace' => 'listings', 'title' => 'Start date', 'type' => 'date_monthYear', 'required' => TRUE),
    'date_to'  => array('namespace' => 'listings', 'title' => 'End date', 'type' => 'date_monthYear', 'required' => TRUE),
    'applications_evaluationDate'  => array('namespace' => 'listings', 'title' => 'Evaluation date', 'type' => 'select', 'options' => array('asap' => 'ASAP', 'after_deadline' => 'After deadline'), 'required' => TRUE),
    'compensation_currency'  => array('namespace' => 'listings', 'title' => 'Payment currency', 'type' => 'select', 'required' => TRUE, 'options' => array('usd' => 'USD', 'eur' => 'EUR', 'nok' => 'NOK', 'sek' => 'SEK', 'dkk' => 'DKK')),
    'compensation_estimatedPay'  => array('namespace' => 'listings', 'title' => 'Estimated pay', 'type' => 'input', 'required' => TRUE),
//    'deadline'  => array('namespace' => 'listings', 'title' => 'Deadline', 'type' => 'date', 'required' => TRUE),
);




$config['techpear']['listings_fields_forSearch'] = array(
    'city'  => array('namespace' => 'listings', 'title' => 'City', 'type' => 'input', 'searchable' => TRUE),
    'country'  => array('namespace' => 'listings', 'title' => 'Country', 'type' => 'input', 'searchable' => TRUE),
    'intern_type'  => array('namespace' => 'listings', 'title' => 'Profession needed', 'type' => 'select', 'options' => array('designer' => 'Designer', 'programmer' => 'Programmer', 'marketer' => 'Marketer', 'finance' => 'Finance', 'pr' => 'PR'), 'required' => TRUE, 'searchable' => TRUE),
//    'time_workHours'  => array('namespace' => 'listings', 'title' => 'Work hours', 'type' => 'select', 'options' => array(10=>10,20=>20,30=>30,40=>40), 'required' => TRUE, 'searchable' => TRUE),
    'intern_onLocation'  => array('namespace' => 'listings', 'title' => 'Must be on Location', 'type' => 'select', 'options' => array('1' => 'Yes', '0'=>'No', '2' => 'Irrelevant'), 'required' => TRUE, 'searchable' => TRUE),
);




$config['techpear']['listings_fields_forSearch_interns'] = array(
    'city'  => array('namespace' => 'interns', 'title' => 'City', 'type' => 'input', 'searchable' => TRUE),
    'country'  => array('namespace' => 'interns', 'title' => 'Country', 'type' => 'input', 'searchable' => TRUE),
    'intern_type'  => array('namespace' => 'interns', 'title' => 'Profession', 'type' => 'select', 'options' => array('designer' => 'Designer', 'programmer' => 'Programmer', 'marketer' => 'Marketer', 'finance' => 'Finance', 'pr' => 'PR'), 'required' => TRUE, 'searchable' => TRUE),
//    'time_workHours'  => array('namespace' => 'listings', 'title' => 'Work hours', 'type' => 'select', 'options' => array(10=>10,20=>20,30=>30,40=>40), 'required' => TRUE, 'searchable' => TRUE),
//    'intern_onLocation'  => array('namespace' => 'listings', 'title' => 'On Location', 'type' => 'select', 'options' => array('1' => 'Yes', '0'=>'No'), 'required' => TRUE, 'searchable' => TRUE),
);



$config['techpear']['listings_fields_analytics'] = array(
    'analytics_views' => array('title' => 'Views', 'analField' => TRUE),
    'analytics_applications' => array('title' => 'Applications', 'analField' => TRUE),
    'analytics_conversionRate' => array('title' => 'Conversion', 'analField' => TRUE),
);

$config['techpear']['listings_fields_forTable'] = array_merge($config['techpear']['listings_fields'], $config['techpear']['listings_fields_analytics']);

unset($config['techpear']['listings_fields_forTable']['positions_number']);
unset($config['techpear']['listings_fields_forTable']['applications_evaluationDate']);
unset($config['techpear']['listings_fields_forTable']['time_workHours']);
unset($config['techpear']['listings_fields_forTable']['intern_onLocation']);
unset($config['techpear']['listings_fields_forTable']['compensation_estimatedPay']);
unset($config['techpear']['listings_fields_forTable']['description']);
unset($config['techpear']['listings_fields_forTable']['intern_type']);
unset($config['techpear']['listings_fields_forTable']['intern_questions']);


//- approximate work hours for an intern a week (10, 20, 30, 40 hr a week)
//- when applications are evaluated (ASAP or after deadline)
//- what months you are looking for an intern “from X to Y”
//- estimated pay “between Z and Q”
//- what type of intern they are looking for
//    - what expertise they should have
//    - can the intern be remote or local
