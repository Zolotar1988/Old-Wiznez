<html>
<head>
    <meta charset="utf-8">
    <title>Form</title>
</head>


<body>
<style type="text/css">
    <!--
    .style6 {font-size: 10}
    .style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
    .style11 {font-size: 12px}
    .style14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
    .style17 {font-family: Verdana, Arial, Helvetica, sans-serif}
    -->
</style>

<form id="form1" name="form1" method="post" action="/boat/process.php">
<table width="100%" border="0">
<tr>
    <td width="10%"><span class="style10">Type</span></td>
    <td width="11%"><span class="style10">Kjønn:</span></td>
    <td width="16%"><span class="style10">Fornavn:</span></td>
    <td width="15%"><span class="style10">Etternavn:</span></td>
    <td width="7%"><span class="style10">Alder:</span></td>
    <td width="24%">&nbsp;</td>
    <td width="17%"><span class="style10">Nasjonalitet</span></td>
</tr>
<tr>
    <td><span class="style10">Voksen 1:</span></td>
    <td><span class="style10">
        <select name="v1_sex" id="v1_sex">
            <option value="">Velg kjønn</option>
            <option value="kvinne">Kvinne</option>
            <option value="mann">Mann</option>
        </select>
      </span></td>
    <td><span class="style10">
        <label>
            <input type="text" name="V1_sur" id="V1_sur" />
        </label>
      </span></td>
    <td><input name="V1_last" type="text" id="V1_last" /></td>
    <td><span class="style10">
        <label>
            <select name="V1_Alder" id="V1_Alder">
                <option value="">Velg</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
                <option value="32">32</option>
                <option value="33">33</option>
                <option value="34">34</option>
                <option value="35">35</option>
                <option value="36">36</option>
                <option value="37">37</option>
                <option value="38">38</option>
                <option value="39">39</option>
                <option value="40">40</option>
                <option value="41">41</option>
                <option value="42">42</option>
                <option value="43">43</option>
                <option value="44">44</option>
                <option value="45">45</option>
                <option value="46">46</option>
                <option value="47">47</option>
                <option value="48">48</option>
                <option value="49">49</option>
                <option value="50">50</option>
                <option value="51">51</option>
                <option value="52">52</option>
                <option value="53">53</option>
                <option value="54">54</option>
                <option value="55">55</option>
                <option value="56">56</option>
                <option value="57">57</option>
                <option value="58">58</option>
                <option value="59">59</option>
                <option value="60">60</option>
                <option value="61">61</option>
                <option value="62">62</option>
                <option value="63">63</option>
                <option value="64">64</option>
                <option value="65">65</option>
                <option value="66">66</option>
                <option value="67">67</option>
                <option value="68">68</option>
                <option value="69">69</option>
                <option value="70">70</option>
                <option value="71">71</option>
                <option value="72">72</option>
                <option value="73">73</option>
                <option value="74">74</option>
                <option value="75">75</option>
                <option value="76">76</option>
                <option value="77">77</option>
                <option value="78">78</option>
                <option value="79">79</option>
                <option value="80">80</option>
                <option value="81">81</option>
                <option value="82">82</option>
                <option value="83">83</option>
                <option value="84">84</option>
                <option value="85">85</option>
                <option value="86">86</option>
                <option value="87">87</option>
                <option value="88">88</option>
                <option value="89">89</option>
                <option value="90">90</option>
            </select>
        </label>
      </span></td>
    <td><span class="style10">
        <label>
            <input type="checkbox" name="V1_ikkevin" id="V1_ikkevin" />
        </label>
      Øsnker ikke vinmeny*</span></td>
    <td><span class="style10">
        <label>
            <select name="V1_nat" id="V1_nat">
                <option value="" selected="selected">Velg</option>
                <option value="Norsk">Norsk</option>
                <option value="Tysk">Tysk</option>
                <option value="Svensk">Svensk</option>
                <option value="Amerikansk">Amerikansk</option>
                <option value="Dansk">Dansk</option>
            </select>
        </label>
      </span></td>
</tr>
<tr>
    <td><span class="style10">Voksen 2:</span></td>
    <td><span class="style10">
        <label>
            <select name="v2_sex" id="v2_sex">
                <option value="">Velg kjønn</option>
                <option value="kvinne">Kvinne</option>
                <option value="mann">Mann</option>
            </select>
        </label>
      </span></td>
    <td><input name="V2_sur" type="text" id="V2_sur" /></td>
    <td><input name="V2_last" type="text" id="V2_last" /></td>
    <td><span class="style10">
        <select name="V2_Alder2" id="V2_Alder2">
            <option value="">Velg</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
            <option value="61">61</option>
            <option value="62">62</option>
            <option value="63">63</option>
            <option value="64">64</option>
            <option value="65">65</option>
            <option value="66">66</option>
            <option value="67">67</option>
            <option value="68">68</option>
            <option value="69">69</option>
            <option value="70">70</option>
            <option value="71">71</option>
            <option value="72">72</option>
            <option value="73">73</option>
            <option value="74">74</option>
            <option value="75">75</option>
            <option value="76">76</option>
            <option value="77">77</option>
            <option value="78">78</option>
            <option value="79">79</option>
            <option value="80">80</option>
            <option value="81">81</option>
            <option value="82">82</option>
            <option value="83">83</option>
            <option value="84">84</option>
            <option value="85">85</option>
            <option value="86">86</option>
            <option value="87">87</option>
            <option value="88">88</option>
            <option value="89">89</option>
            <option value="90">90</option>
        </select>
      </span></td>
    <td><span class="style10">
        <label>
            <input type="checkbox" name="V2_ikkevin" id="V2_ikkevin" />
        </label>
