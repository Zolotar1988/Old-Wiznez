<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Variable
{

    function options_yesNo()
    {
        return array('' => '-- Choose --', 1 => 'Yes', 0 => 'No');
    }

    function months()
    {
        return array('01' => "January", '02' => "February", '03' => "March", '04' => "April", '05' => "May", '06' => "June", '07' => "July", '08' => "August", '09' => "September", '10' => "October", '11' => "November", '12' => "December");
    }

    function days()
    {
        $start          = 0;
        $return         = array();

        while($start < 31)
        {
            $start++;
            $return[($start < 10 ? '0' . $start : $start)] = ($start < 10 ? '0' . $start : $start);
        }

        return $return;
    }

    function years()
    {
        $start          = date('Y') + 1;
        $return         = array();

        while($start > 1960)
        {
            --$start;
            $return[$start] = $start;
        }

        return $return;
    }


    function years_longer()
    {
        $start          = date('Y') + 1;
        $return         = array();

        while($start > 1950)
        {
            --$start;
            $return[$start] = $start;
        }

        return $return;
    }


    function monthsOptions()
    {
        return array
        (
            '1'		    => 'januar',
            '2'		    => 'februar',
            '3'		    => 'mars',
            '4'		    => 'april',
            '5'		    => 'mai',
            '6'		    => 'juni',
            '7'		    => 'juli',
            '8'		    => 'august',
            '9'		    => 'september',
            '10'	    => 'oktober',
            '11'	    => 'november',
            '12'	    => 'desember'
        );
    }


    function yearsOptions()
    {
        return array(2002, 2001,2000,1999,1998,1997,1996,1995,1994,1993,1992,1991,1990,1989,1988,1987,1986,1985,1984,1983,1982,1981,1980,1979,1978,1977,1976,1975);
    }


    function daysOptions()
    {
        return array (1,2,3,4,5,6,7,8,9,10,11,12,13,14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31 );
    }

}
