// resources/js/camera.js

export default function initCamera() {
    const startCameraButton = document.querySelector("#start-camera");
    if (!startCameraButton) return; // Keluar jika elemen tidak ada di halaman

    const video = document.querySelector("#video");
    const clickPhotoButton = document.querySelector("#click-photo");
    const closeCameraButton = document.querySelector("#close-camera");
    const canvas = document.querySelector("#canvas");
    const photoPreview = document.querySelector("#photo-preview");
    const cameraContainer = document.querySelector("#camera-container");
    const photoOptions = document.querySelector("#photo-options");
    const previewContainer = document.querySelector("#photo-preview-container");
    const removePhotoButton = document.querySelector("#remove-photo");
    const photoInput = document.querySelector("#photo");
    const fileNameDisplay = document.querySelector("#file-name");

    let stream = null;

    // Aktifkan Kamera
    startCameraButton.addEventListener('click', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "environment" },
                audio: false 
            });
            video.srcObject = stream;
            cameraContainer.classList.remove('hidden');
            photoOptions.classList.add('hidden');
            previewContainer.classList.add('hidden');
        } catch (error) {
            alert("Akses kamera ditolak atau tidak didukung.");
        }
    });

    // Ambil Foto
    clickPhotoButton.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        canvas.toBlob(function(blob) {
            const file = new File([blob], "capture.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;

            photoPreview.src = URL.createObjectURL(blob);
            previewContainer.classList.remove('hidden');
            stopCamera();
            photoOptions.classList.add('hidden');
        }, 'image/jpeg', 0.8);
    });

    // Pilih File Manual
    photoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileNameDisplay.textContent = "File: " + this.files[0].name;
            fileNameDisplay.classList.remove('hidden');
        }
    });

    // Reset Foto
    removePhotoButton.addEventListener('click', function() {
        photoInput.value = "";
        previewContainer.classList.add('hidden');
        photoOptions.classList.remove('hidden');
        fileNameDisplay.classList.add('hidden');
    });

    closeCameraButton.addEventListener('click', stopCamera);

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        cameraContainer.classList.add('hidden');
        photoOptions.classList.remove('hidden');
    }
}