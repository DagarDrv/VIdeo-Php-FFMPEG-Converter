<!DOCTYPE html>
<html>
<head>
    <title>Video Upload and Conversion</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Video Upload and Conversion</h1>
    <div id="content">
        <h2>Upload Video</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="video" id="video" accept="video/*">
            <input type="button" value="Upload" id="uploadButton">
        </form>
        <div id="progressWrapper" style="display: none;">
            <div id="progressBar"></div>
            <div id="progressText">0%</div>
        </div>
        <div id="conversionOptions"></div>
        <form id="conversionForm" style="display: none;" action="convert.php" method="post">
            <input type="hidden" name="videoPath" id="videoPath">
            <input type="hidden" name="outputFormat" id="outputFormat">
            <input type="hidden" name="outputQuality" id="outputQuality">
        </form>
        <input type="button" value="Start Conversion" id="startConversionButton" style="display: none;">
    </div>

    <div id="conversionProgress" style="display: none;">
        <h2>Conversion Progress</h2>
        <div id="conversionProgressBar"></div>
        <div id="conversionProgressText">0%</div>
    </div>

    <script>
        $("#uploadButton").click(function () {
            var formData = new FormData(document.getElementById('uploadForm'));

            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $("#progressBar").css("width", percentComplete + "%");
                            $("#progressText").text(percentComplete.toFixed(2) + "%");
                        }
                    }, false);
                    return xhr;
                },
                url: 'upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    $("#conversionOptions").html(data);

                    $("#uploadForm").hide();
                    $("#startConversionButton").show();
                    $("#conversionForm").show();

                    // Set the form fields with the selected options
                    $("#videoPath").val($("#uploadedFilePath").val());
                    $("#outputFormat").val($("#selectedFormat").val());
                    $("#outputQuality").val($("#selectedQuality").val());
                }
            });

            $("#progressWrapper").show();
        });

        $("#startConversionButton").click(function () {
            // Submit the conversion form to start the conversion
            $("#conversionForm").submit();
            checkConversionProgress();
        });

        function checkConversionProgress() {
            $("#conversionProgress").show(); // Show the conversion progress section

            function updateProgress() {
                $.ajax({
                    url: 'progress.php',
                    type: 'GET',
                    dataType: 'text',
                    success: function (data) {
                        var progress = parseInt(data);
                        if (!isNaN(progress)) {
                            $("#conversionProgressBar").css("width", progress + "%");
                            $("#conversionProgressText").text(progress + "%");

                            if (progress < 100) {
                                setTimeout(updateProgress, 1000);
                            } else {
                                $("#conversionProgressText").text("Conversion completed!");
                            }
                        }
                    }
                });
            }

            updateProgress(); // Start updating the progress
        }
    </script>
</body>
</html>