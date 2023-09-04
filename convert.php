<?php include_once('progress.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["videoPath"])) {
    $videoPath = $_POST["videoPath"];
    $outputFormat = $_POST["outputFormat"];
    $outputQuality = $_POST["outputQuality"];

    // Perform the conversion using FFmpeg
    $outputFile = "converted_video." . $outputFormat;
    $ffmpegCommand = "ffmpeg -i $videoPath -q:v $outputQuality $outputFile 1> output.txt 2>&1";

    exec($ffmpegCommand, $output, $returnCode);
    if ($returnCode === 0) {
        echo "<p>Conversion completed successfully. <a href=\"$outputFile\" download>Download Converted Video</a></p>";
    } else {
        echo "<p>Conversion failed. Please try again.</p>";
    }
}
?>
