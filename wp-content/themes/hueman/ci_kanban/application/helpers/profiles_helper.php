<?php
function generateFieldsByProfileType($fields)
{
    $CI =& get_instance();

    $response = '';

    foreach($fields as $name => $field)
    {
        $field['value'] = ($CI->user->currentUserData->$name ? $CI->user->currentUserData->$name : FALSE);

        switch($field['type'])
        {
            case 'input':
                $response .= generate_inputField($name, 'profile', $field);
                break;

            case 'textarea':
                $response .= generate_textareaField($name, 'profile', $field);
                break;
        }
    }

    return $response;
}