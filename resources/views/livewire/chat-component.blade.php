<div>
    <div style="overscroll-behavior: none;">
        <div class="fixed w-full bg-green-500 h-16 pt-2 text-white flex justify-between shadow-md  z-10"
            style="top:0px; overscroll-behavior: none;">
            <!-- back button -->
            <a href="/dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12 my-1 text-green-100 ml-2">
                    <path class="text-green-100 fill-current"
                        d="M9.41 11H17a1 1 0 0 1 0 2H9.41l2.3 2.3a1 1 0 1 1-1.42 1.4l-4-4a1 1 0 0 1 0-1.4l4-4a1 1 0 0 1 1.42 1.4L9.4 11z" />
                </svg>
            </a>
            <div class="my-3 text-green-100 font-bold text-lg tracking-wide">{{ $user->name }}</div>
            <!-- 3 dots -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon-dots-vertical w-8 h-8 mt-2 mr-2">
                <path class="text-green-100 fill-current" fill-rule="evenodd"
                    d="M12 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
            </svg>
        </div>

        <div class="mt-20 mb-16">
            @foreach ($messages as $message)
                @php
                    $value = $message['file'];
                    $file = substr($value, 7);

                    // checking file is image of video with his extention
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $isImage = in_array($extension, ['jpeg', 'png', 'jpg', 'gif']);
                    $isVideo = in_array($extension, ['mp4', 'mov', 'avi']);
                    $isPdf = in_array($extension, ['pdf']);

                @endphp

                @if ($message['sender'] != auth()->user()->name)
                    <div class="clearfix w-4/4">
                        <div class="bg-gray-300 mx-4 my-2 p-2 rounded-lg inline-block">
                            <span> <b>{{ $message['sender'] }}: </b></span>
                            @if ($message['file'])
                                @if ($isImage)
                                    <img src="{{ asset('storage/' . $file) }}" alt="Image" width="200px"
                                        height="200px">
                                @elseif($isVideo)
                                    <video width="260" height="180" controls>
                                        <source src="{{ asset('storage/' . $file) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($isPdf)
                                    <embed class="pdf" src="{{ asset('storage/' . $file) }}" width="200px"
                                        height="100px"></embed>
                                @endif
                            @endif
                            <br>

                            @if (trim($message['message']) != '')
                                {{ $message['message'] }}
                            @endif
                        </div>
                    </div>
                @else
                    <div class="clearfix w-4/4">
                        <div class="text-right">
                            <p class="bg-green-300 mx-4 my-2 p-2 rounded-lg inline-block">
                                @if ($message['file'])
                                    @if ($isImage)
                                        <img src="{{ asset('storage/' . $file) }}" alt="Image" width="200px"
                                            height="200px">
                                    @elseif($isVideo)
                                        <video width="260" height="180" controls>
                                            <source src="{{ asset('storage/' . $file) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif($isPdf)
                                        <embed class="pdf" src="{{ asset('storage/' . $file) }}" width="200px"
                                            height="100px"></embed>
                                    @endif
                                @endif

                                @if (trim($message['message']) != '')
                                    {{ $message['message'] }}
                                @endif
                                <b> :you</b>
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <form enctype="multipart/form-data">
        <div class="fixed w-full flex justify-between bg-green-100" style="bottom: 0px;">
            <input class="" type="file" name="file" wire:model="file" id="file"
                accept=".jpg, .jpeg, .png, .gif, .mp3, .wav, .mp4, .mkv, .avi, .doc, .docx, .pdf">
            <textarea id="result" onkeyup="textvaluefun(event)"
                class="flex-grow m-2 py-2 px-4 mr-1 rounded-full border border-gray-300 bg-gray-200 resize-none" rows="1"
                wire:model="message" placeholder="Message..." style="outline: none;"></textarea>
            <button id="start-recognition" class="m-2" style="outline: none;">
                <svg class="svg-inline--fa text-green-400 fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                        transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path
                            d="M 45 0 c -8.481 0 -15.382 6.9 -15.382 15.382 v 29.044 c 0 8.482 6.9 15.382 15.382 15.382 s 15.382 -6.9 15.382 -15.382 V 15.382 C 60.382 6.9 53.481 0 45 0 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,157,51); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 69.245 38.312 c -1.104 0 -2 0.896 -2 2 v 6.505 c 0 12.266 -9.979 22.244 -22.245 22.244 s -22.245 -9.979 -22.245 -22.244 v -6.505 c 0 -1.104 -0.896 -2 -2 -2 s -2 0.896 -2 2 v 6.505 c 0 13.797 10.705 25.134 24.245 26.16 V 86 h -9.126 c -1.104 0 -2 0.896 -2 2 s 0.896 2 2 2 h 22.252 c 1.104 0 2 -0.896 2 -2 s -0.896 -2 -2 -2 H 47 V 72.978 c 13.54 -1.026 24.245 -12.363 24.245 -26.16 v -6.505 C 71.245 39.208 70.35 38.312 69.245 38.312 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,157,51); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                    </g>
                </svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        const startButton = document.getElementById('start-recognition');
        const resultElement = document.getElementById('result');

        function textvaluefun(e) {
            if (e.target.value.length >= 1) {
                startButton.innerHTML = `
                    <span><svg class='svg-inline--fa text-green-400 fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2' aria-hidden='true' focusable='false' data-prefix='fas' data-icon='paper-plane' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                        <path fill='currentColor' d='M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z'/>
                    </svg></span>
                `;
            } else {
                startButton.innerHTML = `<span><svg class="svg-inline--fa text-green-400 fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                    width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;"
                        transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path
                            d="M 45 0 c -8.481 0 -15.382 6.9 -15.382 15.382 v 29.044 c 0 8.482 6.9 15.382 15.382 15.382 s 15.382 -6.9 15.382 -15.382 V 15.382 C 60.382 6.9 53.481 0 45 0 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,157,51); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path
                            d="M 69.245 38.312 c -1.104 0 -2 0.896 -2 2 v 6.505 c 0 12.266 -9.979 22.244 -22.245 22.244 s -22.245 -9.979 -22.245 -22.244 v -6.505 c 0 -1.104 -0.896 -2 -2 -2 s -2 0.896 -2 2 v 6.505 c 0 13.797 10.705 25.134 24.245 26.16 V 86 h -9.126 c -1.104 0 -2 0.896 -2 2 s 0.896 2 2 2 h 22.252 c 1.104 0 2 -0.896 2 -2 s -0.896 -2 -2 -2 H 47 V 72.978 c 13.54 -1.026 24.245 -12.363 24.245 -26.16 v -6.505 C 71.245 39.208 70.35 38.312 69.245 38.312 z"
                            style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,157,51); fill-rule: nonzero; opacity: 1;"
                            transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                    </g>
                </svg></span>`;
            }
        }




        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-IN';

            recognition.onstart = () => {
                resultElement.innerText = 'Listening...';
            };

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                resultElement.innerText = transcript;
            };

            recognition.onerror = (event) => {
                resultElement.innerText = 'Error occurred: ' + event.error;
            };

            recognition.onend = () => {
                resultElement.innerText += ' (End of recognition)';
            };

            startButton.addEventListener('click', () => {
                recognition.start();
            });
        } else {
            resultElement.innerText = 'Speech recognition not supported in this browser.';
        }
    </script>
@endpush