Øsnker ikke vinmeny*</span></td>
    <td><span class="style10">
        <select name="V2_nat" id="V2_nat">
            <option value="" selected="selected">Velg</option>
            <option value="Norsk">Norsk</option>
            <option value="Tysk">Tysk</option>
            <option value="Svensk">Svensk</option>
            <option value="Amerikansk">Amerikansk</option>
            <option value="Dansk">Dansk</option>
        </select>
      </span></td>
</tr>
<tr>
    <td colspan="7"><span class="style10">* Dersom man ikke ønsker vinmeny kan man velge øl og mineralvann fra a la carte meny.</span></td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><p class="style10"><strong>Tilvalg – Lugaroppgradering</strong> (3-stjerners innvendig lugar er standard)<br />
        Kryss av for ønsket oppgradering.</p>      </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
        <label>
            <input type="radio" name="lugaroppgradering" value="3-stjerners utvendig lugar">
        </label>
      3-stjerners utvendig lugar (kr. 400)</span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td>&nbsp;</td>
    <td><span class="style6"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
            <input type="radio" name="lugaroppgradering" value="4-stjerners Color Class lugar">
      4-stjerners Color Class lugar (kr. 1100)</span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td>&nbsp;</td>
    <td><span class="style6"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
            <input type="radio" name="lugaroppgradering" value="5-stjerners Color Suite">
      5-stjerners Color Suite (kr. 2200)</span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td>&nbsp;</td>
    <td><span class="style6"></span></td>
</tr>
<tr>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td><span class="style6"></span></td>
    <td>&nbsp;</td>
    <td><span class="style6"></span></td>
</tr>
<tr>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="3"><span class="style14">Tillegg for singellugar:</span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
        <label>
            <input type="radio" name="singellugarTillegg" value="3-stjerners lugar">
        </label>
      3-stjerners lugar (kr. 800)</span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
            <input type="radio" name="singellugarTillegg" value="4-stjerners lugar">
      4-stjerners lugar (kr. 1100) </span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td colspan="3"><span class="style10">
            <input type="radio" name="singellugarTillegg" value="5-stjerners Color Suite">
      5-stjerners Color Suite (kr. 2200)</span></td>
    <td><span class="style11"></span></td>
    <td><span class="style11"></span></td>
    <td>&nbsp;</td>
    <td><span class="style11"></span></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><span class="style14">Påmelding av barn:</span></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td><span class="style10">Type</span></td>
    <td><span class="style10">Kjønn:</span></td>
    <td><span class="style10">Fornavn:</span></td>
    <td><span class="style10">Etternavn:</span></td>
    <td><span class="style10">Alder:</span></td>
    <td>&nbsp;</td>
    <td><span class="style10">Nasjonalitet</span></td>
</tr>
<tr>
    <td><span class="style10">Barn 1:</span></td>
    <td><span class="style10">
        <select name="b1_sex" id="b1_sex">
            <option value="">Velg kjønn</option>
            <option value="jente">Jente</option>
            <option value="gutt">Gutt</option>
        </select>
      </span></td>
    <td><input name="b1_for" type="text" id="b1_for" /></td>
    <td><input name="b1_etter" type="text" id="b1_etter" /></td>
    <td><span class="style10">
        <select name="b1_Alder" id="b1_Alder">
            <option value="0" selected="selected">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
        </select>
      </span></td>
    <td>&nbsp;</td>
    <td><span class="style10">
        <select name="b1_nat" id="b1_nat">
            <option value="" selected="selected">Velg</option>
            <option value="Norsk">Norsk</option>
            <option value="Tysk">Tysk</option>
            <option value="Svensk">Svensk</option>
            <option value="Amerikansk">Amerikansk</option>
            <option value="Dansk">Dansk</option>
        </select>
      </span></td>
</tr>
<tr>
    <td><span class="style10">Barn 2:</span></td>
    <td><span class="style10">
        <select name="b2_sex" id="b2_sex">
            <option value="">Velg kjønn</option>
            <option value="jente">Jente</option>
            <option value="gutt">Gutt</option>
        </select>
      </span></td>
    <td><input name="b2_for" type="text" id="b2_for" /></td>
    <td><input name="b2_etter" type="text" id="b2_etter" /></td>
    <td><span class="style10">
        <select name="b2_Alder" id="b2_Alder">
            <option value="0" selected="selected">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
        </select>
      </span></td>
    <td>&nbsp;</td>
    <td><span class="style10">
        <select name="b2_nat" id="b2_nat">
            <option value="" selected="selected">Velg</option>
            <option value="Norsk">Norsk</option>
            <option value="Tysk">Tysk</option>
            <option value="Svensk">Svensk</option>
            <option value="Amerikansk">Amerikansk</option>
            <option value="Dansk">Dansk</option>
        </select>
      </span></td>
