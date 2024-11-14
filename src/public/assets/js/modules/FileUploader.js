export default class FileUploader {
    constructor(dropZoneId, uploadUrl = '/api/upload') {
        this.dropZone = document.getElementById(dropZoneId);
        this.uploadUrl = uploadUrl;
        this.init();
    }

    init() {
        if (!this.dropZone) {
            console.error(`Drop zone with ID "${this.dropZoneId}" not found.`);
            return;
        }

        this.dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropZone.classList.add('dragging');
        });

        this.dropZone.addEventListener('dragleave', () => {
            this.dropZone.classList.remove('dragging');
        });

        this.dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dropZone.classList.remove('dragging');

            const files = Array.from(e.dataTransfer.files);
            this.handleFiles(files);
        });

        this.dropZone.addEventListener('click', () => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.multiple = true;
            fileInput.addEventListener('change', () => {
                const files = Array.from(fileInput.files);
                this.handleFiles(files);
            });
            fileInput.click();
        });
    }

    handleFiles(files) {
        files.forEach((file) => {
            console.log('File dropped:', file.name);
            this.uploadFile(file);
        });
    }

    uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        fetch(this.uploadUrl, {
            method: 'POST',
            body: formData,
        })
            .then((response) => {
                if (response.ok) {
                    console.log(`File uploaded successfully: ${file.name}`);
                } else {
                    console.error(`Failed to upload file: ${file.name}`);
                }
            })
            .catch((error) => {
                console.error(`Error uploading file: ${file.name}`, error);
            });
    }
}
