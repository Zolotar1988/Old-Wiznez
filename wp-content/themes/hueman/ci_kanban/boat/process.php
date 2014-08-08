<?php
# Change some values
foreach($_POST as $key => $val)
{
    if($key == 'V1_ikkevin' || $val == 'V1_ikkevin') $_POST[$key] = 'Vil ikke vin';
    if($key == 'V2_ikkevin' || $val == 'V2_ikkevin') $_POST[$key] = 'Vil ikke vin';
    if($key == 'koblet_ledsager_barn' || $val == 'koblet_ledsager_barn') $_POST[$key] = 'Ja';
    if($key == 'koblet_foreldre_barn' || $val == 'koblet_foreldre_barn') $_POST[$key] = 'Ja';
}


# Requires
require('./sendmail.php');
require('./dbinsert.php');


# Just the function that returns the value, this is for the insert - look
function returnSelf($x) { return $x; }


# Here is the relationship between keys and their titles
$keyTitleRelationship = array(
    'v1_sex' => 'Voksen 1 - Kjønn',
    'V1_sur' => 'Voksen 1 - Fornavn',
    'V1_last' => 'Voksen 1 - Etternavn',
    'V1_Alder' => 'Voksen 1 - Alder',
    'V1_nat' => 'Voksen 1 - Nasjonalitet',
    'v2_sex' => 'Voksen 2 - Kjønn',
    'V2_sur' => 'Voksen 2 - Fornavn',
    'V2_last' => 'Voksen 2 - Etternavn',
    'V2_Alder2' => 'Voksen 2 - Alder',
    'V2_nat' => 'Voksen 2 - Nasjonalitet',
    'b1_sex' => 'Barn 1 - Kjønn',
    'b1_for' => 'Barn 1 - Fornavn',
    'b1_etter' => 'Barn 1 - Etternavn',
    'b1_Alder' => 'Barn 1 - Alder',
    'b1_nat' => 'Barn 1 - Nasjonalitet',
    'b2_sex' => 'Barn 2 - Kjønn',
    'b2_for' => 'Barn 2 - Fornavn',
    'b2_etter' => 'Barn 2 - Etternavn',
    'b2_Alder' => 'Barn 2 - Alder',
    'b2_nat' => 'Barn 2 - Nasjonalitet',
    'b3_sex' => 'Barn 3 - Kjønn',
    'b3_for' => 'Barn 3 - Fornavn',
    'b3_etter' => 'Barn 3 - Etternavn',
    'b3_Alder' => 'Barn 3 - Alder',
    'b3_nat' => 'Barn 3 - Nasjonalitet',
    'Ledsager' => 'Ledsager',
    'V1_ikkevin' => 'Voksen 1 - Vin',
    'V2_ikkevin' => 'Voksen 2 - Vin',
    'koblet_ledsager_barn' => 'sammenkoplede - Ønskes mellom ledsager/barn',
    'koblet_foreldre_barn' => 'sammenkoplede - Ønskes mellom foreldre/barn',
    'Ant_barn_middag' => 'barn deltar på middagen dag 1',
    'Voksenmeny_middag' => 'voksenmeny (Inkl brus/mineralvann - kr. 500,-)',
    'barnemeny_middag' => 'Captain Kid barnemeny (Inkl. hovedrett, dessert og drikke - kr. 110,-) ',
    'Ant_barn_lunsj' => 'barn deltar på lunsj dag 2 og ønsker',
    'voksenmeny_lunsj' => 'voksenmeny lunsj (kr. 189,-)',
    'barnemeny_lunsj' => 'barnemeny lunsj (kr. 110,-)',
    'lugaroppgradering' => 'Lugaroppgradering',
    'singellugarTillegg' => 'Tillegg for singellugar',
    'button' => 'Clicked',
);


# Submit to the database
dbinsert::submit_form();

# Send the email
sendmail::send($keyTitleRelationship);

# A nice, friendly message
echo 'Form processed and email sent';