<?php
$ffmpeg_output = @file_get_contents('output.txt');

if ($ffmpeg_output) {
    preg_match_all("/time=(.*?) bitrate/", $ffmpeg_output, $matches);
    $last_match = end($matches[1]);
    $time_array = explode(':', $last_match);
    
    if (count($time_array) == 3) {
        $encode_at_time = floatval($time_array[0]) * 3600 + floatval($time_array[1]) * 60 + floatval($time_array[2]);
        
        preg_match("/Duration: (.*?), start:/", $ffmpeg_output, $duration_match);
        $duration_array = explode(':', $duration_match[1]);
        $total_duration = floatval($duration_array[0]) * 3600 + floatval($duration_array[1]) * 60 + floatval($duration_array[2]);
        
        $progress = ($encode_at_time / $total_duration) * 100;
        echo round($progress);
    } else {
        // Unable to parse time, progress not available
        echo "0";
    }
} else {
    // No ffmpeg output available, progress not available
    echo "0";
}
?>