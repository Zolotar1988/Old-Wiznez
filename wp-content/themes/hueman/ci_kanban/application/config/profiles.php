<?php
# Just define here what we'll use
$config['profile_types'] = array('namespace' => 'profile', 'intern', 'startup');

# The different fields for profiles
$config['profile_fields']['intern'] = array(
    'name'  => array('namespace' => 'profile', 'title' => 'Name', 'type' => 'input', 'required' => TRUE),
//    'nationality'  => array('namespace' => 'profile', 'title' => 'Nationality', 'type' => 'input', 'required' => TRUE),
    'birthdate'  =>  array('namespace' => 'profile', 'title' => 'Birthdate', 'type' => 'date', 'required' => TRUE),
    'gender'  => array('namespace' => 'profile', 'title' => 'Gender', 'type' => 'select', 'options' => array('male' => 'Male', 'female' => 'Female')),
    'city'  => array('namespace' => 'profile', 'title' => 'City', 'type' => 'input', 'required' => TRUE),
    'country'  => array('namespace' => 'profile', 'title' => 'Country', 'type' => 'country', 'required' => TRUE),
    'school_current'  => array('namespace' => 'profile', 'title' => 'Education level', 'type' => 'select', 'options' => array('none' => 'None', 'high_school' => 'High School', 'college' => 'College', 'university' => 'University')),
    'school_field'  => array('namespace' => 'profile', 'title' => 'What do you study?', 'type' => 'input'),
    'profession'  => array('namespace' => 'profile', 'title' => 'Your preferred work area', 'type' => 'select', 'options' => array('designer' => 'Designer', 'programmer' => 'Programmer', 'marketer' => 'Marketer', 'finance' => 'Finance', 'pr' => 'PR', 'sales' => 'Sales'), 'required' => TRUE),
//    'question_description'  => array('namespace' => 'profile', 'title' => 'Describe yourself', 'type' => 'textarea', 'placeholder' => '', 'required' => TRUE),
    'question_description'  => array('namespace' => 'profile', 'title' => 'About you', 'type' => 'textarea', 'placeholder' => 'Describe yourself', 'required' => TRUE),
    'question_whyWantIntern'  => array('namespace' => 'profile', 'title' => 'Write why you wish to intern at a startup, compared to a larger company', 'type' => 'textarea', 'placeholder' => 'Write why you wish to intern at a startup', 'required' => TRUE),
    'question_whatSeparates'  => array('namespace' => 'profile', 'title' => 'Individuality', 'type' => 'textarea', 'placeholder' => 'What separates you from the rest', 'required' => TRUE),
    'url_linkedin'  => array('namespace' => 'profile', 'title' => 'Linkedin profile URL', 'type' => 'input'),
    'url_facebook'  => array('namespace' => 'profile', 'title' => 'Facebook profile URL', 'type' => 'input'),
    'url_twitter'  => array('namespace' => 'profile', 'title' => 'Twitter profile URL', 'type' => 'input'),
//    'internship_period'  => array('namespace' => 'profile', 'title' => 'Internship period', 'type' => 'input'),
);






$config['profile_fields']['startup'] = array(
    'name'  => array('namespace' => 'profile', 'title' => 'Startup name', 'type' => 'input', 'required' => TRUE),
    'city'  => array('namespace' => 'profile', 'title' => 'City', 'type' => 'input', 'required' => TRUE),
    'country'  => array('namespace' => 'profile', 'title' => 'Country', 'type' => 'country', 'required' => TRUE),
    'pitch'  => array('namespace' => 'profile', 'title' => 'Pitch', 'type' => 'textarea', 'placeholder' => 'Explain what your company does in one sentence', 'required' => TRUE),
    'industry'  => array('namespace' => 'profile', 'title' => 'Industry', 'type' => 'select', 'options' => array('business_services' => 'Business Services', 'computer_and_electronics' => 'Computer & Electronics', 'consumer_services' => 'Consumer Services', 'education' => 'Education', 'financial_services' => 'Financial Services', 'hpb' => 'Health & Biotech',  'manufacturing' => 'Manufacturing', 'media_and_entertainment' => 'Media & Entertainment', 'non_profit' => 'Non Profit', 'real_estate' => 'Real Estate & Construction', 'retail' => 'Retail', 'software_and_internet' => 'Software & Internet', 'telecommunications' => 'Telecommunications', 'travel_recreation_and_leisure' => 'Travel & Recreation', 'wholesale' => 'Wholesale & Distribution'), 'required' => TRUE),
    'url_website'  => array('namespace' => 'profile', 'title' => 'Website', 'type' => 'input', 'required' => TRUE),
    'url_website'  => array('namespace' => 'profile', 'title' => 'Website', 'type' => 'input', 'required' => TRUE),
    'cofounders'  => array('namespace' => 'profile', 'title' => 'Cofounders (one email per line)', 'type' => 'textarea'),
    'url_linkedin'  => array('namespace' => 'profile', 'title' => 'Linkedin profile URL', 'type' => 'input'),
    'url_facebook'  => array('namespace' => 'profile', 'title' => 'Facebook profile URL', 'type' => 'input'),
    'url_twitter'  => array('namespace' => 'profile', 'title' => 'Twitter profile URL', 'type' => 'input'),
);