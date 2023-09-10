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

                    <div class="bg-white border border-gray-300 shadow-lg rounded-lg p-4">
                        <!-- Add this inside your form -->
                        <div class="mb-4">
                            <label for="file_path" class="block mb-2 text-gray-700">Choose a file:</label>
                            <div class="relative border border-gray-400 rounded-md p-2 bg-white shadow-md">
                                <input id="file_path" name="file_path" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" capture="camera" onchange="displayImage(this)" />
                                <div class="text-center">
                                    <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">Select a file</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center items-center bg-white border border-gray-300 shadow-lg rounded-lg p-4 text-center">
                            <img id="selectedImage" src="{{ asset('images/placeholder.jpg') }}" alt="Selected File" width="600" height="600">
                        </div>
                        <div id="formDataDisplay"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
                url: 'http://210.246.215.133/UpdateImg',
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
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then((stream) => {
                    // Create a video element to display the camera feed
                    const videoElement = document.createElement('video');
                    videoElement.srcObject = stream;
                    videoElement.autoplay = true;

                    // Append the video element to the document
                    document.body.appendChild(videoElement);
                })
                .catch((error) => {
                    console.error('Error accessing camera:', error);
                });
        });
    </script>
</x-app-layout>