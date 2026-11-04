<?php

function mikhmon_v3_expiration_mode_config($mode)
{
    if (!is_string($mode)) {
        throw new InvalidArgumentException('Expired mode must be one of: None, Remove, Notice, Remove & Record, Notice & Record.');
    }

    $modes = array(
        '0' => array('monitor' => false, 'action' => '', 'record' => false),
        'rem' => array('monitor' => true, 'action' => 'remove', 'record' => false),
        'ntf' => array('monitor' => true, 'action' => 'set limit-uptime=1s', 'record' => false),
        'remc' => array('monitor' => true, 'action' => 'remove', 'record' => true),
        'ntfc' => array('monitor' => true, 'action' => 'set limit-uptime=1s', 'record' => true),
    );
    if (!isset($modes[$mode])) {
        throw new InvalidArgumentException('Expired mode must be one of: None, Remove, Notice, Remove & Record, Notice & Record.');
    }
    return $modes[$mode];
}

function mikhmon_v3_routeros_string_content($value)
{
    $value = (string) $value;
    if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $value) === 1) {
        throw new InvalidArgumentException('RouterOS string values may not contain control characters.');
    }
    return strtr($value, array(
        '\\' => '\\\\',
        '"' => '\\"',
        '$' => '\\$',
        "\n" => '\\n',
        "\r" => '\\r',
        "\t" => '\\t',
    ));
}

function mikhmon_v3_non_negative_decimal($value, $fieldName)
{
    if (!is_string($value)) {
        throw new InvalidArgumentException($fieldName . ' must be a non-negative decimal number.');
    }
    $value = trim($value);
    if (preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $value) !== 1) {
        throw new InvalidArgumentException($fieldName . ' must be a non-negative decimal number.');
    }
    return $value;
}

function mikhmon_v3_positive_routeros_interval($value)
{
    if (!is_string($value)) {
        throw new InvalidArgumentException('Validity must be a positive RouterOS interval such as 30d, 12h, or 5h30m.');
    }
    $value = trim($value);
    if (preg_match('/^(?:[0-9]+w)?(?:[0-9]+d)?(?:[0-9]+h)?(?:[0-9]+m)?(?:[0-9]+s)?$/', $value) !== 1 || preg_match('/[1-9]/', $value) !== 1) {
        throw new InvalidArgumentException('Validity must be a positive RouterOS interval such as 30d, 12h, or 5h30m.');
    }
    return $value;
}

function mikhmon_v3_lock_config($lockLabel)
{
    if ($lockLabel === 'Enable') {
        return array(
            'label' => 'Enable',
            'source' => '; :local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]',
        );
    }
    if ($lockLabel === 'Disable') {
        return array('label' => 'Disable', 'source' => '');
    }
    throw new InvalidArgumentException('Lock user must be either Enable or Disable.');
}

function mikhmon_v3_profile_onlogin_source($mode, $price, $validity, $sellingPrice, $lockLabel, $profileName)
{
    $config = mikhmon_v3_expiration_mode_config($mode);
    $price = mikhmon_v3_non_negative_decimal($price, 'Price');
    $sellingPrice = mikhmon_v3_non_negative_decimal($sellingPrice, 'Selling price');
    $lock = mikhmon_v3_lock_config($lockLabel);
    if (!is_string($profileName) || trim($profileName) === '') {
        throw new InvalidArgumentException('RouterOS profile name is required.');
    }
    if (!is_string($validity)) {
        throw new InvalidArgumentException('Validity must be a RouterOS interval.');
    }
    if ($config['monitor'] || trim($validity) !== '') {
        $validity = mikhmon_v3_positive_routeros_interval($validity);
    }

    $modeSource = mikhmon_v3_routeros_string_content($mode);
    $priceSource = mikhmon_v3_routeros_string_content($price);
    $validitySource = mikhmon_v3_routeros_string_content($validity);
    $sellingPriceSource = mikhmon_v3_routeros_string_content($sellingPrice);
    $lockLabelSource = mikhmon_v3_routeros_string_content($lock['label']);
    $profileNameSource = mikhmon_v3_routeros_string_content($profileName);

    if (!$config['monitor']) {
        return ':put (",,' . $priceSource . ',,,noexp,' . $lockLabelSource . ',")' . $lock['source'];
    }

    $legacyDateHelper = ':local toLegacyDate do={:local monthnames "janfebmaraprmayjunjulaugsepoctnovdec"; :local ds [:tostr $d]; :if ([:pick $ds 4 5] = "-") do={:local year [:pick $ds 0 4]; :local month [:tonum [:pick $ds 5 7]]; :local day [:pick $ds 8 10]; :local idx (($month - 1) * 3); :local monthname [:pick $monthnames $idx ($idx + 3)]; :return ($monthname . "/" . $day . "/" . $year);} else={:return $ds;}};';
    $record = '; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ :local mac $"mac-address"; :local time [/system clock get time ]; ' . $legacyDateHelper . ' :local date [ /system clock get date ]; :local legacyDate [$toLegacyDate d=$date]; :local year [ :pick $legacyDate 7 11 ]; :local month [ :pick $legacyDate 0 3 ]; /system script add name="$legacyDate-|-$time-|-$user-|-' . $priceSource . '-|-$address-|-$mac-|-' . $validitySource . '-|-' . $profileNameSource . '-|-$comment" owner="$month$year" source=$legacyDate comment="mikhmon";};';
    $onLogin = ':put (",' . $modeSource . ',' . $priceSource . ',' . $validitySource . ',' . $sellingPriceSource . ',,' . $lockLabelSource . ',"); { ' . $legacyDateHelper . ' :local date [ /system clock get date ]; :local legacyDate [$toLegacyDate d=$date]; :local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pick $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /system scheduler add name="$user" disabled=no start-date=$date interval="' . $validitySource . '"; :delay 5s; :local nextRun [ /system scheduler get [ /system scheduler find where name="$user" ] next-run]; :local exp $nextRun; :if ([:len $nextRun] = 0) do={ :set exp $legacyDate; } else={ :if ([:len $nextRun] = 8) do={ :set exp ($legacyDate . " " . $nextRun); } else={ :if ([:pick $nextRun 4 5] = "-") do={ :set exp ([$toLegacyDate d=[:pick $nextRun 0 10]] . " " . [:pick $nextRun 11 19]); } else={ :if ([:len $nextRun] = 15) do={ :set exp ([:pick $nextRun 0 6] . "/" . [:pick $legacyDate 7 11] . " " . [:pick $nextRun 7 15]); } else={ :set exp ([$toLegacyDate d=[:pick $nextRun 0 11]] . " " . [:pick $nextRun 12 20]); }; }; }; }; /ip hotspot user set comment="$exp" [find where name="$user"]; :delay 5s; /system scheduler remove [find where name="$user"]';
    if ($config['record']) {
        $onLogin .= $record;
    }
    return $onLogin . $lock['source'] . '}}';
}

