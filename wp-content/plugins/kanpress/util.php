<?php

/**
 * Miscellaneous useful functions
 */

/**
 * Transpose an array with the like [0] => (a, b) into [a] => b
 * 
 * Useful for <select> form controls
 *
 * @param   array $input Original array
 * @return  array        Transposed array
 */
function array_attribute_value( $input ) {
	$tmp = array( );

	$index_item = 0;
	$value_item = 1;

	//If the array is associative, associate the first key with the second one
	if ( isset( $input[ 0 ] ) ) {
		if ( !isset( $input[ 0 ][ 0 ] ) ) {
			$fields = array_keys( $input[ 0 ] );
			$index_item = $fields[ 0 ];
			$value_item = $fields[ 1 ];
		}
	}

	foreach ( $input as $row ) {
		$tmp[ $row[ $index_item ] ] = $row[ $value_item ];
	}
	return $tmp;
}

/**
 * Generate a <select> with specified options and a default value.
 * 
 * Supports nested arrays generating <optgroup />
 * 
 * @param string    $name         Value for the <select> "name" attribute
 * @param array     $options      Values for the <option> tags, for exaple
 *                                array('val1' => 'Value 1', 'val2' => 'Value2');
 * @param mixed     $selected			Default selected option
 * @param array     $attributes   Attributes for the <select> tag
 * @param array     $first_option	Associative array with value and caption for the first <option>
 * @return string   Generated HTML
 */
function form_select( $name, $options, $selected = null, $attributes = null, $first_option = array( '0' => 'Todos' ) ) {

	$selected = ( string ) $selected;

	$output = '<select name="' . $name . '"';
	$output = $output . ' id="' . $name . '"';
	if ( is_array( $attributes ) && count( $attributes ) ) {
		foreach ( $attributes as $n => $valor ) {
			$output .= ' ' . $n . '="' . $valor . '"';
		}
	}

	$output .= ">\n";

	//First option
	if ( is_array( $first_option ) ) {
		/** @todo Esto permite muchas "primeras opciones". ¿Es bueno o malo? */
		foreach ( $first_option as $i => $v ) {
			$output .= "<option value='" . $i . "'>" . $v . "</option>\n";
		}
	}

	foreach ( $options as $c => $v ) {

		if ( is_array( $v ) ) {
			$output .= "<optgroup label=\"$c\">\n";
			foreach ( $v as $subclave => $subvalor ) {
				$output .= '<option value="' . $subvalor . '"';
				if ( $subvalor == $selected )
					$output .= ' selected="selected"';
				$output .= '>' . $subclave . "</option>\n";
			}
			$output .= "</optgroup>\n";
		} else {
			$output .= '<option value="' . $c . '"';
			if ( $c == $selected )
				$output .= ' selected="selected"';
			$output .= '>' . $v . '</option>';
		}
	}
	$output .= '</select>';
	return $output;
}

/**
 * Cut string to n symbols and add delim but do not break words.
 *
 * Example:
 * <code>
 *  $string = 'this sentence is way too long';
 *  echo cortar_texto($string, 16);
 * </code>
 *
 * Output: 'this sentence is...'
 *
 * @author  Justin Cook
 * @url     http://www.justin-cook.com/wp/2006/06/27/php-trim-a-string-without-cutting-any-words/
 * @param   string      string we are operating with
 * @param   integer     character count to cut to
 * @param   string|NULL delimiter. Default: '...'
 * @return  string      processed string
 */
function trim_text( $str, $n, $delim = '...' ) {

	$len = strlen( $str );
	if ( $len > $n ) {
		preg_match( '/(.{' . $n . '}.*?)\b/', $str, $matches );
		return rtrim( $matches[ 1 ] ) . $delim;
	} else {
		return $str;
	}
}

/**
 * Print or return "no-valido" if the field didn't pass the validation
 *
 * @param string    $field              Field name
 * @param array     $validation_results	Validation results
 * @param boolean   $return             Whether to return or print it
 * @return string   "no-valido" if $devolver is true and the field is invalid
 */
function invalido( $field, $validation_results, $return = false ) {
	$output = '';

	if ( isset( $validation_results[ $field ] ) ) {
		$output = 'no-valido';
	}

	if ( true === $return )
		return $output;
	else
		echo $output;
}

/**
 * Prints or returns a POST param if it exists.
 * 
 * @param string $key Parameter key
 */
function post( $key, $return = false ) {
	if ( !$return ) {
		if ( isset( $_POST[ $key ] ) ) {
				echo $_POST[ $key ];
			}
	} else {
		if ( isset( $_POST[ $key ] ) ) {
			return $_POST[ $key ];
		} else {
			return null;
		}
	}
}

/**
 * Print a string escaping UTF-8 characters to HTML entities.
 * 
 * @param string $string The string to print
 */
function e( $string ) {
	echo htmlentities( $string, null, 'UTF-8' );
}
