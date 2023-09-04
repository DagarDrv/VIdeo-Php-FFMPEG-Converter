<?php
$ffmpeg_output = @file_get_contents('output.txt');
if ($ffmpeg_output) {
    preg_match("/Duration: (.*?), start:/", $ffmpeg_output, $a_match);
    $duration_as_time = $a_match[1];
    $time_array = array_reverse(explode(":", $duration_as_time));
    $duration = floatval($time_array[0]);
    if (!empty($time_array[1])) $duration += intval($time_array[1]) * 60;
    if (!empty($time_array[2])) $duration += intval($time_array[2]) * 60 * 60;
    preg_match_all("/time=(.*?) bitrate/", $ffmpeg_output, $a_match);
    $raw_time = array_pop($a_match);
    if (is_array($raw_time)) {
        $raw_time = array_pop($raw_time);
    }
    $time_array = array_reverse(explode(":", $raw_time));
    $encode_at_time = floatval($time_array[0]);
    if (!empty($time_array[1])) $encode_at_time += intval($time_array[1]) * 60;
    if (!empty($time_array[2])) $encode_at_time += intval($time_array[2]) * 60 * 60;
    echo round(($encode_at_time / $duration) * 100);
}
?>