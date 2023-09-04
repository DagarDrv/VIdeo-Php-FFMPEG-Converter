<!DOCTYPE html>
<html>
<head>
    <title>Video Upload and Conversion</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        #content {
            background-color: #fff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        #uploadForm {
            text-align: center;
        }
        #progressWrapper {
            display: none;
            margin-top: 20px;
        }
        #progressBar {
            width: 0;
            height: 30px;
            background-color: #4CAF50;
            border-radius: 5px;
        }
        #progressText {
            margin-top: 5px;
            text-align: center;
        }
        #conversionOptions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Video Upload and Conversion</h1>
    <div id="content">
        <h2>Upload Video</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="video" id="video" accept="video/*">
            <input type="button" value="Upload" onclick="uploadFile()">
        </form>
        <div id="progressWrapper">
            <div id="progressBar"></div>
            <div id="progressText">0%</div>
        </div>
        <div id="conversionOptions"></div>
        <script>
            function uploadFile() {
                var formData = new FormData(document.getElementById('uploadForm'));
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        // Upload progress
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $("#progressBar").css("width", percentComplete + "%");
                                $("#progressText").text(percentComplete.toFixed(2) + "%");
                            }
                        }, false);
                        return xhr;
                    },
                    url: 'upload.php', // Your upload PHP script
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // Handle the response from the server (e.g., show the conversion options)
                        $("#conversionOptions").html(data);
                        // Start checking progress after upload is complete
                        checkProgress();
                    }
                });
                // Show the progress bar
                $("#progressWrapper").show();
            }
            function checkProgress() {
                $.ajax({
                    url: 'progress.php', // Your progress PHP script
                    type: 'GET',
                    dataType: 'text',
                    success: function(data) {
                        var progress = parseInt(data);
                        if (!isNaN(progress)) {
                            // Update the progress bar
                            $("#progressBar").css("width", progress + "%");
                            $("#progressText").text(progress + "%");
                            // Check progress continuously
                            if (progress < 100) {
                                setTimeout(checkProgress, 1000); // Check every second
                            }
                        }
                    }
                });
            }
            // Start checking progress when the page loads
            $(document).ready(function() {
                // Add any additional setup code here
                // Example: Attach an event listener to the upload button
                $("#uploadButton").click(function() {
                    uploadFile();
                });
            });
        </script>
    </div>
</body>
</html>