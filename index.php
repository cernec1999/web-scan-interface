<!DOCTYPE html>
    <head>
        <title>Scanner Web Interface</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id = "header">
            <h1>Scanner Web Interface</h1>
            <p>A simple interface to quickly scan images</p>
        </div>

        <div id = "container">
            <div class = "col" id = "left">
                <p><strong>Result Image Will Display Here</strong></p>
            </div>

            <div class = "col" id = "right">
                <p><strong>Scanner device</strong></p>
                <p>Canon PIXMA MX492</p>

                <!--<div class="block">
                    <label>Image Format</label>
                    <input type="format" />
                </div>-->

                <p><strong>Progress</strong></p>
                <div id = "serverData">
                    Please press "Scan Image" to begin
                </div>

                <button onclick="scanImage()" id = "scanButton">Scan Image</button>

				<p><strong>Files Scanned</strong></p>
				<div id = "filesScanned">

				</div>
                <p><strong>PDF Progress</strong></p>
                <div id = "pdfData">
                    Please press "Download Compiled PDF" to begin
                </div>
                <div id = "scannedButton">
                     <button onclick="downloadPDF()" id = "pdfButton">Download Compiled PDF</button>
                     <button onclick="clearImages()" id = "clearButton">Clear Scanned Images</button>
                </div>
            </div>
        </div>



        <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>

        <script type="text/javascript">
            function downloadPDF() {
                document.getElementById("pdfButton").disabled = true;
                document.getElementById("pdfData").innerHTML = "Starting command...";
                var last_response_len = false;
                $.ajax('./convert.php', {
                    xhrFields: {
                        onprogress: function(e)
                        {
                            var this_response, response = e.currentTarget.response;
                            if(last_response_len === false)
                            {
                                this_response = response;
                                last_response_len = response.length;
                            }
                            else
                            {
                                this_response = response.substring(last_response_len);
                                last_response_len = response.length;
                            }
                            //console.log(this_response);
                            var lastIndexOf = response.lastIndexOf('\r');
                            var output = response.substring(response.lastIndexOf('\r', lastIndexOf - 1) + 1, lastIndexOf);
                            //console.log(response);
                            document.getElementById("pdfData").innerHTML = output;
                        }
                    }
                })
                .done(function(data)
                {
                    document.getElementById("pdfButton").disabled = false;
                    var lastIndexOf = data.lastIndexOf('\r') + 1;
                    var output = data.substring(lastIndexOf);
                    document.getElementById("pdfData").innerHTML = "File saved as <a href = \"./tmp/" + output + "\" download>" + output + "</a>";

                    //console.log(data);
                    //console.log(output);
                    //console.log(elem.src);
					buildScanList();
                })
                .fail(function(data)
                {
                    document.getElementById("scanButton").disabled = false;
                    document.getElementById("serverData").innerHTML = data;
                });
            }

            function clearImages() {
				$.ajax('./clear.php').done(function(data) {
					buildScanList();
				});
            }

			function buildScanList() {
                $.ajax('./list.php').done(function(data) {
					document.getElementById("filesScanned").innerHTML = "";
                    var imgArr = JSON.parse(data);
					for(var i = 0; i < imgArr.length; i++) {
						document.getElementById("filesScanned").innerHTML += "<a href = \"./tmp/" + imgArr[i] + "\"><img src = \"./tmp/" + imgArr[i] + "\" height=\"300px\" /></a>";
					}
                });
			}

			window.onload = function() {
                buildScanList();
			}

            function scanImage() {
                document.getElementById("scanButton").disabled = true;
                document.getElementById("serverData").innerHTML = "Starting scanner...";

                var last_response_len = false;
                $.ajax('./scan.php', {
                    xhrFields: {
                        onprogress: function(e)
                        {
                            var this_response, response = e.currentTarget.response;
                            if(last_response_len === false)
                            {
                                this_response = response;
                                last_response_len = response.length;
                            }
                            else
                            {
                                this_response = response.substring(last_response_len);
                                last_response_len = response.length;
                            }
                            //console.log(this_response);
                            var lastIndexOf = response.lastIndexOf('\r');
                            var output = response.substring(response.lastIndexOf('\r', lastIndexOf - 1) + 1, lastIndexOf);
                            //console.log(response);
                            document.getElementById("serverData").innerHTML = output;
                        }
                    }
                })
                .done(function(data)
                {
                    document.getElementById("scanButton").disabled = false;
                    var lastIndexOf = data.lastIndexOf('\r') + 1;
                    var output = data.substring(lastIndexOf);
                    document.getElementById("serverData").innerHTML = "File saved as <a href = \"./tmp/" + output + "\" download>" + output + "</a>";

                    var elem = document.createElement("img");
                    elem.src = 'tmp/' + output;
                    //console.log(data);
                    //console.log(output);
                    //console.log(elem.src);
                    document.getElementById("left").innerHTML = "";
                    document.getElementById("left").appendChild(elem);
					buildScanList();
                })
                .fail(function(data)
                {
                    document.getElementById("scanButton").disabled = false;
                    document.getElementById("serverData").innerHTML = data;
                });
            }
        </script>
    </body>
</html>
