import './bootstrap';
import 'flowbite';

function openPdfModal(pdfUrl) {
    const iframe = document.getElementById('pdfViewer');
    iframe.src = pdfUrl;
    Livewire.emit('openPdfModal'); // Trigger Livewire event to open the modal
}