function mikhmon_v3_profile_expiration_monitor_source($profileName, $mode)
{
    if (!is_string($profileName) || trim($profileName) === '') {
        throw new InvalidArgumentException('RouterOS profile name is required.');
    }
    $profileName = trim($profileName);
    $profileName = mikhmon_v3_routeros_string_content($profileName);
    if ($mode === 'remove') {
        $expireAction = '[ /ip hotspot user remove $i ];';
    } elseif ($mode === 'set limit-uptime=1s') {
        $expireAction = '[ /ip hotspot user set limit-uptime=1s $i ];';
    } else {
        throw new InvalidArgumentException('Unsupported RouterOS expiration mode: ' . $mode);
    }

    return ':local montharray ("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");:local date [ /system clock get date ];:local time [ /system clock get time ];:local today 0;:if ([:pick $date 4 5] = "-") do={:set today [:tonum ([:pick $date 0 4].[:pick $date 5 7].[:pick $date 8 10])];} else={:local currentmonth ([ :find $montharray [:pick $date 0 3]] + 1);:local currentzero "";:if ([:len $currentmonth] = 1) do={:set currentzero "0";};:set today [:tonum ([:pick $date 7 11].$currentzero.$currentmonth.[:pick $date 4 6])];};:local curtime (([:tonum [:pick $time 0 2]] * 60) + [:tonum [:pick $time 3 5]]);:foreach i in [ /ip hotspot user find where profile="' . $profileName . '" ] do={:local comment [ /ip hotspot user get $i comment];:local name [ /ip hotspot user get $i name];:if ([:pick $comment 4 5] = "-") do={:local expd [:tonum ([:pick $comment 0 4].[:pick $comment 5 7].[:pick $comment 8 10])];:local expt (([:tonum [:pick $comment 11 13]] * 60) + [:tonum [:pick $comment 14 16]]);:if (($expd < $today) or ($expd = $today and $expt < $curtime)) do={' . $expireAction . '[ /ip hotspot active remove [find where user=$name] ];}} else={:if ([:pick $comment 3 4] = "/" and [:pick $comment 6 7] = "/") do={:local expmonth ([ :find $montharray [:pick $comment 0 3]] + 1);:local expzero "";:if ([:len $expmonth] = 1) do={:set expzero "0";};:local expd [:tonum ([:pick $comment 7 11].$expzero.$expmonth.[:pick $comment 4 6])];:local expt (([:tonum [:pick $comment 12 14]] * 60) + [:tonum [:pick $comment 15 17]]);:if (($expd < $today) or ($expd = $today and $expt < $curtime)) do={' . $expireAction . '[ /ip hotspot active remove [find where user=$name] ];}}}}';
}

// RouterOS 7.10+ prints the clock date as 2026-11-04 instead of nov/04/2026.
// Mikhmon's reports and expiry comments use the old form, so convert it.
function mikhmon_v3_legacy_date($date)
{
    $date = (string) $date;
    if (preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $date, $m) !== 1) {
        return $date;
    }
    $months = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
    $month = (int) $m[2];
    if ($month < 1 || $month > 12) {
        return $date;
    }
    return $months[$month - 1] . '/' . $m[3] . '/' . $m[1];
}
