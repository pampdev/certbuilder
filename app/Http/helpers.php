<?php
// helper functions
function wrap_field($formField, $name, $label, $errors, $default = null, $classes = array())
{
    $html = [];
    $class = 'form-group ' . implode(' ', $classes);
    if ($errors && $errors->has($name)) $class .= ' has-error has-feedback';

    if (is_null($default)) $default = Input::old($name);

    array_push($html, '<div class="' . $class . '">');
    if ($label != '<none>') {
        array_push($html, Form::label($name, $label, array('class' => 'control-label')));    
    }
    
    array_push($html, $formField);

    if ($errors && $errors->has($name)){
        array_push($html, '<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
        array_push($html, '<div class="help-block">');
        array_push($html, $errors->first( $name ));
        array_push($html, '</div>');
    }

    array_push($html, '</div>');

    return implode("\n", $html);
}

function textfield($name, $label, $errors, $default = null, $options = array())
{
    $defaultOptions = array('id' => 'field-' . $name);
    if (isset($options['class'])) {
        $options['class'] .= ' form-control';
    } else {
        $options['class'] = 'form-control';
    }

    $options = array_merge($defaultOptions, $options);
    $field = Form::text($name, $default, $options);
    $html = wrap_field($field, $name, text($label), $errors, $default);

    return $html;
}

function selectfield($name, $label, $options, $errors, $default = null)
{
    $field = Form::select($name, $options, $default, array('class' => 'form-control', 'id' => 'field-' . $name));
    $html = wrap_field($field, $name, text($label), $errors, $default);

    return $html;
}

// Form::macro('bsPassword', function($name, $label, $errors, $default = null)
// {
//     $html = '';

//     $field = Form::password($name, array('class' => 'form-control', 'id' => 'field-' . $name));
//     $html .= Form::bsFieldCommon($field, $name, text($label), $errors, $default);
    
//     $field = Form::password($name . '_confirmation', array('class' => 'form-control', 'id' => 'field-' . $name . '-confirmation'));
//     $html .= Form::bsFieldCommon($field, $name, text('Confirm ' . $name), $errors, $default);

//     return $html;
// });

// Form::macro('bsPasswordOnly', function($name, $label, $errors, $default = null)
// {
//     $html = '';

//     $field = Form::password($name, array('class' => 'form-control', 'id' => 'field-' . $name));
//     $html .= Form::bsFieldCommon($field, $name, text($label), $errors, $default);

//     return $html;
// });

// Form::macro('bsTextarea', function($name, $label, $errors, $default = null)
// {
//     $field = Form::textarea($name, $default, array('class' => 'form-control', 'id' => 'field-' . $name));
//     $html = Form::bsFieldCommon($field, $name, text($label), $errors, $default);

//     return $html;
// });

// Form::macro('bsSelect', function

// Form::macro('bsCheckboxes', function($name, $label, $options, $errors, $default = null, $classes = array())
// {
//     $field = '';
//     $i = 0;
//     foreach ($options as $key => $value) {
//         $checked = false;

//         if (is_null($default)) $default = Input::old($name);
//         if ($default && in_array($key, $default)) {
//             $checked = true;
//         }

//         $i++;
//         $field .= '<div class="checkbox">';
//         $field .= '<label>';
//         $field .= Form::checkbox($name . '[' . $i . ']', $key, $checked) . ' ' . $value;
//         $field .= '</label>';
//         $field .= '</div>';
//     }
    
//     $html = Form::bsFieldCommon($field, $name, text($label), $errors, $default, $classes);

//     return $html;
// });

// Form::macro('bsBoolean', function($name, $label, $errors, $default = null, $classes = array())
// {
//     $field = '';
//     $checked = false;

//     if (is_null($default)) $default = Input::old($name);
//     if ($default) {
//         $checked = true;
//     }

//     $field .= '<div class="checkbox">';
//     $field .= '<label>';
//     $field .= Form::checkbox($name . '[' . $name . ']', $label, $checked) . ' ' . $label;
//     $field .= '</label>';
//     $field .= '</div>';

    
//     $html = Form::bsFieldCommon($field, $name, '<none>', $errors, $default, $classes);

//     return $html;
// });

// Form::macro('bsHidden', function($name, $label, $errors, $default = null)
// {
//     $field = Form::textarea($name, $default, array('class' => 'form-control'));
//     $html = Form::bsFieldCommon($field, $name, text($label), $errors, $default);

//     return $html;
// });

// Form::macro('bsError', function($title, $errors)
// {
//     if (!$errors->any()) return '';

//     $html = array();

//     array_push($html, '<div class="alert alert-danger alert-dismissible" role="alert">');
//     array_push($html, '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>');
//     array_push($html, '<strong>' . $title . '</strong>');

//     array_push($html, 'We encountered the following errors:');

//     array_push($html, '<ul>');
//     foreach($errors->all() as $message) {
//         array_push($html, '<li>' . $message . '</li>');
//     }
//     array_push($html, '</ul>');
//     array_push($html, '</div>');

//     return implode("\n", $html);
// });

function text($text)
{
    if (!$text) {
        return false;
    }

    $translated = Lang::get('lang.' . $text);
    $translated = explode('.', $translated);

    if (count($translated) > 1) {
        return $text;
    }

    return $translated[0];
}

function displayAlert()
{
    $html = '';
    if (Session::has('message')) {
        $html .= flash_to_html(['success' => Session::get('message')]);
    }

    if (Session::has('error')) {
        $html .= flash_to_html(['error' => Session::get('error')]);
    }

    return $html;
}

function flash_to_html($session_msg) {
    $message_types = [];

    $messages = [];
    if (is_string($session_msg)) {
        $messages['success'][] = $session_msg;
    } else {
        $messages = $session_msg;
    }

    $tmp = [];
    foreach ($messages as $key => $session_msg_value) {
        $message_type = $key;
        if (!in_array($key, ['error', 'success'], true)) {
            $message_type = 'success';
        }

        if ($message_type == 'error') $message_type = 'danger';
        
        $tmp_msg = '';
        if (!is_array($session_msg_value)) {
            $tmp[$message_type][] = $session_msg_value;
        } else {
            if (!isset($tmp[$message_type])) {
                $tmp[$message_type] = $session_msg_value;
            } else {
                $tmp[$message_type] = array_merge($tmp[$message_type], $session_msg_value);    
            }
        }
    }

    foreach ($tmp as $type => $msg) {
        $html = '<ul>';
        foreach ($msg as $value) {
            $html .= '<li>' . $value . '</li>';
        }
        $html .= '</ul>';

        $message_types[$type] = sprintf('<div class="alert alert-%s">%s</div>', $type, $html);
    }

    return implode("", $message_types);
}