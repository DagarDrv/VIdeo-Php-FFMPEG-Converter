<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"])) {
    $uploadDir = "uploads/";

    // Get the original file name and sanitize it
    $originalFileName = $_FILES["video"]["name"];
    $sanitizedFileName = preg_replace("/[^A-Za-z0-9_.\-]/", "-", $originalFileName);

    // Construct the full path including the sanitized file name
    $uploadFile = $uploadDir . $sanitizedFileName;

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $uploadFile)) {
        echo "<h2>Conversion Options</h2>";
        echo "<input type=\"hidden\" id=\"uploadedFilePath\" value=\"$uploadFile\">";
        
        echo "<label for=\"outputFormat\">Select output format:</label>";
        echo "<select id=\"selectedFormat\">";
        echo "<option value=\"mp4\">MP4</option>";
        echo "<option value=\"avi\">AVI</option>";
        echo "</select><br>";
        
        echo "<label for=\"outputQuality\">Select output quality:</label>";
        echo "<select id=\"selectedQuality\">";
        echo "<option value=\"1\">High</option>";
        echo "<option value=\"2\">Medium</option>";
        echo "<option value=\"3\">Low</option>";
        echo "</select><br>";
    } else {
        echo "<p>There was an error uploading your video.</p>";
    }
} else {
    echo "<p>No file uploaded.</p>";
}
?>