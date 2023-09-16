<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload') }}
        </h2>
    </x-slot>

    <div class="py-12 ">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <div class="mb-4">
                    <!-- Input for file selection -->
                    <label for="file_path" class="block mb-2 text-gray-700 dark:text-gray-300">Choose a file:</label>
                    <div class="relative border border-gray-400 rounded-md p-2 bg-white dark:bg-gray-700 shadow-md">
                        <input id="file_path" name="file_path" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" capture="camera" onchange="displayImage(this)" />
                        <div class="text-center">
                            <svg class="mx-auto h-6 w-6 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Select a file</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center items-center bg-white dark:bg-gray-700 border border-gray-300 shadow-lg rounded-lg p-4 text-center">
                    <!-- Display the selected image -->
                    <img id="selectedImage" src="{{ asset('images/placeholder.jpg') }}" alt="Selected File" class="rounded-lg border-4 border-blue-500" style="max-width: 100%; max-height: 400px; object-fit: cover;">
                </div>

                <!-- Display the detected objects -->
                <div id="show"></div>

            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        function displayFormData(data) {
            //var formDataDisplay = document.getElementById('formDataDisplay');
            //formDataDisplay.innerHTML = 'Detect: ' + DATA.Detect[0] + ', CO2: ' + DATA.CO2[0] + ', Probability: ' + DATA.Probability[0];
            const showElement = document.getElementById("show");
            let htmlContent = "";

            if (data.Detect.length > 0) {
                for (let i = 0; i < data.Detect.length; i++) {
                    htmlContent += `
                    <div style="margin-bottom: 10px; border: 1px solid #ccc; padding: 10px;">
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
        }
    </script>

    <script>
        // Get a reference to the button element
        const openCameraButton = document.getElementById('openCamera');

        // Add a click event listener to the button
        openCameraButton.addEventListener('click', () => {
            // Use the getUserMedia API to access the camera
            status = true
            videoElement.style.display = "block"
        });
    </script>

</x-app-layout>