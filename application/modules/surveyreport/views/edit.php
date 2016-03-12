<script>
     var savedcountry = '';
    </script>
<?php
$attributes = array('name' => 'edit_report', 'id' => 'edit_report', 'class' => 'edit_report');
//var_dump($contact_fields);
//die;
?>
<div class="formInner survey-report">
    <h3>Edit Contact Fields</h3>
    <div class="form-left">
    <?php echo form_open(base_url() . 'surveyreport/edit/' . $survey_id . '/' . $user_id, $attributes); ?>  
    <?php foreach ($contact_fields as $contact_field): ?>
        <fieldset>
            <label for="device"><?php echo $contact_field->name; ?></label>
            <?php if($contact_field->contact_form_field_id == 33):?>
            <script>
                savedcountry = '<?php echo $contact_field->value; ?>';
                </script>
            <select id="country" name="cids[<?php echo $contact_field->contact_form_field_id; ?>]">
                
            </select>
            <?php else:?>
                <input type="text" name="cids[<?php echo $contact_field->contact_form_field_id; ?>]" value="<?php echo $contact_field->value; ?>" />
            <?php endif;?>
        
        </fieldset>
    <?php endforeach; ?>
    <div class="nextBtn surveyreportnxt">
        <input type="submit" value="<?php if($next_response)  echo 'Save and Next'; else echo 'Save' ?>" class="survey-update">
        <?php if($next_response):?>
         <input type="hidden" name="nxtval" value="<?php echo $next_response->survey_user_id;?>" class="survey-update">
        <!--<a href="<?php echo base_url().'surveyreport/edit/'.$survey_id.'/'.$next_response->survey_user_id;?>">Next</a>-->
        <?php endif;?>
    </div>
    <?php echo form_close(); ?>
    </div>
    <div class="business-image">
        <?php if($bcard):?>
        <a href="<?php echo $bcard?>" target="_blank"><img src="<?php echo $bcard?>" /></a>
        <?php endif;?>
    </div>
</div>

