<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Replace the text with a button to open the camera -->
                    <button id="openCamera">Open Camera</button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <video id="videoElement" autoplay="" style="display : none; width: 1280px;"></video>
                    <canvas id="canvasElement" style="display : none; width: 1280px; height: 720px;" width="6000" height="3375" density="300"></canvas>
                    <p id="show"></p>

                </div>
            </div>
            <br>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var status = false;

        function displayImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('selectedImage').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        //Select the image input element
        var imageInput = document.getElementById('file_path');

        // Add an event listener to the input element 
        imageInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            var formData = new FormData();
            formData.append('image', file);

            // Send the image file to the Flask server 
            $.ajax({
                type: 'POST',
                url: 'https://exsinnot.com/UpdateImg',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response)
                    displayFormData(response)
                }
            });
        });

        // Function to display the FormData object
        function displayFormData(DATA) {
            var formDataDisplay = document.getElementById('formDataDisplay');
            formDataDisplay.innerHTML = 'Detect: ' + DATA.Detect[0] + ', CO2: ' + DATA.CO2[0] + ', Probability: ' + DATA.Probability[0];
        }
    </script>

    <script>
        // Get a reference to the button element
        const openCameraButton = document.getElementById('openCamera');

        // Add a click event listener to the button
        openCameraButton.addEventListener('click', () => {
            // Use the getUserMedia API to access the camera
            if(videoElement.style.display == "block"){
                videoElement.style.display = "none"
                document.getElementById("show").innerHTML = "";
            }
            else {
                videoElement.style.display = "block"
            }
            
        });

        const videoElement = document.getElementById("videoElement");
        const canvasElement = document.getElementById("canvasElement");
        const canvasContext = canvasElement.getContext("2d");

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            })
            .then(function(stream) {
                videoElement.srcObject = stream;
            })
            .catch(function(error) {
                console.error("Error accessing the webcam: " + error);
            });

        function sendFrame() {
            if (videoElement.style.display == "block"){
            canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            const desiredWidth = 1920;
            const desiredHeight = 1080;
            const dpi = 300;
            canvasElement.width = desiredWidth * (dpi / 96);
            canvasElement.height = desiredHeight * (dpi / 96);
            canvasContext.scale(dpi / 96, dpi / 96);
            canvasContext.drawImage(videoElement, 0, 0, desiredWidth, desiredHeight);


            canvasElement.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('image', blob, 'image.jpg');

                fetch('https://exsinnot.com/upload2', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const showElement = document.getElementById("show");
                        let htmlContent = "";

                        if (data.Detect.length > 0) {
                            for (let i = 0; i < data.Detect.length; i++) {
                                htmlContent += `
                <div style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;">
                    <strong>Object:</strong> ${data.Detect[i]}<br>
                    <strong>Probability:</strong> ${data.Probability[i]}%<br>
                    <strong>CO2D:</strong> ${data.CO2D[i]}<br>
                    <strong>CO2C:</strong> ${data.CO2C[i]}<br>
                    <strong>CO2R:</strong> ${data.CO2R[i]}
                </div>
            `;
                            }
                        } else {
                            htmlContent = "No objects detected.";
                        }

                        showElement.innerHTML = htmlContent;
                    })

                    .catch(error => {
                        console.error('Error sending image: ', error);
                    });
            }, 'image/jpeg', 1.0);
        }
    }
        setInterval(sendFrame, 5000)
    </script>
</x-app-layout>