</tr>
<tr>
    <td><span class="style10">Barn 3:</span></td>
    <td><span class="style10">
        <select name="b3_sex" id="b3_sex">
            <option value="">Velg kjønn</option>
            <option value="jente">Jente</option>
            <option value="gutt">Gutt</option>
        </select>
      </span></td>
    <td><input name="b3_for" type="text" id="b3_for" /></td>
    <td><input name="b3_etter" type="text" id="b3_etter" /></td>
    <td><span class="style10">
        <select name="b3_Alder" id="b3_Alder">
            <option value="0" selected="selected">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
        </select>
      </span></td>
    <td>&nbsp;</td>
    <td><span class="style10">
        <select name="b3_nat" id="b3_nat">
            <option value="" selected="selected">Velg</option>
            <option value="Norsk">Norsk</option>
            <option value="Tysk">Tysk</option>
            <option value="Svensk">Svensk</option>
            <option value="Amerikansk">Amerikansk</option>
            <option value="Dansk">Dansk</option>
        </select>
      </span></td>
</tr>
<tr>
    <td colspan="7"><span class="style11"></span><span class="style11"></span><span class="style11"></span><span class="style11"></span><span class="style11"></span>
        <p><span class="style10">NB! Barn under 4 år får ikke noe passasjertillegg.<br />
          Lugarpris pr barn over 4 år er kr. 470,-
            </span>
            <!-- o ignored -->
            <br />
            <span class="style10">Booking av lugar for evt. ledsagere/barnepass skjer direkte på Color Line sine nettsider her: <a href="http://www.colorline.no/" target="_blank">www.colorline.no</a></span> </p>
        <span class="style11"></span></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><span class="style10">Tilvalg – sammenkoplede lugarer med dører imellom (kr. xxx)</span></td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><p>
        <label>
        <span class="style17">
        <span class="style11">
        <input name="koblet_foreldre_barn" type="checkbox" id="koblet_foreldre_barn" value="koblet_foreldre_barn" />
        </span></span></label>
        <span class="style10">
        Ønskes mellom foreldre/barn
        <!-- o ignored -->
            <br />
            <input name="koblet_ledsager_barn" type="checkbox" id="koblet_ledsager_barn" value="koblet_ledsager_barn" />
Ønskes mellom ledsager/barn*</span></p>
        <p class="style10">*Vennligst opplys navn på ledsager så jeg får informert Color Line om dette ønske.<br />
            Ledsagers navn:
            <!-- o ignored -->
            <label>
                <input type="text" name="Ledsager" id="Ledsager" />
            </label>
        </p>      </td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><p class="style10"><strong>Tilvalg for antall barn som skal delta på fellesmåltidene
    </strong>
        <!-- o ignored -->
    </p>
        <p class="style10">
            <!-- o ignored -->
            <label>
                <input name="Ant_barn_middag" type="text" id="Ant_barn_middag" size="4" />
            </label>
            barn deltar på middagen dag 1 og ønsker
            <!-- o ignored -->
        </p>
        <p class="style10">
            <!-- o ignored -->
            <input name="Voksenmeny_middag" type="text" id="Voksenmeny_middag" size="4" />
            x  voksenmeny (Inkl brus/mineralvann - kr. 500,-)
            <!-- o ignored -->
        </p>
        <p class="style10">
            <!-- o ignored -->
            <input name="barnemeny_middag" type="text" id="barnemeny_middag" size="4" />
            x Captain Kid barnemeny (Inkl. hovedrett, dessert og drikke - kr. 110,-)
            <!-- o ignored -->
            <br />
            <br />
        </p>
        <p class="style10">
            <!-- o ignored -->
            <input name="Ant_barn_lunsj" type="text" id="Ant_barn_lunsj" size="4" />
            barn deltar på lunsj dag 2 og ønsker
            <!-- o ignored -->
        </p>
        <p class="style10">
            <!-- o ignored -->
            <input name="voksenmeny_lunsj" type="text" id="voksenmeny_lunsj" size="4" />
            x  voksenmeny lunsj (kr. 189,-)
            <!-- o ignored -->
        </p>
        <p class="style10">
            <!-- o ignored -->
            <input name="barnemeny_lunsj" type="text" id="barnemeny_lunsj" size="4" />
            x barnemeny lunsj (kr. 110,-)</p>      </td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7">&nbsp;</td>
</tr>
<tr>
    <td colspan="7"><label>
        <input type="submit" name="button" id="button" value="Send bestilling" />

        <input type="reset" name="button2" id="button2" value="Nullstill skjema" />
    </label></td>
</tr>
</table>
</form>
</body>

</html>