<script>
$(document).ready(function(){
    
    ﻿var countries = [
    {"iso": "DE", "country": "Germany", "c_code": 49, "c_prefix": 0},
    {"iso": "CH", "country": "Switzerland", "c_code": 41, "c_prefix": 0},
    {"iso": "AT", "country": "Austria", "c_code": 43, "c_prefix": 0},
    {"iso": "FR", "country": "France", "c_code": 33, "c_prefix": 0},
    {"iso": "GB", "country": "United Kingdom", "c_code": 44, "c_prefix": 0},
    {"iso": "AF", "country": "Afghanistan", "c_code": 93, "c_prefix": 0},
    {"iso": "AX", "country": "Åland Islands", "c_code": 358, "c_prefix": 0},
    {"iso": "AL", "country": "Albania", "c_code": 355, "c_prefix": 0},
    {"iso": "DZ", "country": "Algeria", "c_code": 213, "c_prefix": 0},
    {"iso": "AS", "country": "American Samoa", "c_code": 1, "c_prefix": -1},	
    {"iso": "AD", "country": "Andorra", "c_code": 376, "c_prefix": -1},	
    {"iso": "AO", "country": "Angola", "c_code": 244, "c_prefix": 0},
    {"iso": "AI", "country": "Anguilla", "c_code": 1, "c_prefix": -1},	
    {"iso": "EH", "country": "Antarctica", "c_code": -1, "c_prefix":	-1},	
    {"iso": "AG", "country": "Antigua and Barbuda", "c_code": 1, "c_prefix": -1},	
    {"iso": "AR", "country": "Argentina", "c_code": 54, "c_prefix": 0},
    {"iso": "AM", "country": "Armenia", "c_code": 374, "c_prefix": 0},
    {"iso": "AW", "country": "Aruba", "c_code": 297, "c_prefix": -1},	
    {"iso": "AU", "country": "Australia", "c_code": 61, "c_prefix": 0},
    {"iso": "AZ", "country": "Azerbaijan", "c_code": 994, "c_prefix": 0},
    {"iso": "BS", "country": "Bahamas", "c_code": 1, "c_prefix": -1},	
    {"iso": "BH", "country": "Bahrain", "c_code": 973, "c_prefix": -1},	
    {"iso": "BD", "country": "Bangladesh", "c_code": 880, "c_prefix": 0},
    {"iso": "BB", "country": "Barbados", "c_code": 1, "c_prefix": -1},	
    {"iso": "BY", "country": "Belarus", "c_code": 375, "c_prefix": 8},
    {"iso": "BE", "country": "Belgium", "c_code": 32, "c_prefix": 0},
    {"iso": "BZ", "country": "Belize", "c_code": 501, "c_prefix": -1},	
    {"iso": "BJ", "country": "Benin", "c_code": 229, "c_prefix": -1},	
    {"iso": "BM", "country": "Bermuda", "c_code": 1, "c_prefix": -1},	
    {"iso": "BT", "country": "Bhutan", "c_code": 975, "c_prefix": -1},	
    {"iso": "BO", "country": "Bolivia", "c_code": 591, "c_prefix": 0},
    {"iso": "BQ", "country": "Bonaire / Sint Eustatius and Saba", "c_code": 599, "c_prefix": 0},
    {"iso": "BA", "country": "Bosnia and Herzegovina", "c_code": 387, "c_prefix": 0},
    {"iso": "BW", "country": "Botswana", "c_code": 267, "c_prefix": -1},	
    {"iso": "BV", "country": "Bouvet Island", "c_code": -1, "c_prefix":	-1},	
    {"iso": "BR", "country": "Brazil", "c_code": 55, "c_prefix": 0},
    {"iso": "IO", "country": "British Indian Ocean Territory", "c_code": 246, "c_prefix": -1},	
    {"iso": "BN", "country": "Brunei Darussalam", "c_code": 673, "c_prefix": -1},	
    {"iso": "BG", "country": "Bulgaria", "c_code": 359, "c_prefix": 0},
    {"iso": "BF", "country": "Burkina Faso", "c_code": 226, "c_prefix": -1},	
    {"iso": "BI", "country": "Burundi", "c_code": 257, "c_prefix": -1},	
    {"iso": "KH", "country": "Cambodia", "c_code": 855, "c_prefix": 0},
    {"iso": "CM", "country": "Cameroon", "c_code": 237, "c_prefix": -1},	
    {"iso": "CA", "country": "Canada", "c_code": 1, "c_prefix": -1},	
    {"iso": "CV", "country": "Cape Verde", "c_code": 238, "c_prefix": -1},	
    {"iso": "KY", "country": "Cayman Islands", "c_code": 1, "c_prefix": -1},	
    {"iso": "CF", "country": "Central African Republic", "c_code": 236, "c_prefix": -1},	
    {"iso": "TD", "country": "Chad", "c_code": 235, "c_prefix": -1},	
    {"iso": "CL", "country": "Chile", "c_code": 56, "c_prefix": 1},
    {"iso": "CN", "country": "China", "c_code": 86, "c_prefix": 0},
    {"iso": "CX", "country": "Christmas Island", "c_code": 61, "c_prefix": 0},
    {"iso": "CC", "country": "Cocos (Keeling) Islands", "c_code": 61, "c_prefix": 0},
    {"iso": "CO", "country": "Colombia", "c_code": 57, "c_prefix": 0},
    {"iso": "KM", "country": "Comoros", "c_code": 269, "c_prefix": -1},	
    {"iso": "CG", "country": "Congo", "c_code": 242, "c_prefix": -1},	
    {"iso": "CD", "country": "Congo (Democratic Republic, former Zaire)", "c_code": 243, "c_prefix": 0},
    {"iso": "CK", "country": "Cook Islands", "c_code": 682, "c_prefix": -1},	
    {"iso": "CR", "country": "Costa Rica", "c_code": 506, "c_prefix": -1},	
    {"iso": "CI", "country": "Côte D'Ivoire", "c_code": 225, "c_prefix": -1},	
    {"iso": "HR", "country": "Croatia", "c_code": 385, "c_prefix": 0},
    {"iso": "CU", "country": "Cuba", "c_code": 53, "c_prefix": 0},
    {"iso": "CW", "country": "Curaçao", "c_code": 599, "c_prefix": 0},
    {"iso": "CY", "country": "Cyprus", "c_code": 357, "c_prefix": -1},	
    {"iso": "CZ", "country": "Czech Republic", "c_code": 420, "c_prefix": -1},	
    {"iso": "DK", "country": "Denmark", "c_code": 45, "c_prefix": -1},	
    {"iso": "DJ", "country": "Djibouti", "c_code": 253, "c_prefix": -1},	
    {"iso": "DM", "country": "Dominica", "c_code": 1, "c_prefix": -1},	
    {"iso": "DO", "country": "Dominican Republic", "c_code": 1, "c_prefix": -1},	
    {"iso": "EC", "country": "Ecuador", "c_code": 593, "c_prefix": 0},
    {"iso": "EG", "country": "Egypt", "c_code": 20, "c_prefix": 0},
    {"iso": "SV", "country": "El Salvador", "c_code": 503, "c_prefix": -1},	
    {"iso": "GQ", "country": "Equatorial Guinea", "c_code": 240, "c_prefix": -1},	
    {"iso": "ER", "country": "Eritrea", "c_code": 291, "c_prefix": 0},
    {"iso": "EE", "country": "Estonia", "c_code": 372, "c_prefix": -1},	
    {"iso": "ET", "country": "Ethiopia", "c_code": 251, "c_prefix": 0},
    {"iso": "FK", "country": "Falkland Islands (Malvinas)", "c_code": 500, "c_prefix": -1},	
    {"iso": "FO", "country": "Faroe Islands", "c_code": 298, "c_prefix": -1},	
    {"iso": "FJ", "country": "Fiji", "c_code": 679, "c_prefix": -1},	
    {"iso": "FI", "country": "Finland", "c_code": 358, "c_prefix": 0},
    {"iso": "GF", "country": "French Guiana", "c_code": 594, "c_prefix": -1},	
    {"iso": "PF", "country": "French Polynesia", "c_code": 689, "c_prefix": -1},	
    {"iso": "TF", "country": "French Southern Territories", "c_code": 262, "c_prefix": -1},	
    {"iso": "GA", "country": "Gabon", "c_code": 241, "c_prefix": -1},	
    {"iso": "GM", "country": "Gambia", "c_code": 220, "c_prefix": -1},	
    {"iso": "GE", "country": "Georgia", "c_code": 995, "c_prefix": 8},
    {"iso": "GH", "country": "Ghana", "c_code": 233, "c_prefix": 0},
    {"iso": "GI", "country": "Gibraltar", "c_code": 350, "c_prefix": -1},	
    {"iso": "GR", "country": "Greece", "c_code": 30, "c_prefix": 0},
    {"iso": "GL", "country": "Greenland", "c_code": 299, "c_prefix": -1},	
    {"iso": "GD", "country": "Grenada", "c_code": 1, "c_prefix": -1},	
    {"iso": "GP", "country": "Guadeloupe", "c_code": 590, "c_prefix": -1},	
    {"iso": "GU", "country": "Guam", "c_code": 1, "c_prefix": -1},	
    {"iso": "GT", "country": "Guatemala", "c_code": 502, "c_prefix": -1},	
    {"iso": "GG", "country": "Guernsey", "c_code": 44, "c_prefix": 0},
    {"iso": "GN", "country": "Guinea", "c_code": 224, "c_prefix": -1},	
    {"iso": "GW", "country": "Guinea-Bissau", "c_code": 245, "c_prefix": -1},	
    {"iso": "GY", "country": "Guyana", "c_code": 592, "c_prefix": -1},	
    {"iso": "HT", "country": "Haiti", "c_code": 509, "c_prefix": -1},	
    {"iso": "HM", "country": "Heard Island and McDonald Islands", "c_code": 61, "c_prefix": 0},
    {"iso": "VA", "country": "Holy See (Vatican City State)", "c_code": 39, "c_prefix": -1},	
    {"iso": "HN", "country": "Honduras", "c_code": 504, "c_prefix": -1},	
    {"iso": "HK", "country": "Hong Kong", "c_code": 852, "c_prefix": -1},	
    {"iso": "HU", "country": "Hungary", "c_code": 36, "c_prefix": 06},
    {"iso": "IS", "country": "Iceland", "c_code": 354, "c_prefix": -1},	
    {"iso": "IN", "country": "India", "c_code": 91, "c_prefix": 0},
    {"iso": "ID", "country": "Indonesia", "c_code": 62, "c_prefix": 0},
    {"iso": "IR", "country": "Iran", "c_code": 98, "c_prefix": 0},
    {"iso": "IQ", "country": "Iraq", "c_code": 964, "c_prefix": 0},
    {"iso": "IE", "country": "Ireland", "c_code": 353, "c_prefix": 0},
    {"iso": "IM", "country": "Isle of Man", "c_code": 44, "c_prefix": 0},
    {"iso": "IL", "country": "Israel", "c_code": 972, "c_prefix": 0},
    {"iso": "IT", "country": "Italy", "c_code": 39, "c_prefix": -1},	
    {"iso": "JM", "country": "Jamaica", "c_code": 1, "c_prefix": -1},	
    {"iso": "JP", "country": "Japan", "c_code": 81, "c_prefix": 0},
    {"iso": "JE", "country": "Jersey", "c_code": 44, "c_prefix": 0},
    {"iso": "JO", "country": "Jordan", "c_code": 962, "c_prefix": 0},
    {"iso": "KZ", "country": "Kazakhstan", "c_code": 7, "c_prefix": 8},
    {"iso": "KE", "country": "Kenya", "c_code": 254, "c_prefix": 0},
    {"iso": "KI", "country": "Kiribati", "c_code": 686, "c_prefix": -1},	
    {"iso": "KW", "country": "Kuwait", "c_code": 965, "c_prefix": -1},	
    {"iso": "KG", "country": "Kyrgyzstan", "c_code": 996, "c_prefix": 0},
    {"iso": "LA", "country": "Lao People's Democratic Republic", "c_code": 856, "c_prefix": 0},
    {"iso": "LV", "country": "Latvia", "c_code": 371, "c_prefix": -1},	
    {"iso": "LB", "country": "Lebanon", "c_code": 961, "c_prefix": 0},
    {"iso": "LS", "country": "Lesotho", "c_code": 266, "c_prefix": -1},	
    {"iso": "LR", "country": "Liberia", "c_code": 231, "c_prefix": -1},	
    {"iso": "LY", "country": "Libya", "c_code": 218, "c_prefix": 0},
    {"iso": "LI", "country": "Liechtenstein", "c_code": 423, "c_prefix": -1},	
    {"iso": "LT", "country": "Lithuania", "c_code": 370, "c_prefix": 0},
    {"iso": "LU", "country": "Luxembourg", "c_code": 352, "c_prefix": -1},	
    {"iso": "MO", "country": "Macao", "c_code": 853, "c_prefix": -1},	
    {"iso": "MK", "country": "Macedonia", "c_code": 389, "c_prefix": 0},
    {"iso": "MG", "country": "Madagascar", "c_code": 261, "c_prefix": -1},	
    {"iso": "MW", "country": "Malawi", "c_code": 265, "c_prefix": -1},	
    {"iso": "MY", "country": "Malaysia", "c_code": 60, "c_prefix": 0},
    {"iso": "MV", "country": "Maldives", "c_code": 960, "c_prefix": -1},	
    {"iso": "ML", "country": "Mali", "c_code": 223, "c_prefix": -1},	
    {"iso": "MT", "country": "Malta", "c_code": 356, "c_prefix": -1},	
    {"iso": "MH", "country": "Marshall Islands", "c_code": 692, "c_prefix": 1},
    {"iso": "MQ", "country": "Martinique", "c_code": 596, "c_prefix": -1},	
    {"iso": "MR", "country": "Mauritania", "c_code": 222, "c_prefix": -1},	
    {"iso": "MU", "country": "Mauritius", "c_code": 230, "c_prefix": -1},	
    {"iso": "YT", "country": "Mayotte", "c_code": 269, "c_prefix": -1},	
    {"iso": "MX", "country": "Mexico", "c_code": 52, "c_prefix": 1},
    {"iso": "FM", "country": "Micronesia", "c_code": 691, "c_prefix": 1},
    {"iso": "MD", "country": "Moldova", "c_code": 373, "c_prefix": 0},
    {"iso": "MC", "country": "Monaco", "c_code": 377, "c_prefix": -1},	
    {"iso": "MN", "country": "Mongolia", "c_code": 976, "c_prefix": 0},
    {"iso": "ME", "country": "Montenegro", "c_code": 382, "c_prefix": 0},
    {"iso": "MS", "country": "Montserrat", "c_code": 1, "c_prefix": -1},	
    {"iso": "MA", "country": "Morocco", "c_code": 212, "c_prefix": 0},
    {"iso": "MZ", "country": "Mozambique", "c_code": 258, "c_prefix": -1},	
    {"iso": "MM", "country": "Myanmar", "c_code": 95, "c_prefix": 0},
    {"iso": "NA", "country": "Namibia", "c_code": 264, "c_prefix": 0},
    {"iso": "NR", "country": "Nauru", "c_code": 674, "c_prefix": -1},	
    {"iso": "NP", "country": "Nepal", "c_code": 977, "c_prefix": 0},
    {"iso": "NL", "country": "Netherlands", "c_code": 31, "c_prefix": 0},
    {"iso": "NC", "country": "New Caledonia", "c_code": 687, "c_prefix": -1},	
    {"iso": "NZ", "country": "New Zealand", "c_code": 64, "c_prefix": 0},
    {"iso": "NI", "country": "Nicaragua", "c_code": 505, "c_prefix": -1},	
    {"iso": "NE", "country": "Niger", "c_code": 227, "c_prefix": -1},	
    {"iso": "NG", "country": "Nigeria", "c_code": 234, "c_prefix": 0},
    {"iso": "NU", "country": "Niue", "c_code": 683, "c_prefix": -1},	
    {"iso": "NF", "country": "Norfolk Island", "c_code": 672, "c_prefix": 0},
    {"iso": "KP", "country": "North Korea", "c_code": 850, "c_prefix": 0},
    {"iso": "MP", "country": "Northern Mariana Islands", "c_code": 1, "c_prefix": -1},	
    {"iso": "NO", "country": "Norway", "c_code": 47, "c_prefix": -1},	
    {"iso": "OM", "country": "Oman", "c_code": 968, "c_prefix": -1},	
    {"iso": "PK", "country": "Pakistan", "c_code": 92, "c_prefix": 0},
    {"iso": "PW", "country": "Palau", "c_code": 680, "c_prefix": -1},	
    {"iso": "PS", "country": "Palestinian Territory", "c_code": 970, "c_prefix": 0},
    {"iso": "PA", "country": "Panama", "c_code": 507, "c_prefix": -1},	
    {"iso": "PG", "country": "Papua New Guinea", "c_code": 675, "c_prefix": -1},	
    {"iso": "PY", "country": "Paraguay", "c_code": 595, "c_prefix": 0},
    {"iso": "PE", "country": "Peru", "c_code": 51, "c_prefix": 0},
    {"iso": "PH", "country": "Philippines", "c_code": 63, "c_prefix": 0},
    {"iso": "PN", "country": "Pitcairn", "c_code": 649, "c_prefix": 0},
    {"iso": "PL", "country": "Poland", "c_code": 48, "c_prefix": 0},
    {"iso": "PT", "country": "Portugal", "c_code": 351, "c_prefix": -1},	
    {"iso": "PR", "country": "Puerto Rico", "c_code": 1, "c_prefix": -1},	
    {"iso": "QA", "country": "Qatar", "c_code": 974, "c_prefix": -1},	
    {"iso": "RE", "country": "Réunion", "c_code": 262, "c_prefix": -1},	
    {"iso": "RO", "country": "Romania", "c_code": 40, "c_prefix": 0},
    {"iso": "RU", "country": "Russian Federation", "c_code": 7, "c_prefix": 8},
    {"iso": "RW", "country": "Rwanda", "c_code": 250, "c_prefix": -1},	
    {"iso": "BL", "country": "Saint Barthélemy", "c_code": 590, "c_prefix": -1},	
    {"iso": "SH", "country": "Saint Helena / Ascension and Tristan Da Cunha", "c_code": 290, "c_prefix": -1},	
    {"iso": "KN", "country": "Saint Kitts and Nevis", "c_code": 1, "c_prefix": -1},	
    {"iso": "LC", "country": "Saint Lucia", "c_code": 1, "c_prefix": -1},	
    {"iso": "MF", "country": "Saint Martin (French Part)", "c_code": 590, "c_prefix": -1},	
    {"iso": "PM", "country": "Saint Pierre and Miquelon", "c_code": 508, "c_prefix": -1},	
    {"iso": "VC", "country": "Saint Vincent and the Grenadines", "c_code": 1, "c_prefix": -1},	
    {"iso": "WS", "country": "Samoa", "c_code": 685, "c_prefix": -1},	
    {"iso": "SM", "country": "San Marino", "c_code": 378, "c_prefix": -1},	
    {"iso": "ST", "country": "Sao Tome and Principe", "c_code": 239, "c_prefix": -1},	
    {"iso": "SA", "country": "Saudi Arabia", "c_code": 966, "c_prefix": 0},
    {"iso": "SN", "country": "Senegal", "c_code": 221, "c_prefix": -1},	
    {"iso": "RS", "country": "Serbia", "c_code": 381, "c_prefix": 0},
    {"iso": "SC", "country": "Seychelles", "c_code": 248, "c_prefix": -1},	
    {"iso": "SL", "country": "Sierra Leone", "c_code": 232, "c_prefix": 0},
    {"iso": "SG", "country": "Singapore", "c_code": 65, "c_prefix": -1},	
    {"iso": "SX", "country": "Sint Maarten (Dutch Part)", "c_code": 1, "c_prefix": -1},	
    {"iso": "SK", "country": "Slovakia", "c_code": 421, "c_prefix": 0},
    {"iso": "SI", "country": "Slovenia", "c_code": 386, "c_prefix": 0},
    {"iso": "SB", "country": "Solomon Islands", "c_code": 677, "c_prefix": -1},	
    {"iso": "SO", "country": "Somalia", "c_code": 252, "c_prefix": -1},	
    {"iso": "ZA", "country": "South Africa", "c_code": 27, "c_prefix": 0},
    {"iso": "GS", "country": "South Georgia and the South Sandwich Islands", "c_code": 27, "c_prefix": 0},
    {"iso": "KR", "country": "South Korea", "c_code": 82, "c_prefix": 0},
    {"iso": "SS", "country": "South Sudan", "c_code": 211, "c_prefix": -1},	
    {"iso": "ES", "country": "Spain", "c_code": 34, "c_prefix": -1},	
    {"iso": "LK", "country": "Sri Lanka", "c_code": 94, "c_prefix": 0},
    {"iso": "SD", "country": "Sudan", "c_code": 249, "c_prefix": 0},
    {"iso": "SR", "country": "Suriname", "c_code": 597, "c_prefix": 0},
    {"iso": "SJ", "country": "Svalbard and Jan Mayen", "c_code": 47, "c_prefix": -1},	
    {"iso": "SZ", "country": "Swaziland", "c_code": 268, "c_prefix": -1},	
    {"iso": "SE", "country": "Sweden", "c_code": 46, "c_prefix": 0},
    {"iso": "SY", "country": "Syrian Arab Republic", "c_code": 963, "c_prefix": 0},
    {"iso": "TW", "country": "Taiwan", "c_code": 886, "c_prefix": 0},
    {"iso": "TJ", "country": "Tajikistan", "c_code": 992, "c_prefix": 8},
    {"iso": "TZ", "country": "Tanzania", "c_code": 255, "c_prefix": 0},
    {"iso": "TH", "country": "Thailand", "c_code": 66, "c_prefix": 0},
    {"iso": "TL", "country": "Timor-Leste", "c_code": 670, "c_prefix": -1},	
    {"iso": "TG", "country": "Togo", "c_code": 228, "c_prefix": -1},	
    {"iso": "TK", "country": "Tokelau", "c_code": 690, "c_prefix": -1},	
    {"iso": "TO", "country": "Tonga", "c_code": 676, "c_prefix": -1},	
    {"iso": "TT", "country": "Trinidad and Tobago", "c_code": 1, "c_prefix": -1},	
    {"iso": "TN", "country": "Tunisia", "c_code": 216, "c_prefix": -1},	
    {"iso": "TR", "country": "Turkey", "c_code": 90, "c_prefix": 0},
    {"iso": "TM", "country": "Turkmenistan", "c_code": 993, "c_prefix": 8},
    {"iso": "TC", "country": "Turks and Caicos Islands", "c_code": 1, "c_prefix": -1},	
    {"iso": "TV", "country": "Tuvalu", "c_code": 688, "c_prefix": -1},	
    {"iso": "UG", "country": "Uganda", "c_code": 256, "c_prefix": 0},
    {"iso": "UA", "country": "Ukraine", "c_code": 380, "c_prefix": 0},
    {"iso": "AE", "country": "United Arab Emirates", "c_code": 971, "c_prefix": 0},
    {"iso": "US", "country": "United States", "c_code": 1, "c_prefix": -1},	
    {"iso": "UM", "country": "United States Minor Outlying Islands", "c_code": 1, "c_prefix": -1},	
    {"iso": "UY", "country": "Uruguay", "c_code": 598, "c_prefix": 0},
    {"iso": "UZ", "country": "Uzbekistan", "c_code": 998, "c_prefix": 8},
    {"iso": "VU", "country": "Vanuatu", "c_code": 678, "c_prefix": -1},	
    {"iso": "VE", "country": "Venezuela", "c_code": 58, "c_prefix": 0},
    {"iso": "VN", "country": "Vietnam", "c_code": 84, "c_prefix": 0},
    {"iso": "VG", "country": "Virgin Islands (British)", "c_code": 1, "c_prefix":-1},	
    {"iso": "VI", "country": "Virgin Islands (U.S.)", "c_code": 1, "c_prefix": -1},	
    {"iso": "WF", "country": "Wallis and Futuna", "c_code": 681, "c_prefix":-1},	
    {"iso": "EH", "country": "Western Sahara", "c_code": -1, "c_prefix":-1},
    {"iso": "YE", "country": "Yemen", "c_code": 967, "c_prefix": 0},
    {"iso": "ZM", "country": "Zambia", "c_code": 260, "c_prefix": 0},
    {"iso": "ZW", "country": "Zimbabwe", "c_code": 263, "c_prefix": 0}
]

           for (var i = 0; i < countries.length; i++) {
               var country = countries[i];
               var label = country.country;
               var iso = country.iso;
               var optionVal = label+'('+iso+')';
               var selected =  (savedcountry == optionVal) ? "selected" : "";
               $("#country").append("<option "+ selected + " value='"+optionVal+"'>"+country.country+"</option>");
           
           //country.label = country.country;
            //label: "+" + country.c_code,
                                  
           }
})
</script>