<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["videoPath"])) {
    $videoPath = $_POST["videoPath"];
    $outputFormat = $_POST["outputFormat"];
    $outputQuality = $_POST["outputQuality"];
    
    // Specify the output directory (change 'output' to your desired directory)
    $outputDirectory = "output/";
    
    // Ensure the output directory exists, or create it if it doesn't
    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory, 0777, true);
    }

    // Generate a unique output file name with a timestamp
    $timestamp = date("YmdHis"); // Current timestamp
    $randomString = bin2hex(random_bytes(8)); // Random string
    $uniqueFilename = $timestamp . "_" . $randomString . "." . $outputFormat;
    
    // Define the output file path with the specified directory
    $outputFile = $outputDirectory . $uniqueFilename;
    
    // Create an empty output.txt file
    touch("output.txt");
    
    // Perform the conversion using FFmpeg
    $ffmpegCommand = "ffmpeg -i $videoPath -q:v $outputQuality $outputFile 1> output.txt 2>&1";

    exec($ffmpegCommand, $output, $returnCode);
    echo '<html>';
    echo '<head>';
    echo '<link rel="stylesheet" type="text/css" href="style.css">';
    echo '<style>';
    echo '.centered-button { text-align: center; }';
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '<h1>Video Upload and Conversion</h1>';
    if ($returnCode === 0) {
        echo "<p>Conversion completed successfully. <a href=\"$outputFile\" download>Download Converted Video</a></p>";
        
        // Add a centered button to convert another file
        echo '<div class="centered-button">';
        echo '<p><a href="index.html"><button>Convert Another File</button></a></p>';
        echo '</div>';
    } else {
        echo "<p>Conversion failed. Please try again.</p>";
    }
    // Delete the output.txt file
    unlink("output.txt");
    echo '</body>';
    echo '</html>';
}
?>
